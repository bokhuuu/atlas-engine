<?php

declare(strict_types=1);

namespace App\Modules\Scraper\Profiles;

use App\Modules\Scraper\Base\AbstractScraperProfile;
use App\Modules\Scraper\Browser\BinazScraper;
use App\Modules\Scraper\Notifications\MailNotifier;
use App\Modules\Scraper\Notifications\TelegramNotifier;
use App\Modules\Scraper\Pipeline\Stages\CalculatePricePerSqmStage;
use App\Modules\Scraper\Pipeline\Stages\CleanAreaStage;
use App\Modules\Scraper\Pipeline\Stages\CleanBuildingTypeStage;
use App\Modules\Scraper\Pipeline\Stages\CleanPriceStage;
use App\Modules\Scraper\Pipeline\Stages\DeduplicateStage;
use App\Modules\Scraper\Pipeline\Stages\DetectPriceChangeStage;
use App\Modules\Scraper\Pipeline\Stages\FlagBinazDuplicatesStage;
use App\Modules\Scraper\Pipeline\Stages\NormalizeStringFieldsStage;
use App\Modules\Scraper\Pipeline\Stages\NormalizeToEnglishStage;
use App\Modules\Scraper\Pipeline\Stages\ValidateRequiredFieldsStage;

/**
 * Shared base for every bina.az category profile.
 *
 * Holds everything true of bina.az as a site, regardless of category:
 * scraper class, pipeline stages, notifiers, exports, and the critical
 * getMaxPages() override that forces ScraperManager to dispatch exactly
 * one CrawlIndexPageJob - the cursor pagination loop runs inside
 * BinazScraper::crawlIndexPage() itself.
 *
 * Each concrete profile only answers what differs per category:
 * getName(), getCategoryId(), getListingType(), getPropertyType().
 */
abstract class AbstractBinaAzProfile extends AbstractScraperProfile
{
    /**
     * The bina.az category ID for this profile.
     * Maps to the categoryId filter in the GraphQL SearchItems query.
     */
    abstract public function getCategoryId(): int;

    public function getBaseUrl(): string
    {
        return 'https://ru.bina.az';
    }

    /**
     * Not used for bina.az - GraphQL replaces paginated URL patterns.
     * Returns empty string to satisfy the interface contract.
     * BinazScraper::crawlIndexPage() handles GraphQL directly.
     */
    public function getIndexUrlPattern(): string
    {
        return '';
    }

    /**
     * Not used for bina.az - GraphQL response provides structured data
     * directly, no HTML index page selectors needed.
     */
    public function getIndexSelectors(): array
    {
        return [];
    }

    /**
     * Not used for bina.az - BinazScraper extracts detail fields
     * via DomCrawler and JSON-LD, not CSS selectors passed from profile.
     */
    public function getDetailSelectors(): array
    {
        return [];
    }

    /**
     * Critical override - forces ScraperManager to dispatch exactly ONE
     * CrawlIndexPageJob for this profile. The actual number of GraphQL
     * cursor fetches is controlled by profile_config.{name}.max_pages
     * and read inside BinazScraper::crawlIndexPage().
     *
     * Without this override, ScraperManager would dispatch N parallel jobs
     * which breaks cursor-based pagination (each page needs the previous
     * page's cursor).
     */
    public function getMaxPages(): int
    {
        return 1;
    }

    /**
     * Optional bina.az location IDs to filter listings by named development.
     * Returns null by default - no location filter applied.
     * Override in concrete profiles to scope scraping to a specific development
     * e.g. Sea Breeze (locationId 380).
     */
    public function getLocationIds(): ?array
    {
        return null;
    }

    public function getRequiredFields(): array
    {
        return ['sourceProfileName', 'externalId', 'url', 'price'];
    }

    /**
     * Pipeline for bina.az listings.
     *
     * NormalizeToEnglishStage runs first - translates Russian district names
     * and building types to English before any other stage reads them.
     * DetectPriceChangeStage and FlagBinazDuplicatesStage run last,
     * same as Yerevan, for cross-poster duplicate detection.
     */
    public function getPipelineStages(): array
    {
        return [
            app(NormalizeStringFieldsStage::class),
            app(NormalizeToEnglishStage::class),
            app(ValidateRequiredFieldsStage::class, ['requiredFields' => $this->getRequiredFields()]),
            app(CleanPriceStage::class),
            // CleanPhoneStage intentionally excluded for bina.az —
            // phone format from API is already human-readable: "(050) 210-31-20"
            // CleanPhoneStage would strip formatting and lose the leading zero.
            app(CleanAreaStage::class),
            app(CalculatePricePerSqmStage::class),
            app(CleanBuildingTypeStage::class),
            app(DeduplicateStage::class),
            app(DetectPriceChangeStage::class),
            app(FlagBinazDuplicatesStage::class),
        ];
    }

    public function getScraperClass(): string
    {
        return BinazScraper::class;
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
     * Controls pacing between GraphQL fetches and detail page requests.
     */
    public function getRequestDelay(): int
    {
        return (int) config(
            "scraper.profile_config.{$this->getName()}.request_delay",
            parent::getRequestDelay()
        );
    }

    /**
     * Extended timeout for bina.az profiles.
     *
     * Unlike Yerevan (one job per page, 60s each), bina.az runs the full
     * cursor pagination loop inside a single CrawlIndexPageJob. A backfill
     * run of 282 fetches at 2s delay needs ~10 minutes just for index fetching,
     * plus detail page time per listing. 1800s (30 min) covers this safely.
     * Override per-profile via scraper.profile_config.{name}.timeout in config.
     */
    public function getQueueConfig(): array
    {
        return [
            'concurrency' => (int) config(
                "scraper.profile_config.{$this->getName()}.concurrency",
                10
            ),
            'retry_times' => (int) config('scraper.default_retry_times'),
            'timeout' => (int) config(
                "scraper.profile_config.{$this->getName()}.timeout",
                1800
            ),
        ];
    }
}
