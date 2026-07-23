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
use App\Modules\Scraper\Pipeline\Stages\CleanPhoneStage;
use App\Modules\Scraper\Pipeline\Stages\CleanPriceStage;
use App\Modules\Scraper\Pipeline\Stages\DeduplicateStage;
use App\Modules\Scraper\Pipeline\Stages\DetectPriceChangeStage;
use App\Modules\Scraper\Pipeline\Stages\FlagProbableDuplicatesStage;
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

    public function getRequiredFields(): array
    {
        return ['sourceProfileName', 'externalId', 'url', 'price'];
    }

    /**
     * Pipeline for bina.az listings.
     *
     * NormalizeToEnglishStage runs first - translates Russian district names
     * and building types to English before any other stage reads them.
     * DetectPriceChangeStage and FlagProbableDuplicatesStage run last,
     * same as Yerevan, for cross-poster duplicate detection.
     */
    public function getPipelineStages(): array
    {
        return [
            app(NormalizeStringFieldsStage::class),
            app(NormalizeToEnglishStage::class),
            app(ValidateRequiredFieldsStage::class, ['requiredFields' => $this->getRequiredFields()]),
            app(CleanPriceStage::class),
            app(CleanPhoneStage::class),
            app(CleanAreaStage::class),
            app(CalculatePricePerSqmStage::class),
            app(CleanBuildingTypeStage::class),
            app(DeduplicateStage::class),
            app(DetectPriceChangeStage::class),
            app(FlagProbableDuplicatesStage::class),
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
}
