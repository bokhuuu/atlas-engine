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
 * Phone extraction is written but dormant - extractPhone() exists but is
 * not called from crawlDetailPage(). Enable by adding CleanPhoneStage to
 * the pipeline when Colliers requests phone data.
 *
 * Auth: CookieAuthStrategy + FormLoginStrategy (identical to list.am).
 * Cookies stored at storage/app/scraper/cookies/{profileName}.json.
 *
 * Pagination: &page=N URL parameter, 20 listings per page.
 *
 * Index page selector strategy:
 *   All <a href="/en/real-estate/..."> links are collected, then filtered
 *   in PHP to keep only listing detail URLs (those ending with a numeric ID).
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
     * Returns a raw key-value array - pipeline stages clean and normalize.
     * All fields are nullable - missing data never crashes the scrape.
     */
    public function crawlDetailPage(string $url): array
    {
        $this->navigate($url);
        $this->browser->script(StealthConfig::javascriptPatches());

        return [
            'external_id' => $this->extractId($url),
            'url' => strtok($url, '?'),
            'source_profile_name' => $this->profile->getName(),
            'listing_type' => $this->profile->getListingType(),
            'property_type' => $this->profile->getPropertyType(),
            'price' => $this->extractPrice(),
            'currency' => 'GEL',
            'price_per_sqm' => null,
            'area' => $this->extractArea(),
            'rooms' => $this->extractRooms(),
            'floor' => $this->extractFloor(),
            'total_floors' => $this->extractTotalFloors(),
            'district' => $this->extractDistrict(),
            'address' => $this->extractAddress(),
            'description' => $this->extractDescription(),
            'building_type' => null,
            'condition' => $this->extractCondition(),
            'agency_name' => $this->extractAgencyName(),
            'phone' => $this->extractPhone(),
            'image_urls' => $this->extractImageUrls(),
            'listing_date' => $this->extractListingDate(),
            'extras' => $this->extractExtras(),
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
        // Must match /en/real-estate/{anything}-{digits}/ or end with digits
        return (bool) preg_match('#/en/real-estate/[^/]+-\d+/?$#', $href);
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
     * Extract the GEL price from the listing detail page.
     *
     * myhome.ge renders the price as text with the ₾ symbol.
     * We extract the numeric value via JavaScript to avoid locale parsing issues.
     */
    private function extractPrice(): ?string
    {
        try {
            $result = $this->browser->script("
                const el = document.querySelector('.statement-price .price-value');
                if (el) return el.textContent.replace(/[^0-9.]/g, '').trim();

                // Fallback: look for price in the meta tag
                const meta = document.querySelector('meta[property=\"product:price:amount\"]');
                if (meta) return meta.getAttribute('content');

                return null;
            ");

            $value = $result[0] ?? null;

            return ($value !== null && $value !== '') ? (string) $value : null;
        } catch (Throwable) {
            return null;
        }
    }

    /**
     * Extract area in square metres.
     *
     * myhome.ge shows area in the stats row: "Area\n55 m²"
     * We find the Area label and read the adjacent value.
     */
    private function extractArea(): ?string
    {
        try {
            $result = $this->browser->script("
                const labels = document.querySelectorAll('.statement-parameters .parameter-item');
                for (const item of labels) {
                    const label = item.querySelector('.parameter-label');
                    const value = item.querySelector('.parameter-value');
                    if (label && value && label.textContent.trim() === 'Area') {
                        return value.textContent.replace(/[^0-9.]/g, '').trim();
                    }
                }

                // Fallback: find the Area stat block
                const statBlocks = document.querySelectorAll('.d-flex.align-items-center');
                for (const block of statBlocks) {
                    if (block.textContent.includes('Area')) {
                        const match = block.textContent.match(/(\d+\.?\d*)\s*m²/);
                        if (match) return match[1];
                    }
                }

                return null;
            ");

            return $result[0] ?? null;
        } catch (Throwable) {
            return null;
        }
    }

    /**
     * Extract number of rooms from the stats row.
     */
    private function extractRooms(): ?string
    {
        return $this->extractStatValue('Rooms');
    }

    /**
     * Extract floor number from "Floor" stat (format "4 / 18" → "4").
     */
    private function extractFloor(): ?string
    {
        $value = $this->extractStatValue('Floor');

        if ($value === null) {
            return null;
        }

        if (str_contains($value, '/')) {
            return trim(explode('/', $value)[0]);
        }

        return $value;
    }

    /**
     * Extract total floors from "Floor" stat (format "4 / 18" → "18").
     */
    private function extractTotalFloors(): ?string
    {
        $value = $this->extractStatValue('Floor');

        if ($value === null || ! str_contains($value, '/')) {
            return null;
        }

        return trim(explode('/', $value)[1]);
    }

    /**
     * Extract a value from the listing stats row by label name.
     *
     * myhome.ge detail page shows stats as icon + label + value blocks.
     * This covers Area, Rooms, Bedroom, Floor.
     */
    private function extractStatValue(string $label): ?string
    {
        try {
            $result = $this->browser->script("
                const labelEl = label => {
                    const labels = document.querySelectorAll('.statement-parameters .parameter-label, .d-flex .text-secondary');
                    for (const el of labels) {
                        if (el.textContent.trim() === '{$label}') {
                            const parent = el.closest('.d-flex, .parameter-item');
                            if (parent) {
                                const value = parent.querySelector('.parameter-value, .fw-medium, .text-dark');
                                if (value) return value.textContent.trim();
                            }
                        }
                    }
                    return null;
                };
                return labelEl();
            ");

            return $result[0] ?? null;
        } catch (Throwable) {
            return null;
        }
    }

    /**
     * Extract district from the breadcrumb navigation.
     *
     * Breadcrumb: Sale → Apartment → Tbilisi → Vake-Saburtalo → Saburtalo
     * We take the second-to-last breadcrumb item (parent district level).
     * This is "Vake-Saburtalo" in the example above.
     *
     * NormalizeToEnglishMyhomeStage will map it to a canonical district name.
     */
    private function extractDistrict(): ?string
    {
        try {
            $result = $this->browser->script("
                const items = document.querySelectorAll('.breadcrumb-item, nav[aria-label=\"breadcrumb\"] a, .breadcrumb a');
                const breadcrumbs = Array.from(items).map(el => el.textContent.trim()).filter(t => t.length > 0);

                // Breadcrumb: Sale > Apartment > Tbilisi > Vake-Saburtalo > Saburtalo
                // We want the second-to-last item (parent district), index -2
                if (breadcrumbs.length >= 2) {
                    return breadcrumbs[breadcrumbs.length - 2];
                }

                return null;
            ");

            return $result[0] ?? null;
        } catch (Throwable) {
            return null;
        }
    }

    /**
     * Extract full address string from the listing page.
     *
     * myhome.ge shows address below the title: "Vazha-Pshavela Avenue 71"
     */
    private function extractAddress(): ?string
    {
        try {
            $result = $this->browser->script("
                const el = document.querySelector('.statement-address, .address-text, a[href*=\"maps\"]');
                return el ? el.textContent.trim() : null;
            ");

            return $result[0] ?? null;
        } catch (Throwable) {
            return null;
        }
    }

    /**
     * Extract the listing description text.
     *
     * myhome.ge shows a "Short description" section with Georgian text.
     * Full text may be behind a "See more" button - we grab what is visible.
     * TranslationService translates Georgian → English at pipeline time.
     */
    private function extractDescription(): ?string
    {
        try {
            $result = $this->browser->script("
                const el = document.querySelector('.statement-description, .short-description p, .description-text');
                return el ? el.textContent.trim() : null;
            ");

            return $result[0] ?? null;
        } catch (Throwable) {
            return null;
        }
    }

    /**
     * Extract condition from "Addition parameters" section.
     *
     * myhome.ge shows: Status, Condition, Type of project as labeled pairs.
     * Example: Condition → "Newly renovated"
     */
    private function extractCondition(): ?string
    {
        return $this->extractAdditionParameter('Condition');
    }

    /**
     * Extract a value from the "Addition parameters" section by label.
     *
     * Used for: Condition, Status, Type of project, Bathrooms, etc.
     */
    private function extractAdditionParameter(string $label): ?string
    {
        try {
            $result = $this->browser->script("
                const items = document.querySelectorAll('.addition-parameters .parameter-item, .additional-params .item');
                for (const item of items) {
                    const labelEl = item.querySelector('.param-label, .label, p:first-child');
                    const valueEl = item.querySelector('.param-value, .value, p:last-child');
                    if (labelEl && valueEl && labelEl.textContent.trim() === '{$label}') {
                        return valueEl.textContent.trim();
                    }
                }
                return null;
            ");

            return $result[0] ?? null;
        } catch (Throwable) {
            return null;
        }
    }

    /**
     * Extract agency or owner name from the contact block.
     *
     * myhome.ge shows either "Owner" or the agent/agency name.
     * Returns null for owner listings — consistent with other markets.
     */
    private function extractAgencyName(): ?string
    {
        try {
            $result = $this->browser->script("
                const typeEl = document.querySelector('.contact-type, .user-type, .badge-owner');
                if (typeEl) {
                    const type = typeEl.textContent.trim().toLowerCase();
                    if (type === 'owner') return null;
                }

                const nameEl = document.querySelector('.contact-name, .agent-name, .company-name');
                return nameEl ? nameEl.textContent.trim() : null;
            ");

            return $result[0] ?? null;
        } catch (Throwable) {
            return null;
        }
    }

    /**
     * Reveal and extract the phone number from the listing.
     *
     * Requires authenticated session — CookieAuthStrategy handles this.
     * myhome.ge shows a "Show number" button that reveals the phone on click.
     * Same TNet pattern as myauto.ge. Rate limiting (~5 reveals per IP per day)
     * handled by Webshare rotating residential proxies.
     * Returns null if button not present or reveal fails.
     */
    private function extractPhone(): ?string
    {
        try {
            $showButton = 'button.show-phone-btn, button[class*="show-number"], .phone-reveal-btn';

            if (! $this->isPresent($showButton)) {
                return null;
            }

            $this->ensureAuthenticated();
            $this->click($showButton);

            // Wait for the phone number to appear after the button click
            $this->waitFor('.phone-number, .revealed-phone, a[href^="tel:"]', 5);

            $result = $this->browser->script("
                const el = document.querySelector('a[href^=\"tel:\"]');
                if (el) return el.textContent.trim();

                const phoneEl = document.querySelector('.phone-number, .revealed-phone');
                return phoneEl ? phoneEl.textContent.trim() : null;
            ");

            return $result[0] ?? null;
        } catch (Throwable) {
            return null;
        }
    }

    /**
     * Extract listing image URLs from the photo gallery.
     */
    private function extractImageUrls(): array
    {
        try {
            $result = $this->browser->script("
                const imgs = document.querySelectorAll('.statement-photos img, .gallery img, .swiper-slide img');
                return Array.from(imgs)
                    .map(img => img.src || img.getAttribute('data-src'))
                    .filter(src => src && src.includes('tnet.ge'))
                    .filter((v, i, a) => a.indexOf(v) === i);
            ");

            return $result[0] ?? [];
        } catch (Throwable) {
            return [];
        }
    }

    /**
     * Extract listing date from the page.
     *
     * myhome.ge shows date as "Today at 3:45 PM" or "24 Aug. 12:54".
     * We store the raw string - pipeline normalizes if needed.
     */
    private function extractListingDate(): ?string
    {
        try {
            $result = $this->browser->script("
                const el = document.querySelector('.statement-date, .listing-date, time');
                if (el) {
                    const datetime = el.getAttribute('datetime');
                    if (datetime) return datetime;
                    return el.textContent.trim();
                }
                return null;
            ");

            return $result[0] ?? null;
        } catch (Throwable) {
            return null;
        }
    }

    /**
     * Extract myhome.ge-specific fields into the extras JSONB column.
     *
     * Stores building status and original GEL price for reference.
     * Pipeline converts GEL → USD — original preserved here.
     */
    private function extractExtras(): array
    {
        $extras = [];

        // Building status: New building / Old building / Under construction
        $status = $this->extractAdditionParameter('Status');
        if ($status !== null) {
            $extras['building_status'] = $status;
        }

        // Type of project: Non-standard, Standard, etc.
        $projectType = $this->extractAdditionParameter('Type of project');
        if ($projectType !== null) {
            $extras['project_type'] = $projectType;
        }

        // Bathrooms count
        $bathrooms = $this->extractAdditionParameter('Bathrooms');
        if ($bathrooms !== null) {
            $extras['bathrooms'] = $bathrooms;
        }

        // Original GEL price stored for reference after USD conversion
        $price = $this->extractPrice();
        if ($price !== null) {
            $extras['original_price_gel'] = $price;
        }

        return $extras;
    }
}
