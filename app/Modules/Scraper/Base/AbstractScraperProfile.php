<?php

declare(strict_types=1);

namespace App\Modules\Scraper\Base;

use App\Modules\Scraper\Auth\Contracts\AuthStrategyInterface;
use App\Modules\Scraper\Contracts\ScraperProfileInterface;
use App\Modules\Scraper\Notifications\TelegramNotifier;
use App\Modules\Scraper\Pipeline\Stages\CalculatePricePerSqmStage;
use App\Modules\Scraper\Pipeline\Stages\CleanAreaStage;
use App\Modules\Scraper\Pipeline\Stages\CleanBuildingTypeStage;
use App\Modules\Scraper\Pipeline\Stages\CleanPhoneStage;
use App\Modules\Scraper\Pipeline\Stages\CleanPriceStage;
use App\Modules\Scraper\Pipeline\Stages\DeduplicateStage;
use App\Modules\Scraper\Pipeline\Stages\NormalizeStringFieldsStage;
use App\Modules\Scraper\Pipeline\Stages\ValidateRequiredFieldsStage;
use Laravel\Dusk\Browser;

/**
 * Default implementations for methods common across all scraper profiles.
 * Concrete profiles extend this and override only what differs.
 *
 * Abstract methods must be implemented by every profile - they contain
 * site-specific data that cannot have a sensible default.
 */
abstract class AbstractScraperProfile implements ScraperProfileInterface
{
    /**
     * Fields required for a listing to be processable.
     * Passed to ValidateRequiredFieldsStage at pipeline construction.
     * Different sites expose different data - price may not always be available.
     */
    abstract public function getRequiredFields(): array;

    /**
     * Known districts for the target city.
     * Override in profiles that use EnrichDistrictStage.
     * Returns empty array by default - most sites do not need district enrichment.
     */
    public function getDistricts(): array
    {
        return [];
    }

    /**
     * Colloquial district name aliases for the target city.
     * Override in profiles where the site uses non-standard district names.
     * Returns empty array by default - most sites use official names only.
     */
    public function getDistrictAliases(): array
    {
        return [];
    }

    /**
     * Most sites are public - no auth required by default.
     * Override in profiles that require login (e.g. to reveal phone numbers).
     * Browser is injected at runtime by CrawlDetailPageJob after scraper boots.
     */
    public function getAuthStrategy(?Browser $browser = null): ?AuthStrategyInterface
    {
        return null;
    }

    /**
     * Default delay between requests in seconds, read from config.
     * Override in site profiles that need slower or faster pacing.
     */
    public function getRequestDelay(): int
    {
        return (int) config('scraper.default_request_delay_s');
    }

    /**
     * The queue this profile's jobs should be dispatched to.
     * Chrome-based profiles override this to return 'chrome'.
     */
    public function getQueue(): string
    {
        return 'default';
    }

    /**
     * HTTP-based profiles never reuse sessions — no browser involved.
     */
    public function supportsSessionReuse(): bool
    {
        return false;
    }

    /**
     * HTTP-based profiles never use Chrome proxy.
     */
    public function requiresChromProxy(): bool
    {
        return false;
    }

    /**
     * Maximum pages to scrape per run.
     * Set SCRAPER_MAX_PAGES=2 locally for fast testing.
     */
    public function getMaxPages(): int
    {
        return (int) config('scraper.default_max_pages');
    }

    /**
     * Standard desktop browser configuration.
     * 1920x1080 ensures sites render their full desktop layout.
     * User agent mimics real Chrome to avoid bot detection.
     */
    public function getBrowserConfig(): array
    {
        return [
            'headless' => true,
            'window_size' => config('scraper.browser_window_size'),
            'user_agent' => config('scraper.user_agent'),
        ];
    }

    /**
     * Excel for client deliverables, JSON for API/integration use.
     * Override to change export formats per project.
     */
    public function getExports(): array
    {
        return ['excel', 'json'];
    }

    /**
     * Default pipeline handles normalization, validation,
     * price/area cleaning and deduplication.
     * Override to add site-specific stages (e.g. CleanMileageStage for MyAuto).
     */
    public function getPipelineStages(): array
    {
        return [
            app(NormalizeStringFieldsStage::class),
            app(ValidateRequiredFieldsStage::class, ['requiredFields' => $this->getRequiredFields()]),
            app(CleanPriceStage::class),
            app(CleanPhoneStage::class),
            app(CleanAreaStage::class),
            app(CalculatePricePerSqmStage::class),
            app(CleanBuildingTypeStage::class),
            app(DeduplicateStage::class),

        ];
    }

    /**
     * Telegram is the default notification channel.
     * Override to add Slack, disable notifications, or use multiple channels.
     * Return an empty array to silence all notifications for a profile.
     */
    public function getNotifiers(): array
    {
        return [TelegramNotifier::class];
    }

    /**
     * Default queue settings read from config.
     * Override for slow or rate-limited sites that need different concurrency or timeouts.
     */
    public function getQueueConfig(): array
    {
        return [
            'concurrency' => (int) config('scraper.default_concurrency'),
            'retry_times' => (int) config('scraper.default_retry_times'),
            'timeout' => (int) config('scraper.default_timeout_s'),
        ];
    }

    /**
     * Build a paginated index URL for the given page number.
     *
     * Replaces the {page} placeholder in getIndexUrlPattern().
     */
    public function buildIndexUrl(int $page): string
    {
        return str_replace('{page}', (string) $page, $this->getIndexUrlPattern());
    }
}
