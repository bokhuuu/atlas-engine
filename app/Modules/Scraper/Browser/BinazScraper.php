<?php

declare(strict_types=1);

namespace App\Modules\Scraper\Browser;

use App\Modules\Scraper\Contracts\ScraperProfileInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\DomCrawler\Crawler;
use Throwable;

/**
 * HTTP scraper for bina.az - Azerbaijan's largest real estate platform.
 *
 * Unlike ListAmScraper which drives a real Chrome browser, BinazScraper
 * uses plain HTTP requests only:
 *   - Guzzle for making HTTP requests (GraphQL, detail pages, phones)
 *   - DomCrawler for parsing HTML with CSS selectors
 *   - JSON-LD script tag for structured listing data (price, description, address)
 *
 * No Selenium, no ChromeDriver, no browser sessions.
 * bina.az serves fully server-rendered HTML - no JavaScript execution needed.
 *
 * Cursor pagination note:
 *   bina.az uses cursor-based pagination - each response returns a cursor
 *   for the next page. This is sequential by nature and cannot be parallelised.
 *   Therefore crawlIndexPage() runs the full cursor loop internally and returns
 *   ALL listing URLs in one call. ScraperManager dispatches only ONE
 *   CrawlIndexPageJob per Baku profile (AbstractBinaAzProfile::getMaxPages()=1).
 *   The number of cursor fetches is controlled by
 *   config scraper.profile_config.{name}.max_pages read inside this loop.
 */
class BinazScraper extends BaseScraper
{
    private Client $client;

    public function __construct(
        ScraperProfileInterface $profile,
        ProxyResolver $proxyResolver,
    ) {
        parent::__construct($profile, $proxyResolver);

        $this->client = new Client([
            'timeout' => 30,
            'headers' => [
                'User-Agent' => config('scraper.user_agent'),
                'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                'Accept-Language' => 'ru-RU,ru;q=0.9',
            ],
        ]);
    }

    /**
     * BinazScraper never uses a browser - override to prevent
     * BaseScraper from spinning up Chrome if closeBrowser() is called.
     */
    public function closeBrowser(): void
    {
        // No browser to close - HTTP client needs no teardown.
    }

    /**
     * Run the full cursor pagination loop and return all listing URLs.
     *
     * Ignores the $page parameter - bina.az cursor pagination is sequential
     * and must be handled in one loop rather than parallel jobs.
     *
     * Number of GraphQL fetches is controlled by:
     *   config('scraper.profile_config.{profileName}.max_pages')
     *
     * Each fetch returns 16 listings. The cursor from each response
     * is passed into the next request.
     */
    public function crawlIndexPage(int $page): array
    {
        if ($page > 1) {
            return [];
        }

        $maxFetches = (int) config(
            "scraper.profile_config.{$this->profile->getName()}.max_pages",
            config('scraper.default_max_pages')
        );

        $urls = [];
        $cursor = null;

        for ($fetch = 1; $fetch <= $maxFetches; $fetch++) {
            $result = $this->fetchGraphQlPage($cursor);

            if (empty($result['urls'])) {
                break;
            }

            $urls = array_merge($urls, $result['urls']);
            $cursor = $result['cursor'];

            if (! $result['hasNextPage']) {
                break;
            }

            $this->pauseForHuman();
        }

        return $urls;
    }

    /**
     * Fetch one listing detail page and extract all available fields.
     *
     * Makes two HTTP requests:
     *   1. GET https://ru.bina.az/items/{id} - full HTML with JSON-LD + property rows
     *   2. GET https://ru.bina.az/items/{id}/phones?react=true - phone number
     *
     * Returns raw key-value array - pipeline cleans and normalizes.
     * All fields are nullable - missing data never crashes the scrape.
     */
    public function crawlDetailPage(string $url): array
    {
        $id = $this->extractSourceId($url);

        try {
            $html = (string) $this->client->get($url)->getBody();
        } catch (GuzzleException) {
            return $this->emptyListing($id, $url);
        }

        $crawler = new Crawler($html);
        $jsonLd = $this->extractJsonLd($html);
        $itemOffered = $jsonLd['offers']['itemOffered'] ?? [];

        $price = $jsonLd['offers']['price'] ?? null;
        $currency = $jsonLd['offers']['priceCurrency'] ?? 'AZN';
        $areaUnit = $this->extractAreaUnit($crawler, $html);
        $area = $this->extractArea($crawler, $html);

        $areaSqm = ($areaUnit === 'сот' && $area !== null)
            ? $area * 100
            : $area;

        $areaSqm = $areaSqm ?? ($itemOffered['floorSize']['value'] ?? null);

        $this->pauseForHuman();

        return [
            'external_id' => $id,
            'url' => $url,
            'source_profile_name' => $this->profile->getName(),
            'listing_type' => $this->profile->getListingType(),
            'property_type' => $this->profile->getPropertyType(),
            'price' => $price !== null ? (float) $price : null,
            'currency' => $currency,
            'price_per_sqm' => null,
            'area' => $areaSqm,
            'rooms' => $this->extractPropertyRow($crawler, 'Количество комнат')
                ?? (isset($itemOffered['numberOfRooms']) ? (string) $itemOffered['numberOfRooms'] : null),
            'floor' => $this->extractFloor($crawler),
            'total_floors' => $this->extractTotalFloors($crawler),
            'building_type' => $this->extractPropertyRow($crawler, 'Тип строения'),
            'condition' => $this->extractCondition($crawler),
            'is_new_building' => $this->extractIsNewBuilding($crawler),
            'district' => $this->extractDistrict($crawler, $html),
            'address' => $this->extractAddress($jsonLd),
            'description' => $jsonLd['description'] ?? null,
            'agency_name' => $this->extractAgencyName($crawler),
            'phone' => $this->fetchPhone($id),
            'image_urls' => $jsonLd['image'] ?? [],
            'listing_date' => $this->extractListingDate($crawler),
            'has_bill_of_sale' => $this->extractHasBillOfSale($crawler),
            'has_mortgage' => $this->extractHasMortgage($crawler),
            'extras' => [
                'original_price_azn' => $price !== null ? (float) $price : null,
                'area_unit' => $areaUnit,
            ],
            'scraped_at' => $this->scrapedAt(),
        ];
    }

    /**
     * Fire one GraphQL SearchItems query and return urls, cursor, hasNextPage.
     */
    private function fetchGraphQlPage(?string $cursor): array
    {
        $filter = [
            'cityId' => config('scraper.binaz.city_id', '1'),
            'categoryId' => (string) $this->profile->getCategoryId(),
            'leased' => $this->profile->getListingType() === 'rent',
        ];

        if ($this->profile->getLocationIds() !== null) {
            $filter['locationIds'] = $this->profile->getLocationIds();
        }

        $variables = [
            'first' => 16,
            'filter' => $filter,
            'sort' => 'BUMPED_AT_DESC',
        ];

        if ($cursor !== null) {
            $variables['cursor'] = $cursor;
        }

        try {
            $response = $this->client->post('https://ru.bina.az/graphql', [
                'json' => [
                    'operationName' => 'SearchItems',
                    'variables' => $variables,
                    'extensions' => [
                        'persistedQuery' => [
                            'version' => 1,
                            'sha256Hash' => config('scraper.binaz.persisted_query_hash'),
                        ],
                    ],
                ],
            ]);

            $data = json_decode((string) $response->getBody(), true);

            if (! isset($data['data']['categorySearch']['edges'])) {
                return ['urls' => [], 'cursor' => null, 'hasNextPage' => false];
            }

            $edges = $data['data']['categorySearch']['edges'];
            $pageInfo = $data['data']['categorySearch']['pageInfo'];
            $endCursor = $pageInfo['endCursor'] ?? null;
            $hasNextPage = $pageInfo['hasNextPage'] ?? false;

            $urls = array_map(
                fn (array $edge) => 'https://ru.bina.az'.$edge['node']['path'],
                $edges
            );

            return [
                'urls' => $urls,
                'cursor' => $endCursor,
                'hasNextPage' => $hasNextPage,
            ];
        } catch (GuzzleException) {
            return ['urls' => [], 'cursor' => null, 'hasNextPage' => false];
        }
    }

    /**
     * Parse the JSON-LD script tag from the page HTML.
     * Returns the Product object from the @graph array, or empty array.
     * For houses, returns the root object directly (no @graph wrapper).
     */
    private function extractJsonLd(string $html): array
    {
        try {
            preg_match('/<script[^>]*type=["\']application\/ld\+json["\'][^>]*>(.*?)<\/script>/s', $html, $matches);

            if (empty($matches[1])) {
                return [];
            }

            $data = json_decode($matches[1], true);

            if (! isset($data['@graph'])) {
                if (isset($data['offers']['price'])) {
                    return $data;
                }

                return [];
            }

            foreach ($data['@graph'] as $item) {
                if (($item['@type'] ?? '') === 'Product') {
                    return $item;
                }
            }

            return [];
        } catch (Throwable) {
            return [];
        }
    }

    /**
     * Extract a property row value by its label text.
     * bina.az renders specs as label/value pairs inside .product-properties__i divs.
     */
    private function extractPropertyRow(Crawler $crawler, string $label): ?string
    {
        try {
            $value = null;

            $crawler->filter('.product-properties__i')->each(function (Crawler $node) use ($label, &$value) {
                $labelText = trim($node->filter('label')->text(''));
                if ($labelText === $label) {
                    $value = trim($node->filter('.product-properties__i-value')->text(''));
                }
            });

            return $value ?: null;
        } catch (Throwable) {
            return null;
        }
    }

    /**
     * Extract area value from the property row table.
     * bina.az shows "Площадь" row with value like "7 сот" or "85 м²".
     * Returns null if the property row is not found.
     */
    private function extractArea(Crawler $crawler, string $html): ?float
    {
        try {
            $value = $this->extractPropertyRow($crawler, 'Площадь');

            if ($value === null) {
                return null;
            }

            preg_match('/([\d.,]+)/', $value, $m);

            return isset($m[1]) ? (float) str_replace(',', '.', $m[1]) : null;
        } catch (Throwable) {
            return null;
        }
    }

    /**
     * Extract floor number from "Этаж" property row.
     * Apartments: "16 / 17" → 16
     * Offices/commercial: "5" → 5
     * Land: row absent → null
     */
    private function extractFloor(Crawler $crawler): ?string
    {
        $value = $this->extractPropertyRow($crawler, 'Этаж');

        if ($value === null) {
            return null;
        }

        if (str_contains($value, '/')) {
            return trim(explode('/', $value)[0]);
        }

        return $value;
    }

    /**
     * Extract total floors from either:
     * - "Этаж" row "16 / 17" → 17 (apartments)
     * - "Этажей в здании" row "17" → 17 (offices/commercial)
     * - Land: both absent → null
     */
    private function extractTotalFloors(Crawler $crawler): ?string
    {
        $etazh = $this->extractPropertyRow($crawler, 'Этаж');

        if ($etazh !== null && str_contains($etazh, '/')) {
            return trim(explode('/', $etazh)[1]);
        }

        return $this->extractPropertyRow($crawler, 'Этажей в здании');
    }

    /**
     * Extract the area unit (сот or м²) from the page title.
     */
    private function extractAreaUnit(Crawler $crawler, string $html): string
    {
        $value = $this->extractPropertyRow($crawler, 'Площадь');

        if ($value !== null) {
            return str_contains($value, 'сот') ? 'сот' : 'м²';
        }

        return 'м²';
    }

    /**
     * Extract district from the page title.
     * Title formats:
     *   "Участок - 10 сот - пос. Бузовна, Баку"
     *   "Квартира - м. Насими, Баку"
     *   "Офис - Наримановский р., Баку"
     */
    private function extractDistrict(Crawler $crawler, string $html): ?string
    {
        try {
            // Match пос. (settlement) or м. (metro) prefix
            if (preg_match('/—\s*(?:пос\.|м\.)\s*([^,—]+),/u', $html, $matches)) {
                return trim($matches[1]);
            }

            // Match district name directly (e.g. "Наримановский р.")
            if (preg_match('/—\s*([^—,]+р\.),/u', $html, $matches)) {
                return trim($matches[1]);
            }

            return null;
        } catch (Throwable) {
            return null;
        }
    }

    /**
     * Extract full address string from JSON-LD address object.
     */
    private function extractAddress(array $jsonLd): ?string
    {
        if (! isset($jsonLd['address'])) {
            return null;
        }

        if (is_string($jsonLd['address'])) {
            return $jsonLd['address'];
        }

        if (is_array($jsonLd['address'])) {
            return $jsonLd['address']['streetAddress']
                ?? $jsonLd['address']['addressLocality']
                ?? null;
        }

        return null;
    }

    /**
     * Extract condition (repair status) from product label badges.
     */
    private function extractCondition(Crawler $crawler): ?string
    {
        try {
            $condition = null;

            $crawler->filter('.product-labels__i')->each(function (Crawler $node) use (&$condition) {
                $text = trim($node->text(''));
                if (in_array($text, ['С ремонтом', 'Без ремонта', 'Евроремонт'])) {
                    $condition = $text;
                }
            });

            return $condition;
        } catch (Throwable) {
            return null;
        }
    }

    /**
     * Extract whether the listing has a bill of sale (купчая).
     * Critical for Baku pricing - significantly affects property value.
     */
    private function extractHasBillOfSale(Crawler $crawler): bool
    {
        try {
            return $crawler->filter('.product-labels__i-icon--bill-of-sale')->count() > 0;
        } catch (Throwable) {
            return false;
        }
    }

    private function extractHasMortgage(Crawler $crawler): bool
    {
        try {
            $hasMortgage = false;

            $crawler->filter('.product-labels__i')->each(function (Crawler $node) use (&$hasMortgage) {
                if (str_contains($node->text(''), 'ипотека')) {
                    $hasMortgage = true;
                }
            });

            return $hasMortgage;
        } catch (Throwable) {
            return false;
        }
    }

    /**
     * Extract agency name from the contact block.
     *
     * bina.az shows three contact types:
     *   - собственник / elan sahibi - private owner → returns null
     *   - посредник (агент) - individual broker → returns their name
     *   - Агентство недвижимости - real estate agency → returns agency name
     *
     * Returning null for owners makes the listing appear under "Owner"
     * in Data Hub "Listed by" filter, matching bina.az's own distinction.
     */
    private function extractAgencyName(Crawler $crawler): ?string
    {
        try {
            $typeNode = $crawler->filter('.product-owner__info-region');
            $type = $typeNode->count() > 0 ? mb_strtolower(trim($typeNode->text(''))) : '';

            if (
                mb_stripos($type, 'собственник') !== false ||
                mb_stripos($type, 'elan sahibi') !== false ||
                mb_stripos($type, 'sahib') !== false
            ) {
                return null;
            }

            $node = $crawler->filter('.product-owner__info-name');

            return $node->count() > 0 ? trim($node->text('')) : null;
        } catch (Throwable) {
            return null;
        }
    }

    /**
     * Extract whether the listing is a new building from the "Категория" property row.
     * Returns true for "Новостройка", false for "Вторичка", null if row absent.
     * Land and office pages have no "Категория" row — returns null naturally.
     */
    private function extractIsNewBuilding(Crawler $crawler): ?bool
    {
        $value = $this->extractPropertyRow($crawler, 'Категория');

        if ($value === null) {
            return null;
        }

        return str_contains($value, 'Новостройка');
    }

    /**
     * Extract listing date from the page.
     */
    private function extractListingDate(Crawler $crawler): ?string
    {
        try {
            $meta = $crawler->filter('meta[property="og:updated_time"]');
            if ($meta->count() > 0) {
                return $meta->attr('content');
            }

            return null;
        } catch (Throwable) {
            return null;
        }
    }

    /**
     * Fetch phone number via bina.az phones API.
     * GET /items/{id}/phones?react=true → {"phones": ["(055) 929-95-50"]}
     */
    private function fetchPhone(?string $id): ?string
    {
        if ($id === null) {
            return null;
        }

        try {
            $response = $this->client->get(
                "https://ru.bina.az/items/{$id}/phones?react=true"
            );

            $data = json_decode((string) $response->getBody(), true);

            return $data['phones'][0] ?? null;
        } catch (GuzzleException) {
            return null;
        }
    }

    /**
     * Return a minimal empty listing when the detail page fetch fails.
     * ValidateRequiredFieldsStage will reject it - nothing gets persisted.
     */
    private function emptyListing(?string $id, string $url): array
    {
        return [
            'external_id' => $id,
            'url' => $url,
            'source_profile_name' => $this->profile->getName(),
            'listing_type' => $this->profile->getListingType(),
            'property_type' => $this->profile->getPropertyType(),
            'scraped_at' => $this->scrapedAt(),
        ];
    }
}
