<?php

declare(strict_types=1);

namespace App\Modules\Scraper\Profiles;

use App\Modules\Scraper\Auth\Contracts\AuthStrategyInterface;
use App\Modules\Scraper\Auth\CookieAuthStrategy;
use App\Modules\Scraper\Auth\FormLoginStrategy;
use App\Modules\Scraper\Base\AbstractScraperProfile;
use App\Modules\Scraper\Browser\ListAmScraper;
use App\Modules\Scraper\Notifications\MailNotifier;
use App\Modules\Scraper\Notifications\TelegramNotifier;
use App\Modules\Scraper\Pipeline\Stages\EnrichDistrictStage;
use App\Modules\Scraper\Pipeline\Stages\FilterCurrencyStage;
use App\Modules\Scraper\Pipeline\Stages\FlagProbableDuplicatesStage;
use Laravel\Dusk\Browser;

/**
 * Shared base for every list.am category profile.
 *
 * Holds everything true of list.am as a site, regardless of category:
 * domain, detail-page selectors, Yerevan districts, login flow, notifiers.
 *
 * Each concrete category profile (ApartmentsForSaleProfile, etc.) only
 * answers what's genuinely different per category: getName(),
 * getIndexUrlPattern(), getListingType(), getPropertyType().
 */
abstract class AbstractListAmProfile extends AbstractScraperProfile
{
    public function getBaseUrl(): string
    {
        return 'https://www.list.am';
    }

    /**
     * Yerevan-only location filter, identical for every category - the IDs
     * are list.am's site-wide district taxonomy, not category-specific.
     * Building the full URL here means no subclass can forget to include it.
     */
    public function getIndexUrlPattern(): string
    {
        return "https://www.list.am/en/category/{$this->getCategoryId()}/{page}?n=1%2C2%2C3%2C4%2C5%2C6%2C7%2C8%2C9%2C10%2C13%2C11%2C12";
    }

    /**
     * The list.am category number for this profile - the only thing that
     * actually changes per category's URL.
     */
    abstract public function getCategoryId(): int;

    /**
     * Same grid layout across every list.am category - card link pattern,
     * price, and location selectors don't change per category.
     */
    public function getIndexSelectors(): array
    {
        return [
            'listing_container' => 'div.gl',
            'card_link' => 'a[href*="/en/item/"]',
            'price' => 'div.p',
            'location' => 'div.at',
        ];
    }

    /**
     * Same detail-page structure across every category. Spec lookups use
     * label-text matching (extractSpecByLabel), so a category missing a
     * given spec (e.g. land has no "Number of Rooms") just returns null -
     * nothing breaks, the field is simply empty for that listing.
     */
    public function getDetailSelectors(): array
    {
        return [
            'price' => 'span[itemprop="price"]',
            'currency' => 'meta[itemprop="priceCurrency"]',
            'location' => '#poi-map-anchor',
            'call_button' => 'a.call',
            'phone_number' => 'span.phone',
            'listing_date' => 'span[itemprop="datePosted"]',
            'images' => 'img[src*="s.list.am/f/"]',
            'area' => 'Floor Area',
            'floor' => 'Floor',
            'floor_total' => 'Floors in the Building',
            'rooms' => 'Number of Rooms',
            'bathrooms' => 'Number of Bathrooms',
            'ceiling_height' => 'Ceiling Height',
            'building_type' => 'Construction Type',
            'new_construction' => 'New Construction',
            'renovation' => 'Renovation',
        ];
    }

    /**
     * Identical minimum requirement across every category - a listing needs
     * an ID, a URL, and a price to be usable for comps. Override in a
     * specific category profile only if that category genuinely differs.
     */
    public function getRequiredFields(): array
    {
        return ['sourceProfileName', 'externalId', 'url', 'price'];
    }

    /**
     * Yerevan districts and their colloquial aliases - city-wide, not
     * category-specific. Every category profile gets this for free.
     */
    public function getDistricts(): array
    {
        return [
            'Kentron',
            'Arabkir',
            'Avan',
            'Davtashen',
            'Erebuni',
            'Malatia-Sebastia',
            'Nor Nork',
            'Nork-Marash',
            'Nubarashen',
            'Shengavit',
            'Ajapnyak',
            'Kanaker-Zeytun',
        ];
    }

    public function getDistrictAliases(): array
    {
        return [
            'the center' => 'Kentron',
            'center' => 'Kentron',
        ];
    }

    /**
     * Adds list.am-wide pipeline steps on top of the base defaults:
     * filter to USD-priced listings, then resolve the Yerevan district.
     */
    public function getPipelineStages(): array
    {
        return [
            ...parent::getPipelineStages(),
            app(FilterCurrencyStage::class, ['acceptedCurrencies' => ['USD']]),
            app(EnrichDistrictStage::class, ['districts' => $this->getDistricts()]),
            app(FlagProbableDuplicatesStage::class),
        ];
    }

    public function getScraperClass(): string
    {
        return ListAmScraper::class;
    }

    public function getNotifiers(): array
    {
        return [TelegramNotifier::class, MailNotifier::class];
    }

    public function getExports(): array
    {
        return ['excel', 'json'];
    }

    /**
     * Per-category page depth and pacing, read from config keyed by this
     * profile's own name - so each of the 7 categories can be tuned
     * independently without touching code. Falls back to the global
     * default if a category-specific config key isn't set yet.
     */
    public function getRequestDelay(): int
    {
        return (int) config(
            "scraper.profile_config.{$this->getName()}.request_delay",
            parent::getRequestDelay()
        );
    }

    public function getMaxPages(): int
    {
        return (int) config(
            "scraper.profile_config.{$this->getName()}.max_pages",
            parent::getMaxPages()
        );
    }

    /**
     * Same list.am login flow for every category - cookie restore first,
     * form login fallback. profileName scopes the cookie file per category
     * automatically, since getName() differs in each concrete subclass.
     */
    public function getAuthStrategy(?Browser $browser = null): ?AuthStrategyInterface
    {
        if ($browser === null) {
            return null;
        }

        $formLogin = app(FormLoginStrategy::class, [
            'browser' => $browser,
            'loginUrl' => 'https://www.list.am/en/user/login',
            'emailSelector' => 'input[name="username"]',
            'passwordSelector' => 'input[name="password"]',
            'submitSelector' => 'button[type="submit"]',
            'successSelector' => 'a.logout',
            'email' => config('scraper.listam_auth.email'),
            'password' => config('scraper.listam_auth.password'),
        ]);

        return app(CookieAuthStrategy::class, [
            'browser' => $browser,
            'profileName' => $this->getName(),
            'baseUrl' => $this->getBaseUrl(),
            'authCheckSelector' => 'a.logout',
            'loginStrategy' => $formLogin,
        ]);
    }
}
