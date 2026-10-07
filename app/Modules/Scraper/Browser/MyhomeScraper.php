<?php

declare(strict_types=1);

namespace App\Modules\Scraper\Browser;

use App\Modules\Scraper\Contracts\ScraperProfileInterface;
use Laravel\Dusk\Browser;
use Throwable;

/**
 * Scraper implementation for myhome.ge - Georgia's largest real estate platform.
 *
 * Uses Laravel Dusk + Chrome (same as ListAmScraper) because:
 *   - Cloudflare protection requires a real browser session
 *   - Phone number reveals require authenticated session + JS interaction
 *
 * Data extraction strategy:
 *   myhome.ge (Next.js SSR) embeds all listing data as structured JSON in
 *   <script id="__NEXT_DATA__"> on every detail page. We extract this once
 *   and map fields directly — no fragile DOM selectors needed.
 *
 * Auth: CookieAuthStrategy + FormLoginStrategy (identical to list.am).
 * Cookies stored at storage/app/scraper/cookies/{profileName}.json.
 *
 * Pagination: &page=N URL parameter, 20 listings per page.
 *
 * Index page selector strategy:
 *   All <a href="/en/real-estate/..."> links are collected, then filtered
 *   in PHP to keep only genuine listing URLs (those ending with a numeric ID).
 *   Nav and footer links match the href pattern but lack the numeric ID suffix.
 */
class MyhomeScraper extends BaseScraper
{
    public function __construct(
        ScraperProfileInterface $profile,
        ProxyResolver $proxyResolver,
        ?Browser $browser = null,
    ) {
        parent::__construct($profile, $proxyResolver, $browser);
    }

    /**
     * Adds myhome.ge-specific stealth flags on top of base Chrome arguments.
     * TNet sites share the same Cloudflare setup as myauto.ge - same flags needed.
     */
    protected function chromeArguments(): array
    {
        return [
            ...parent::chromeArguments(),
            ...StealthConfig::chromeFlags(),
        ];
    }

    /**
     * Visit one index page and collect all listing detail page URLs.
     *
     * Navigates to the paginated search results page, collects all
     * <a href="/en/real-estate/..."> links, then filters to keep only
     * genuine listing URLs (slug ending with a numeric ID).
     */
    public function crawlIndexPage(int $page): array
    {
        $url = $this->profile->buildIndexUrl($page);

        $this->navigate($url);
        $this->browser->script(StealthConfig::javascriptPatches());

        $urls = [];

        $links = $this->browser->elements('a[href*="/en/real-estate/"]');

        foreach ($links as $link) {
            $href = $link->getAttribute('href');

            if ($href === null || $href === '') {
                continue;
            }

            // Filter to genuine listing detail URLs only.
            // Listing URLs end with a numeric ID: /en/real-estate/{slug}-{id}/
            // Nav/footer links like /en/real-estate/sale/apartment have no numeric suffix.
            if (! $this->isListingUrl($href)) {
                continue;
            }

            $urls[] = str_starts_with($href, 'http')
                ? $href
                : 'https://www.myhome.ge'.$href;
        }

        return array_unique($urls);
    }

    /**
     * Visit one listing detail page and extract all available fields.
     *
     * myhome.ge (Next.js SSR) embeds all listing data as structured JSON
     * in a <script id="__NEXT_DATA__"> tag on every detail page.
     * We extract this JSON once and map fields directly — no DOM selectors needed.
     *
     * JSON path: pageProps.dehydratedState.queries[n].state.data.statement
     *
     * Returns a raw key-value array - pipeline stages clean and normalize.
     * All fields are nullable - missing data never crashes the scrape.
     */
    public function crawlDetailPage(string $url): array
    {
        $this->navigate($url);
        $this->browser->script(StealthConfig::javascriptPatches());

        $statement = $this->extractNextData();

        return [
            'external_id' => $this->extractId($url),
            'url' => strtok($url, '?'),
            'source_profile_name' => $this->profile->getName(),
            'listing_type' => $this->profile->getListingType(),
            'property_type' => $this->profile->getPropertyType(),
            'price' => $this->extractPrice($statement),
            'currency' => 'GEL',
            'price_per_sqm' => null,
            'area' => isset($statement['area']) ? (string) $statement['area'] : null,
            'rooms' => isset($statement['room_type_id']) ? (string) $statement['room_type_id'] : null,
            'floor' => isset($statement['floor']) ? (string) $statement['floor'] : null,
            'total_floors' => isset($statement['total_floors']) ? (string) $statement['total_floors'] : null,
            'district' => $statement['district_name'] ?? null,
            'address' => $statement['address'] ?? null,
            'description' => isset($statement['comment']) ? strip_tags($statement['comment']) : null,
            'building_type' => null,
            'condition' => null,
            'agency_name' => $this->extractAgencyName($statement),
            'phone' => $this->extractPhone($statement['uuid'] ?? null),
            'image_urls' => $this->extractImageUrls(),
            'listing_date' => $statement['last_updated'] ?? $statement['created_at'] ?? null,
            'extras' => $this->extractExtras($statement),
            'scraped_at' => $this->scrapedAt(),
        ];
    }

    // -------------------------------------------------------------------------
    // Index page helpers
    // -------------------------------------------------------------------------

    /**
     * Check if a URL is a genuine listing detail page.
     *
     * Listing URLs follow the pattern:
     *   /en/real-estate/{slug}-{numeric-id}/
     *
     * Nav/footer links like /en/real-estate/sale/apartment do not end
     * with a numeric segment and are excluded by this check.
     */
    protected function isListingUrl(string $href): bool
    {
        return (bool) preg_match('#/en/real-estate/[^/]+-\d+/?$#', $href);
    }

    // -------------------------------------------------------------------------
    // __NEXT_DATA__ extraction
    // -------------------------------------------------------------------------

    /**
     * Extract the statement object from Next.js __NEXT_DATA__ JSON.
     *
     * myhome.ge embeds all page data in a <script id="__NEXT_DATA__"> tag.
     * The statement data lives at:
     *   pageProps.dehydratedState.queries[n].state.data.statement
     *
     * We find the query whose data contains a "statement" key with an "id" field.
     * Returns empty array if extraction fails — all fields will be null.
     */
    protected function extractNextData(): array
    {
        try {
            $result = $this->browser->script("
                const el = document.querySelector('#__NEXT_DATA__');
                if (!el) return null;
                const data = JSON.parse(el.textContent);
                const queries = data?.props?.pageProps?.dehydratedState?.queries ?? [];
                for (const query of queries) {
                    const statement = query?.state?.data?.statement;
                    if (statement && statement.id) {
                        return JSON.stringify(statement);
                    }
                    const data2 = query?.state?.data?.data?.statement;
                    if (data2 && data2.id) {
                        return JSON.stringify(data2);
                    }
                }
                return null;
            ");

            $json = $result[0] ?? null;

            if ($json === null) {
                return [];
            }

            return json_decode($json, true) ?? [];
        } catch (Throwable) {
            return [];
        }
    }

    // -------------------------------------------------------------------------
    // Detail page extractors
    // -------------------------------------------------------------------------

    /**
     * Extract the numeric listing ID from the URL slug.
     *
     * URL format: /en/real-estate/4-room-apartment-for-sale-in-saburtalo-25738134/
     * Returns: "25738134"
     */
    protected function extractId(string $url): ?string
    {
        $path = rtrim(parse_url($url, PHP_URL_PATH) ?? '', '/');
        $slug = basename($path);

        if (preg_match('/-(\d+)$/', $slug, $matches)) {
            return $matches[1];
        }

        return null;
    }

    /**
     * Extract GEL price from statement JSON.
     *
     * myhome.ge price object: {"1": {"price_total": 1072273, ...}, "2": {...}}
     * Currency IDs: 1 = GEL, 2 = USD, 3 = EUR.
     * We always store GEL (currency_id=1) — pipeline converts to USD.
     */
    private function extractPrice(array $statement): ?string
    {
        // price_total in GEL (currency_id=1)
        $gelPrice = $statement['price']['1']['price_total'] ?? null;

        if ($gelPrice !== null) {
            return (string) $gelPrice;
        }

        // Fallback: total_price field (also GEL)
        $totalPrice = $statement['total_price'] ?? null;

        return $totalPrice !== null ? (string) $totalPrice : null;
    }

    /**
     * Extract agency name from statement JSON.
     *
     * myhome.ge shows is_owner boolean — if true, listing is by owner → return null.
     * If false, return owner_name as agency name.
     */
    private function extractAgencyName(array $statement): ?string
    {
        $isOwner = $statement['is_owner'] ?? true;

        if ($isOwner) {
            return null;
        }

        return isset($statement['owner_name']) && $statement['owner_name'] !== ''
            ? $statement['owner_name']
            : null;
    }

    /**
     * Reveal the phone number by clicking "Show number" in the browser.
     *
     * myhome.ge masks the phone in __NEXT_DATA__ until the button is clicked.
     * After the click, the phone renders as <a href="tel:+995..."> in the DOM.
     * We observe DOM mutations and read the href once it appears.
     *
     * Returns null silently on any failure — phone is optional.
     */
    private function extractPhone(?string $statementUuid): ?string
    {
        if ($statementUuid === null || $statementUuid === '') {
            return null;
        }

        try {
            $result = $this->browser->script("
                return await new Promise((resolve) => {
                    const btn = Array.from(document.querySelectorAll('button'))
                        .find(b => b.textContent.includes('ნომრის ნახვა'));

                    if (!btn) { resolve(null); return; }

                    const observer = new MutationObserver(() => {
                        const tel = document.querySelector('a[href^=\"tel:\"]');
                        if (tel) {
                            observer.disconnect();
                            const phone = tel.href.replace('tel:', '').replace('+995', '').replace(/\\s+/g, '').trim();
                            resolve(phone !== '' ? phone : null);
                        }
                    });

                    observer.observe(document.body, { childList: true, subtree: true });
                    btn.click();
                    setTimeout(() => { observer.disconnect(); resolve(null); }, 8000);
                });
            ");

            $phone = $result[0] ?? null;

            return $phone !== null && $phone !== '' ? (string) $phone : null;
        } catch (Throwable) {
            return null;
        }
    }

    /**
     * Extract listing image URLs from the photo gallery.
     *
     * Images are still read from the DOM — they are not in __NEXT_DATA__.
     * Filters to only TNet CDN URLs to exclude tracking pixels.
     * Deduplicates to exclude thumbnail variants.
     */
    private function extractImageUrls(): array
    {
        try {
            $result = $this->browser->script("
                const imgs = document.querySelectorAll('.statement-photos img, .gallery img, .swiper-slide img');
                return Array.from(imgs)
                    .map(img => img.src || img.getAttribute('data-src'))
                    .filter(src => src && src.includes('tnet.ge') && !src.includes('_thumb'))
                    .filter((v, i, a) => a.indexOf(v) === i);
            ");

            return $result[0] ?? [];
        } catch (Throwable) {
            return [];
        }
    }

    /**
     * Extract myhome.ge-specific fields into the extras JSONB column.
     *
     * Stores sub-district, building status, USD price, coordinates,
     * and original GEL price for reference after USD conversion.
     */
    private function extractExtras(array $statement): array
    {
        $extras = [];

        // Sub-district (more granular than district_name)
        if (isset($statement['urban_name']) && $statement['urban_name'] !== '') {
            $extras['sub_district'] = $statement['urban_name'];
        }

        // Original GEL price for reference
        $gelPrice = $statement['price']['1']['price_total'] ?? $statement['total_price'] ?? null;
        if ($gelPrice !== null) {
            $extras['original_price_gel'] = (string) $gelPrice;
        }

        // USD price from myhome.ge (their own conversion)
        $usdPrice = $statement['price']['2']['price_total'] ?? null;
        if ($usdPrice !== null) {
            $extras['myhome_price_usd'] = (string) $usdPrice;
        }

        // Building status id (1=old, 2=new, 3=under construction)
        if (isset($statement['status_id'])) {
            $extras['status_id'] = $statement['status_id'];
        }

        // Coordinates
        if (isset($statement['lat'], $statement['lng'])) {
            $extras['lat'] = $statement['lat'];
            $extras['lng'] = $statement['lng'];
        }

        // Bedroom count
        if (isset($statement['bedroom_type_id'])) {
            $extras['bedrooms'] = $statement['bedroom_type_id'];
        }

        // Bathroom count
        if (isset($statement['bathroom_type_id'])) {
            $extras['bathrooms'] = $statement['bathroom_type_id'];
        }

        // Ceiling height in cm
        if (isset($statement['height']) && $statement['height'] > 0) {
            $extras['ceiling_height'] = $statement['height'];
        }

        // Statement UUID — needed for phone reveal API
        if (isset($statement['uuid']) && $statement['uuid'] !== '') {
            $extras['statement_uuid'] = $statement['uuid'];
        }

        return $extras;
    }
}
