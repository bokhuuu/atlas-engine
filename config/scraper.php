<?php

declare(strict_types=1);

use App\Modules\Scraper\Exports\CsvExporter;
use App\Modules\Scraper\Exports\ExcelExporter;
use App\Modules\Scraper\Exports\JsonExporter;
use App\Modules\Scraper\Exports\RealEstateMarketReport;
use App\Modules\Scraper\Profiles\ApartmentsForSaleProfile;
use App\Modules\Scraper\Profiles\BinazLandSaleProfile;
use App\Modules\Scraper\Profiles\CommercialForRentProfile;
use App\Modules\Scraper\Profiles\CommercialForSaleProfile;
use App\Modules\Scraper\Profiles\HousesForSaleProfile;
use App\Modules\Scraper\Profiles\LandForSaleProfile;
use App\Modules\Scraper\Profiles\LongTermApartmentRentalsProfile;
use App\Modules\Scraper\Profiles\NewDevelopmentApartmentsProfile;

return [

    /*
    |--------------------------------------------------------------------------
    | ChromeDriver Connection
    |--------------------------------------------------------------------------
    |
    | URL where ChromeDriver is listening. Default port is 9515.
    | Override in .env if running ChromeDriver in Docker or on a remote host.
    |
    */

    'chromedriver_url' => env('CHROMEDRIVER_URL', 'http://localhost:9515'),

    /*
    |--------------------------------------------------------------------------
    | Browser Timeouts
    |--------------------------------------------------------------------------
    |
    | connect_timeout_ms - how long to wait when opening a ChromeDriver session.
    | request_timeout_ms - how long to wait for each WebDriver command to complete.
    | element_wait_timeout_s - how long waitFor() polls before throwing.
    |
    | All values configurable per environment. Production may need higher
    | values; local development can be lower for faster feedback.
    |
    */

    'browser_connect_timeout_ms' => (int) env('BROWSER_CONNECT_TIMEOUT_MS', 30_000),
    'browser_request_timeout_ms' => (int) env('BROWSER_REQUEST_TIMEOUT_MS', 30_000),
    'element_wait_timeout_s' => (int) env('ELEMENT_WAIT_TIMEOUT_S', 10),

    /*
    |--------------------------------------------------------------------------
    | Browser Window
    |--------------------------------------------------------------------------
    |
    | Window size affects which CSS breakpoint the site renders at.
    | Sites sometimes hide elements on mobile widths- use desktop size.
    |
    */

    'browser_window_size' => env('BROWSER_WINDOW_SIZE', '1920,1080'),

    /*
    |--------------------------------------------------------------------------
    | Stealth- User Agent
    |--------------------------------------------------------------------------
    |
    | The browser string sent with every request.
    | Update this in .env when Chrome releases a new major version.
    | Never use the default HeadlessChrome string- it is instantly detectable.
    |
    */

    'user_agent' => env(
        'SCRAPER_USER_AGENT',
        'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36'
    ),

    /*
    |--------------------------------------------------------------------------
    | Retry Logic
    |--------------------------------------------------------------------------
    |
    | When a browser action fails (element not found, timeout, network blip),
    | BaseScraper::retry() will attempt it this many times before giving up.
    | retry_delay_ms is the wait between attempts.
    |
    */

    'retry_attempts' => (int) env('SCRAPER_RETRY_ATTEMPTS', 3),
    'retry_delay_ms' => (int) env('SCRAPER_RETRY_DELAY_MS', 1_000),

    /*
    |--------------------------------------------------------------------------
    | Human-like Pacing
    |--------------------------------------------------------------------------
    |
    | Default delay between page requests in seconds.
    | Each site profile can override this via getRequestDelay().
    | Jitter of ±30% is applied automatically in BaseScraper::pauseForHuman().
    | Increase on sites with aggressive rate limiting.
    |
    */

    'default_request_delay_s' => (int) env('SCRAPER_REQUEST_DELAY', 2),

    /*
    |--------------------------------------------------------------------------
    | Request Delay Jitter
    |--------------------------------------------------------------------------
    |
    | Fraction of the base delay applied as random variance (±).
    | 0.3 means ±30% - a 2s delay becomes anywhere from 1.4s to 2.6s.
    | Increase for more unpredictable timing, decrease for more consistent pacing.
    |
    */

    'request_delay_jitter' => (float) env('SCRAPER_DELAY_JITTER', 0.3),

    /*
    |--------------------------------------------------------------------------
    | Stealth - Browser Languages
    |--------------------------------------------------------------------------
    |
    | Languages reported to sites via navigator.languages JS property.
    | Comma-separated. Match the locale of your target site.
    | list.am: en-US,en - Armenian site but English interface targeted.
    | A Georgian site: ka-GE,ka
    |
    */

    'browser_languages' => env('SCRAPER_BROWSER_LANGUAGES', 'en-US,en'),

    /*
    |--------------------------------------------------------------------------
    | Browser Pool
    |--------------------------------------------------------------------------
    |
    | Maximum number of concurrent browser instances.
    | Each instance uses ~150-200MB RAM- size this to your server.
    | Phase 4.2 (BrowserPool) reads this value.
    |
    */

    'browser_pool_size' => (int) env('BROWSER_POOL_SIZE', 1),

    /*
    |--------------------------------------------------------------------------
    | Queue Settings
    |--------------------------------------------------------------------------
    |
    | Default concurrency, retry attempts, and job timeout for queue jobs.
    | Each site profile can override these via getQueueConfig().
    | Adjust timeout for slow sites that need more time per page.
    |
    */

    'default_concurrency' => (int) env('SCRAPER_CONCURRENCY', 3),
    'default_retry_times' => (int) env('SCRAPER_RETRY_TIMES', 3),
    'default_timeout_s' => (int) env('SCRAPER_TIMEOUT_S', 60),

    /*
    |--------------------------------------------------------------------------
    | Scraper Profiles
    |--------------------------------------------------------------------------
    |
    | Maps profile name strings to their class implementations.
    | Add a new entry here to register a new scraping target.
    | No other files need to change.
    |
    | 'name' => ProfileClass::class
    |
    */

    'profiles' => [
        'listam_apartments_sale' => ApartmentsForSaleProfile::class,
        'listam_apartments_rent' => LongTermApartmentRentalsProfile::class,
        'listam_houses_sale' => HousesForSaleProfile::class,
        'listam_commercial_sale' => CommercialForSaleProfile::class,
        'listam_commercial_rent' => CommercialForRentProfile::class,
        'listam_land_sale' => LandForSaleProfile::class,
        'listam_new_developments_sale' => NewDevelopmentApartmentsProfile::class,

        'binaz_land_sale' => BinazLandSaleProfile::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Market Registry
    |--------------------------------------------------------------------------
    |
    | Defines all markets Atlas supports. Each market has a slug (used in URLs),
    | a label, country, flag emoji, currency, and the source_profile_name
    | prefixes that belong to it. One market can have multiple prefixes
    | (e.g. two data sources for the same city).
    |
    | 'global' is a reserved slug - do not use it as a market key.
    |
    */
    'markets' => [
        'yerevan' => [
            'label' => 'Yerevan',
            'country' => 'Armenia',
            'flag' => '🇦🇲',
            'source_prefixes' => ['listam'],
            'currency' => 'USD',
        ],
        'baku' => [
            'label' => 'Baku',
            'country' => 'Azerbaijan',
            'flag' => '🇦🇿',
            'source_prefixes' => ['binaz'],
            'currency' => 'AZN',
        ],
        'tbilisi' => [
            'label' => 'Tbilisi',
            'country' => 'Georgia',
            'flag' => '🇬🇪',
            'source_prefixes' => ['myhome', 'ss'],
            'currency' => 'USD',
        ],
    ],

    'default_market' => env('DEFAULT_MARKET', 'yerevan'),

    /*
    |--------------------------------------------------------------------------
    | Job Middleware
    |--------------------------------------------------------------------------
    |
    | rate_limit_window_s  - rolling window for concurrent job throttling.
    | rate_limit_release_s - how long to wait before retrying a throttled job.
    | retry_base_delay_s   - multiplied by attempt count for progressive backoff.
    |                        attempt 1 = 30s, attempt 2 = 60s, attempt 3 = 90s.
    |
    */

    'rate_limit_window_s' => (int) env('SCRAPER_RATE_LIMIT_WINDOW', 1),
    'rate_limit_release_s' => (int) env('SCRAPER_RATE_LIMIT_RELEASE', 5),
    'retry_base_delay_s' => (int) env('SCRAPER_RETRY_BASE_DELAY', 30),

    /*
    |--------------------------------------------------------------------------
    | Default Max Pages
    |--------------------------------------------------------------------------
    |
    | Maximum pages to scrape per run by default.
    | Set to 2 locally for fast testing, 50+ in production for full runs.
    |
    */
    'default_max_pages' => (int) env('SCRAPER_MAX_PAGES', 50),

    /*
    |--------------------------------------------------------------------------
    | Absolute Max Pages (safety ceiling)
    |--------------------------------------------------------------------------
    |
    | Hard upper limit on pages for any single scrape run, regardless of how
    | pages were calculated (manual --pages, or converted from --listings).
    | Protects against accidental huge runs (e.g. a typo'd listing count).
    | This is a ceiling, not a target - normal runs should rarely approach it.
    |
    */
    'absolute_max_pages' => (int) env('SCRAPER_ABSOLUTE_MAX_PAGES', 20),

    /*
    |--------------------------------------------------------------------------
    | Index Page Sort Order
    |--------------------------------------------------------------------------
    |
    | Controls which order list.am returns index-page listings in, via the
    | srt= query parameter. list.am's own values (confirmed against the
    | real site's sort dropdown, not guessed):
    |   0 = By default, 4 = Sort by relevancy, 1 = Cheapest first,
    |   2 = Expensive first, 3 = By date (newest first).
    |
    */
    'index_sort' => (int) env('SCRAPER_INDEX_SORT', 3),

    /*
    |--------------------------------------------------------------------------
    | Per-Profile Configuration
    |--------------------------------------------------------------------------
    |
    | Site-specific settings and credentials.
    | Add a new entry here for each scraping target.
    | Credentials are never hardcoded - always read from .env.
    |
    */

    'profile_config' => [
        'listam_apartments_sale' => [
            'request_delay' => env('LISTAM_APARTMENTS_SALE_REQUEST_DELAY', 3),
            'max_pages' => env('LISTAM_APARTMENTS_SALE_MAX_PAGES', 1),
            'listings_per_page' => env('LISTAM_APARTMENTS_SALE_LISTINGS_PER_PAGE', 97),
        ],
        'listam_apartments_rent' => [
            'request_delay' => env('LISTAM_APARTMENTS_RENT_REQUEST_DELAY', 3),
            'max_pages' => env('LISTAM_APARTMENTS_RENT_MAX_PAGES', 1),
            'listings_per_page' => env('LISTAM_APARTMENTS_RENT_LISTINGS_PER_PAGE', 38),
        ],
        'listam_houses_sale' => [
            'request_delay' => env('LISTAM_HOUSES_SALE_REQUEST_DELAY', 3),
            'max_pages' => env('LISTAM_HOUSES_SALE_MAX_PAGES', 1),
            'listings_per_page' => env('LISTAM_HOUSES_SALE_LISTINGS_PER_PAGE', 95),
        ],
        'listam_commercial_sale' => [
            'request_delay' => env('LISTAM_COMMERCIAL_SALE_REQUEST_DELAY', 3),
            'max_pages' => env('LISTAM_COMMERCIAL_SALE_MAX_PAGES', 1),
            'listings_per_page' => env('LISTAM_COMMERCIAL_SALE_LISTINGS_PER_PAGE', 91),
        ],
        'listam_commercial_rent' => [
            'request_delay' => env('LISTAM_COMMERCIAL_RENT_REQUEST_DELAY', 3),
            'max_pages' => env('LISTAM_COMMERCIAL_RENT_MAX_PAGES', 1),
            'listings_per_page' => env('LISTAM_COMMERCIAL_RENT_LISTINGS_PER_PAGE', 26),
        ],
        'listam_land_sale' => [
            'request_delay' => env('LISTAM_LAND_SALE_REQUEST_DELAY', 3),
            'max_pages' => env('LISTAM_LAND_SALE_MAX_PAGES', 1),
            'listings_per_page' => env('LISTAM_LAND_SALE_LISTINGS_PER_PAGE', 94),
        ],
        'listam_new_developments_sale' => [
            'request_delay' => env('LISTAM_NEW_DEVELOPMENTS_SALE_REQUEST_DELAY', 3),
            'max_pages' => env('LISTAM_NEW_DEVELOPMENTS_SALE_MAX_PAGES', 1),
            'listings_per_page' => env('LISTAM_NEW_DEVELOPMENTS_SALE_LISTINGS_PER_PAGE', 39),
        ],

        'binaz_land_sale' => [
            'request_delay' => env('BINAZ_LAND_SALE_REQUEST_DELAY', 2),
            'max_pages' => env('BINAZ_LAND_SALE_MAX_PAGES', 5),
            'timeout' => env('BINAZ_LAND_SALE_TIMEOUT', 1800),
            'concurrency' => env('BINAZ_LAND_SALE_CONCURRENCY', 10),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | list.am Login Credentials
    |--------------------------------------------------------------------------
    |
    | Shared across all 7 list.am category profiles - one login, one account,
    | not duplicated per category. Used by AbstractListAmProfile::getAuthStrategy()
    | to authenticate the cookie/form-login flow described in ATLAS.md Section 7.
    | Never hardcoded - always read from .env.
    |
    */
    'listam_auth' => [
        'email' => env('LISTAM_EMAIL'),
        'password' => env('LISTAM_PASSWORD'),
    ],

    /*
    |--------------------------------------------------------------------------
    | bina.az API Configuration
    |--------------------------------------------------------------------------
    |
    | Settings for the bina.az GraphQL API used by all binaz_* profiles.
    |
    | city_id              - Baku is city 1 in bina.az's taxonomy. Only Baku
    |                        is scraped - all profiles filter by this ID.
    | persisted_query_hash - SHA256 hash identifying the SearchItems GraphQL
    |                        query on bina.az's frontend. This hash is tied
    |                        to their frontend build and may rotate when they
    |                        deploy a new version. If GraphQL calls start
    |                        returning errors, update this value from DevTools
    |                        Network tab → SearchItems → Payload → extensions.
    |                        Store the new hash in .env as BINAZ_PERSISTED_QUERY_HASH.
    |
    */
    'binaz' => [
        'city_id' => env('BINAZ_CITY_ID', '1'),
        'persisted_query_hash' => env(
            'BINAZ_PERSISTED_QUERY_HASH',
            '0411196b53414c016d897220337db3bd777dee1a71fdfafa4d98ce3502b38754'
        ),
    ],

    /*
    |--------------------------------------------------------------------------
    | Batch Name Pattern
    |--------------------------------------------------------------------------
    |
    | Pattern used to name Laravel Bus batches for identification in logs
    | and Laravel Horizon. Supports two placeholders:
    |   {source} - the profile name (e.g. listam)
    |   {id}     - the scraper run ID
    |
    */
    'batch_name_pattern' => env('SCRAPER_BATCH_NAME_PATTERN', '{source}-run-{id}'),

    /*
    |--------------------------------------------------------------------------
    | Export Path
    |--------------------------------------------------------------------------
    |
    | Directory where exported files are stored, relative to storage/app/.
    | Override via SCRAPER_EXPORT_PATH in .env.
    |
    */
    'export_path' => env('SCRAPER_EXPORT_PATH', 'exports'),

    /*
    |--------------------------------------------------------------------------
    | Exporters Map
    |--------------------------------------------------------------------------
    |
    | Maps format keys to their exporter class implementations.
    | Profiles declare which formats to run via getExports().
    | Add a new entry here to register a new export format globally.
    | No other files need to change.
    |
    | 'format_key' => ExporterClass::class
    |
    */
    'exporters' => [
        'excel' => ExcelExporter::class,
        'csv' => CsvExporter::class,
        'json' => JsonExporter::class,
        'real_estate_report' => RealEstateMarketReport::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Proxy Configuration
    |--------------------------------------------------------------------------
    |
    | When enabled, ProxyResolver rotates through the proxy list on each
    | request so the target site never sees the same IP twice in a row.
    |
    | proxy_enabled  - master switch, set false for local development.
    | proxies        - comma-separated list of proxy addresses in .env.
    | proxy_strategy - 'random' or 'round_robin'.
    | proxy_username - credentials for authenticated proxy services.
    | proxy_password - credentials for authenticated proxy services.
    |
    */
    'proxy_enabled' => (bool) env('SCRAPER_PROXY_ENABLED', false),

    'proxies' => array_filter(explode(',', env('SCRAPER_PROXIES', ''))),

    'proxy_strategy' => env('SCRAPER_PROXY_STRATEGY', 'round_robin'),

    'proxy_username' => env('SCRAPER_PROXY_USERNAME'),

    'proxy_password' => env('SCRAPER_PROXY_PASSWORD'),

    /*
    |--------------------------------------------------------------------------
    | API Rate Limiting
    |--------------------------------------------------------------------------
    |
    | api_rate_limit         - max requests per minute across all clients globally.
    | api_per_token_limit    - max requests per minute per Sanctum token.
    |
    */
    'api_rate_limit' => (int) env('API_RATE_LIMIT', 60),
    'api_per_token_limit' => (int) env('API_PER_TOKEN_LIMIT', 30),

    /*
    |--------------------------------------------------------------------------
    | Data Drift Detection
    |--------------------------------------------------------------------------
    |
    | drift_min_listings  - minimum listings expected per run.
    |                       if a run returns fewer, ScrapeFailed event fires.
    | drift_max_null_rate - maximum acceptable null rate for key fields (0-1).
    |                       0.5 means if >50% of listings have null district,
    |                       ScrapeFailed event fires.
    |
    */
    'drift_min_listings' => (int) env('SCRAPER_DRIFT_MIN_LISTINGS', 10),
    'drift_max_null_rate' => (float) env('SCRAPER_DRIFT_MAX_NULL_RATE', 0.5),

    /*
    |--------------------------------------------------------------------------
    | Cross-Poster Duplicate Detection
    |--------------------------------------------------------------------------
    |
    | Flags (does not delete) probable duplicate listings - the same physical
    | property posted by different parties under different external_ids.
    | District/floor/rooms must match exactly; area and price are checked
    | within tolerance. Thresholds confirmed against real scraped data -
    | see ATLAS.md for the actual analysis, not picked arbitrarily.
    |
    */
    'duplicate_detection' => [
        'enabled' => env('DUPLICATE_DETECTION_ENABLED', true),
        'area_tolerance_percent' => env('DUPLICATE_AREA_TOLERANCE_PERCENT', 5.0),
        'price_tolerance_percent' => env('DUPLICATE_PRICE_TOLERANCE_PERCENT', 10.0),
        'area_tolerance_percent_strict' => env('DUPLICATE_AREA_TOLERANCE_PERCENT_STRICT', 3.0),
        'price_tolerance_percent_strict' => env('DUPLICATE_PRICE_TOLERANCE_PERCENT_STRICT', 6.0),
    ],

    /*
    |--------------------------------------------------------------------------
    | Yield Estimate Minimum Listings
    |--------------------------------------------------------------------------
    | Minimum number of listings required on both the sale and rent side
    | of a district before an estimated rental yield is calculated.
    | Districts below this threshold show - instead of a potentially
    | unreliable figure based on too few data points.
    */
    'yield_min_listings' => env('SCRAPER_YIELD_MIN_LISTINGS', 5),

    /*
    |--------------------------------------------------------------------------
    | Price Change Detection Window
    |--------------------------------------------------------------------------
    | Number of days to look back when counting price reductions per district
    | on the Market Stats page. Listings with a price drop detected within
    | this window are counted as "recent reductions".
    | Override via .env to tune per environment without touching code.
    */
    'price_change_window_days' => env('SCRAPER_PRICE_CHANGE_WINDOW_DAYS', 7),

    /*
    |--------------------------------------------------------------------------
    | Listing Lifecycle Check
    |--------------------------------------------------------------------------
    |
    | listing_check_daily_percent - percentage of active listings to check
    |                               per daily run via HTTP HEAD requests.
    |                               5% = full DB cycles in ~20 days.
    | listing_check_delay_ms      - delay between HEAD requests in milliseconds.
    |                               Keeps request rate human-like.
    |
    */
    'listing_check_daily_percent' => (float) env('LISTING_CHECK_DAILY_PERCENT', 5.0),
    'listing_check_delay_ms' => (int) env('LISTING_CHECK_DELAY_MS', 2000),

    /*
    |--------------------------------------------------------------------------
    | Screenshot Capture Chunk
    |--------------------------------------------------------------------------
    |
    | Maximum number of listings to screenshot per nightly run.
    | Limits Chrome session count and run duration.
    | At ~20 seconds per listing, 200 = ~67 minutes per run.
    |
    */
    'screenshot_daily_chunk' => (int) env('SCREENSHOT_DAILY_CHUNK', 200),

    /*
    |--------------------------------------------------------------------------
    | Stale Listing Threshold
    |--------------------------------------------------------------------------
    |
    | Number of days after which a listing is considered stale.
    | Stale listings are still active on the site but have been
    | listed for a long time - potentially overpriced or slow-moving.
    | Shown as "Stale (90+ days)" in exports and admin panel.
    |
    */
    'stale_listing_days' => (int) env('SCRAPER_STALE_LISTING_DAYS', 90),

    /*
    |--------------------------------------------------------------------------
    | Screenshot Capture
    |--------------------------------------------------------------------------
    |
    | When enabled, CrawlDetailPageJob captures a full-page screenshot of
    | each new listing detail page and uploads it to Backblaze B2.
    | Only new listings are screenshotted - never re-captured on re-scrape.
    | Set false locally to avoid B2 uploads during development.
    |
    */
    'screenshots_enabled' => (bool) env('SCRAPER_SCREENSHOTS_ENABLED', false),
    'screenshot_access_key' => env('SCREENSHOT_ACCESS_KEY'),

    /*
    |--------------------------------------------------------------------------
    | Screenshots Public
    |--------------------------------------------------------------------------
    |
    | When true, screenshot URLs are served as permanent public B2 URLs.
    | Requires the b2_screenshots bucket to be set to public in Backblaze.
    | When false, signed URLs are generated per-request (expire after 30 days).
    | Switch to true after making the bucket public to enable permanent links
    | in Data Hub, Excel exports, and AI responses.
    |
    */
    'screenshots_public' => (bool) env('SCREENSHOTS_PUBLIC', false),

    /*
    |--------------------------------------------------------------------------
    | Exchange Rate Fallbacks
    |--------------------------------------------------------------------------
    |
    | Approximate fallback rates used when the settings table has no stored
    | exchange rate yet (e.g. first boot before atlas:fetch-exchange-rates runs).
    | These are not used for production calculations - the daily job overwrites
    | them in settings immediately. Values are USD per 1 unit of currency.
    | Update these in .env if rates drift significantly before the job runs.
    |
    */
    'exchange_rates' => [
        'amd_usd' => (float) env('FALLBACK_AMD_USD_RATE', 0.00256),
        'azn_usd' => (float) env('FALLBACK_AZN_USD_RATE', 0.588),
        'gel_usd' => (float) env('FALLBACK_GEL_USD_RATE', 0.357),
        'eur_usd' => (float) env('FALLBACK_EUR_USD_RATE', 0.876),
    ],

    /*
    |--------------------------------------------------------------------------
    | Russian → English Normalization Dictionaries
    |--------------------------------------------------------------------------
    |
    | Used by NormalizeToEnglishStage to translate structured fields from
    | Russian-language sources (bina.az) into English at pipeline time.
    | Zero API calls - instant lookup against these config arrays.
    |
    | Districts map settlements → their parent administrative district.
    | Baku has 12 official districts - all settlements normalize to one of them.
    | Add unknown values here after inspecting real scraped data.
    |
    */
    'normalization' => [
        'baku' => [
            'districts' => [
                // Absheron district
                'Абшеронский р.' => 'Absheron',
                'Абшеронcкий р.' => 'Absheron',
                'Абшеронcкий  р.' => 'Absheron',
                'Абшеронский район' => 'Absheron',
                'Абшеронский' => 'Absheron',
                'Атъялы' => 'Absheron',
                'Ашагы Гюздек' => 'Absheron',
                'Геокмалы' => 'Absheron',
                'Горадил' => 'Absheron',
                'Гюздек' => 'Absheron',
                'Джейранбатан' => 'Absheron',
                'Дигях' => 'Absheron',
                'Ени Джорат' => 'Absheron',
                'Зугульба' => 'Absheron',
                'Кобу' => 'Absheron',
                'Мамедли' => 'Absheron',
                'Масазыр' => 'Absheron',
                'Мехтиабад' => 'Absheron',
                'Новханы' => 'Absheron',
                'Перекишкюль' => 'Absheron',
                'Сарай' => 'Absheron',
                'Старый Джорат' => 'Absheron',
                'Фатмаи' => 'Absheron',
                'Чичек' => 'Absheron',

                // Binagadi district
                'Бинагадинский р.' => 'Binagadi',
                'Бинагадинский район' => 'Binagadi',
                'Бинагадинский' => 'Binagadi',
                '2-ая Алатава' => 'Binagadi',
                '28 мая' => 'Binagadi',
                '6-ой мкр' => 'Binagadi',
                '7-ой мкр' => 'Binagadi',
                '8-й мкр' => 'Binagadi',
                '9-й мкр' => 'Binagadi',
                'Биладжары' => 'Binagadi',
                'Бинагади' => 'Binagadi',
                'М. Расулзаде' => 'Binagadi',
                'Сулутепе' => 'Binagadi',
                'Ходжасан' => 'Binagadi',
                'Хутор' => 'Binagadi',
                'Бакмил' => 'Binagadi',

                // Garadagh district
                'Гарадагский р.' => 'Garadagh',
                'Гарадагский район' => 'Garadagh',
                'Гарадагский' => 'Garadagh',
                'Аляты' => 'Garadagh',
                'Гобустан' => 'Garadagh',
                'Кызыл-Даш' => 'Garadagh',
                'Локбатан' => 'Garadagh',
                'Мушфигабад' => 'Garadagh',
                'Пута' => 'Garadagh',
                'Сангачал' => 'Garadagh',
                'Сахиль' => 'Garadagh',
                'Шубаны' => 'Garadagh',
                'Дернегюль' => 'Garadagh',

                // Narimanov district
                'Наримановский р.' => 'Narimanov',
                'Наримановский район' => 'Narimanov',
                'Наримановский' => 'Narimanov',
                'Бёюк-Шор' => 'Narimanov',
                'Нариман Нариманов' => 'Narimanov',

                // Nasimi district
                'Насиминский р.' => 'Nasimi',
                'Насиминский район' => 'Nasimi',
                'Насиминский' => 'Nasimi',
                '1-й мкр' => 'Nasimi',
                '2-й мкр' => 'Nasimi',
                '3-й мкр' => 'Nasimi',
                '4-й мкр' => 'Nasimi',
                '5-й мкр' => 'Nasimi',
                'Кубинка' => 'Nasimi',

                // Nizami district
                'Низаминский р.' => 'Nizami',
                'Низаминский район' => 'Nizami',
                'Низаминский' => 'Nizami',
                '8-й километр' => 'Nizami',
                'Кешля' => 'Nizami',

                // Pirallahi district
                'Пираллахинский р.' => 'Pirallahi',
                'Пираллахинский район' => 'Pirallahi',
                'Пираллахинский' => 'Pirallahi',
                'Пираллахи' => 'Pirallahi',

                // Sabail district
                'Сабаильский р.' => 'Sabail',
                'Сабаильский район' => 'Sabail',
                'Сабаильский' => 'Sabail',
                '20-й участок' => 'Sabail',
                'Бадамдар' => 'Sabail',
                'Баилова' => 'Sabail',
                'Биби-Эйбат' => 'Sabail',
                'Шихов' => 'Sabail',

                // Sabunchu district
                'Сабунчинский р.' => 'Sabunchu',
                'Сабунчинский район' => 'Sabunchu',
                'Бакиханова' => 'Sabunchu',
                'Балаханы' => 'Sabunchu',
                'Бильгях' => 'Sabunchu',
                'Вишнёвка' => 'Sabunchu',
                'Ени Балаханы' => 'Sabunchu',
                'Ени Романы' => 'Sabunchu',
                'Забрат' => 'Sabunchu',
                'Кюрдаханы' => 'Sabunchu',
                'Маштага' => 'Sabunchu',
                'Маштаги' => 'Sabunchu',
                'Нардаран' => 'Sabunchu',
                'Пиршаги' => 'Sabunchu',
                'Рамана' => 'Sabunchu',
                'Сабунчи' => 'Sabunchu',
                'Сабунчу' => 'Sabunchu',
                'Савалан' => 'Sabunchu',
                'Sea Breeze' => 'Sabunchu',

                // Surakhani district
                'Сураханский р.' => 'Surakhani',
                'Сураханский район' => 'Surakhani',
                'Сураханский' => 'Surakhani',
                'Амирджаны' => 'Surakhani',
                'Бахар' => 'Surakhani',
                'Бюльбюля' => 'Surakhani',
                'Говсан' => 'Surakhani',
                'Гюнешли' => 'Surakhani',
                'Деде Горгуд' => 'Surakhani',
                'Ени Гюнешли' => 'Surakhani',
                'Ени Сураханы' => 'Surakhani',
                'Зых' => 'Surakhani',
                'Карачухур' => 'Surakhani',
                'Гарачухур' => 'Surakhani',
                'Массив А' => 'Surakhani',
                'Массив Б' => 'Surakhani',
                'Массив В' => 'Surakhani',
                'Массив Г' => 'Surakhani',
                'Массив Д' => 'Surakhani',
                'Сураханы' => 'Surakhani',
                'Шерг' => 'Surakhani',

                // Khazar district
                'Хазарский р.' => 'Khazar',
                'Хазарский район' => 'Khazar',
                'Бина' => 'Khazar',
                'Бузовна' => 'Khazar',
                'Гала' => 'Khazar',
                'Гюргян' => 'Khazar',
                'Дюбенди' => 'Khazar',
                'Зире' => 'Khazar',
                'Мардакан' => 'Khazar',
                'Северный Грэс' => 'Khazar',
                'Тюркан' => 'Khazar',
                'Шаган' => 'Khazar',
                'Шувеляны' => 'Khazar',
                'Бинэ' => 'Khazar',

                // Khatai district
                'Хатаинский р.' => 'Khatai',
                'Хатаинский район' => 'Khatai',
                'Хатаинский' => 'Khatai',
                'Аг шехер' => 'Khatai',
                'Ази Асланова' => 'Khatai',
                'Ази Асланов' => 'Khatai',
                'Ахмедлы' => 'Khatai',
                'НЗС' => 'Khatai',
                'Старые Гюнешли' => 'Khatai',
                'Кара Караев' => 'Khatai',

                // Yasamal district
                'Ясамальский р.' => 'Yasamal',
                'Ясамальский район' => 'Yasamal',
                'Ени Ясамал' => 'Yasamal',
                'Ясамал' => 'Yasamal',

                // Metro stations → their district
                'Насими' => 'Nasimi',
                'Низами' => 'Nizami',
                'Сахил' => 'Sabail',
                '8 ноября' => 'Narimanov',
                'Иншаатчылар' => 'Narimanov',
                'Халглар Достлугу' => 'Surakhani',
                'Шах Исмаил Хатаи' => 'Khatai',
                'Азадлыг проспекти' => 'Binagadi',
                'Элмляр Академиясы' => 'Narimanov',
                '20 января' => 'Nasimi',
                'Ичеришехер' => 'Sabail',
                'Гянджлик' => 'Narimanov',
                'Мемар Аджеми' => 'Narimanov',
                'Улдуз' => 'Narimanov',
                'Нефтчиляр' => 'Surakhani',
                'Автовокзал' => 'Binagadi',
            ],

            'building_types' => [
                'Монолит' => 'Monolith',
                'Кирпич' => 'Brick',
                'Панель' => 'Panel',
                'Бизнес центр' => 'Business Center',
                'Жилое здание' => 'Residential Building',
                'Административное здание' => 'Administrative Building',
                'Производственное здание' => 'Industrial Building',
                'Частный дом' => 'Private House',
                'Новостройка' => 'New Building',
            ],

            'conditions' => [
                'С ремонтом' => 'Renovated',
                'Без ремонта' => 'Needs renovation',
                'Евроремонт' => 'Euro renovation',
                'Хорошее' => 'Good',
                'Среднее' => 'Average',
                'Требует ремонта' => 'Needs renovation',
            ],
        ],
    ],
];
