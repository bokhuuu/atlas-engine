<?php

declare(strict_types=1);

namespace App\Modules\Scraper\Profiles;

use App\Modules\Scraper\Auth\Contracts\AuthStrategyInterface;
use App\Modules\Scraper\Auth\CookieAuthStrategy;
use App\Modules\Scraper\Auth\FormLoginStrategy;
use App\Modules\Scraper\Base\AbstractScraperProfile;
use App\Modules\Scraper\Browser\MyhomeScraper;
use App\Modules\Scraper\Notifications\MailNotifier;
use App\Modules\Scraper\Notifications\TelegramNotifier;
use App\Modules\Scraper\Pipeline\Stages\CalculatePricePerSqmStage;
use App\Modules\Scraper\Pipeline\Stages\CleanAreaStage;
use App\Modules\Scraper\Pipeline\Stages\CleanPhoneStage;
use App\Modules\Scraper\Pipeline\Stages\CleanPriceStage;
use App\Modules\Scraper\Pipeline\Stages\DeduplicateStage;
use App\Modules\Scraper\Pipeline\Stages\DetectPriceChangeStage;
use App\Modules\Scraper\Pipeline\Stages\FlagProbableDuplicatesStage;
use App\Modules\Scraper\Pipeline\Stages\NormalizeStringFieldsStage;
use App\Modules\Scraper\Pipeline\Stages\NormalizeToEnglishMyhomeStage;
use App\Modules\Scraper\Pipeline\Stages\ValidateRequiredFieldsStage;
use Laravel\Dusk\Browser;

/**
 * Shared base for every myhome.ge category profile.
 *
 * Holds everything true of myhome.ge as a site, regardless of category:
 * scraper class, base URL, auth strategy, pipeline stages, notifiers, exports.
 *
 * Each concrete profile only answers what differs per category:
 * getName(), getDealType(), getPropertyTypeSlug(),
 * getDealTypeId(), getRealEstateTypeId(), getListingType(), getPropertyType().
 *
 * URL pattern:
 *   https://www.myhome.ge/en/real-estate/{deal_type}/{property_type_slug}/tbilisi/
 *   ?deal_types={deal_type_id}&real_estate_types={real_estate_type_id}
 *   &cities=1&currency_id=1&CardView=1&page={page}
 *
 * Auth:
 *   CookieAuthStrategy + FormLoginStrategy — identical to list.am.
 *   Cookie file: storage/app/scraper/cookies/{profileName}.json
 *   First run logs in via form, subsequent runs restore from cookies.
 *
 * Phones:
 *   Collected via "Show number" button click — same TNet pattern as myauto.ge.
 *   Requires authenticated session. CleanPhoneStage strips formatting to digits.
 *   Rate limiting expected (~5 reveals per IP per day) — Webshare rotating
 *   residential proxies handle this, same as other markets.
 */
abstract class AbstractMyhomeProfile extends AbstractScraperProfile
{
    /**
     * The deal type URL slug for this profile.
     * Examples: 'sale', 'rent'
     */
    abstract public function getDealType(): string;

    /**
     * The property type URL slug for this profile.
     * Examples: 'apartment', 'private-house', 'land', 'commercial-area'
     */
    abstract public function getPropertyTypeSlug(): string;

    /**
     * The deal_types query parameter value for this profile.
     * sale = 1, rent = 2
     */
    abstract public function getDealTypeId(): int;

    /**
     * The real_estate_types query parameter value for this profile.
     * apartment = 1, private-house = 2, land = 4, commercial-area = 5
     */
    abstract public function getRealEstateTypeId(): int;

    public function getBaseUrl(): string
    {
        return 'https://www.myhome.ge';
    }

    /**
     * Build paginated index URL for this profile.
     *
     * Pattern confirmed from DevTools investigation:
     *   /en/real-estate/{deal_type}/{property_type_slug}/tbilisi/
     *   ?deal_types={id}&real_estate_types={id}&cities=1&currency_id=1&CardView=1&page={page}
     *
     * cities=1 = Tbilisi, currency_id=1 = GEL, CardView=1 = card grid layout.
     */
    public function getIndexUrlPattern(): string
    {
        $dealType = $this->getDealType();
        $propertyTypeSlug = $this->getPropertyTypeSlug();
        $dealTypeId = $this->getDealTypeId();
        $realEstateTypeId = $this->getRealEstateTypeId();

        return "https://www.myhome.ge/en/real-estate/{$dealType}/{$propertyTypeSlug}/tbilisi/"
            ."?deal_types={$dealTypeId}&real_estate_types={$realEstateTypeId}"
            .'&cities=1&currency_id=1&CardView=1&page={page}';
    }

    /**
     * Not used — MyhomeScraper extracts links directly via browser elements.
     * Defined to satisfy the interface contract.
     */
    public function getIndexSelectors(): array
    {
        return [
            'card_link' => 'a[href*="/en/real-estate/"]',
        ];
    }

    /**
     * Not used — MyhomeScraper extracts detail fields via JavaScript.
     * Defined to satisfy the interface contract.
     */
    public function getDetailSelectors(): array
    {
        return [];
    }

    public function getRequiredFields(): array
    {
        return ['sourceProfileName', 'externalId', 'url', 'price'];
    }

    /**
     * Pipeline for myhome.ge listings.
     *
     * NormalizeToEnglishMyhomeStage runs first — maps Georgian district names
     * and sub-districts to canonical English equivalents from config dictionary.
     *
     * CleanPhoneStage included — phone numbers collected via "Show number"
     * button click in authenticated browser session. TNet rate limits phone
     * reveals per IP — Webshare rotating proxies handle this.
     */
    public function getPipelineStages(): array
    {
        return [
            app(NormalizeStringFieldsStage::class),
            app(NormalizeToEnglishMyhomeStage::class),
            app(ValidateRequiredFieldsStage::class, ['requiredFields' => $this->getRequiredFields()]),
            app(CleanPriceStage::class),
            app(CleanPhoneStage::class),
            app(CleanAreaStage::class),
            app(CalculatePricePerSqmStage::class),
            app(DeduplicateStage::class),
            app(DetectPriceChangeStage::class),
            app(FlagProbableDuplicatesStage::class),
        ];
    }

    public function getScraperClass(): string
    {
        return MyhomeScraper::class;
    }

    public function getNotifiers(): array
    {
        return [TelegramNotifier::class, MailNotifier::class];
    }

    public function getExports(): array
    {
        return ['real_estate_report', 'json'];
    }

    /**
     * Per-profile request delay read from config.
     * TNet sites are rate-sensitive — default 3s matches list.am pacing.
     */
    public function getRequestDelay(): int
    {
        return (int) config(
            "scraper.profile_config.{$this->getName()}.request_delay",
            parent::getRequestDelay()
        );
    }

    /**
     * Per-profile max pages read from config.
     */
    public function getMaxPages(): int
    {
        return (int) config(
            "scraper.profile_config.{$this->getName()}.max_pages",
            parent::getMaxPages()
        );
    }

    public function getQueue(): string
    {
        return 'chrome';
    }

    /**
     * myhome.ge uses session cookies (RefreshToken, S) destroyed on Chrome close.
     * Session reuse keeps the authenticated Chrome alive across detail jobs.
     */
    public function supportsSessionReuse(): bool
    {
        return true;
    }

    /**
     * Route Chrome through the rotating residential proxy.
     *
     * auth.tnet.ge blocks headless Chrome from VPS IPs via Cloudflare.
     * Webshare rotating residential proxies pass as real users.
     */
    public function requiresChromProxy(): bool
    {
        return true;
    }

    /**
     * Queue config for myhome.ge profiles.
     *
     * Concurrency must be 1 — each job opens a Chrome browser session.
     * Running multiple sessions simultaneously saturates the Selenium Grid
     * and causes Chrome to hang without failing cleanly.
     */
    public function getQueueConfig(): array
    {
        return [
            'concurrency' => (int) config(
                "scraper.profile_config.{$this->getName()}.concurrency",
                1
            ),
            'retry_times' => (int) config('scraper.default_retry_times'),
            'timeout' => (int) config(
                "scraper.profile_config.{$this->getName()}.timeout",
                config('scraper.default_timeout_s')
            ),
        ];
    }

    /**
     * Auth strategy — CookieAuthStrategy with FormLoginStrategy fallback.
     *
     * Identical pattern to list.am. Cookie file scoped per profile name.
     * Browser submits the login form directly — does not call auth API.
     * Direct API calls to auth.tnet.ge are Cloudflare-blocked from server IPs.
     * Browser form submission via Dusk works fine.
     */
    public function getAuthStrategy(?Browser $browser = null): ?AuthStrategyInterface
    {
        if ($browser === null) {
            return null;
        }

        $formLogin = app(FormLoginStrategy::class, [
            'browser' => $browser,
            'loginUrl' => 'https://auth.tnet.ge/en/user/login/?Continue=https://www.myhome.ge/en/',
            'emailSelector' => 'input[name="Email"]',
            'passwordSelector' => 'input[name="Password"]',
            'submitSelector' => 'button[type="submit"]',
            'successSelector' => '.user-avatar, .user-menu, span.truncate',
            'email' => config('scraper.myhome_auth.email'),
            'password' => config('scraper.myhome_auth.password'),
            'submitText' => 'Sign in',
        ]);

        return app(CookieAuthStrategy::class, [
            'browser' => $browser,
            'profileName' => $this->getName(),
            'baseUrl' => $this->getBaseUrl(),
            'authCheckSelector' => '.user-avatar, .user-menu, span.truncate',
            'loginStrategy' => $formLogin,
        ]);
    }
}
