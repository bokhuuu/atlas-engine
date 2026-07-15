<?php

declare(strict_types=1);

namespace App\Providers;

use App\Events\AiCallCompleted;
use App\Listeners\CostAlertListener;
use App\Listeners\TrackLastLogin;
use App\Listeners\UsageTrackingListener;
use App\Modules\AI\Jobs\TranslateNewListings;
use App\Modules\AI\Models\AiUsageLog;
use App\Modules\Scraper\Events\ScrapeCompleted;
use App\Modules\Scraper\Events\ScrapeFailed;
use App\Modules\Scraper\Listeners\SendScrapeCompletedNotification;
use App\Modules\Scraper\Listeners\SendScrapeFailedNotification;
use App\Modules\Scraper\Listeners\TriggerScrapeExport;
use App\Observers\AiUsageLogObserver;
use Carbon\CarbonImmutable;
use Illuminate\Auth\Events\Login;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

/**
 * Bootstraps global application defaults.
 *
 * Configures immutable dates, blocks destructive database commands
 * in production, and enforces stricter password rules in production.
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (app()->isProduction()) {
            URL::forceScheme('https');
            request()->server->set('HTTPS', 'on');
        }

        $this->configureDefaults();

        Model::preventLazyLoading(! app()->isProduction());

        Event::listen(Login::class, TrackLastLogin::class);

        Event::listen(ScrapeCompleted::class, SendScrapeCompletedNotification::class);
        Event::listen(ScrapeCompleted::class, TriggerScrapeExport::class);

        Event::listen(ScrapeCompleted::class, function (ScrapeCompleted $event) {
            TranslateNewListings::dispatch($event->source);
        });

        Event::listen(ScrapeFailed::class, SendScrapeFailedNotification::class);

        RateLimiter::for('api.global', fn () => Limit::perMinute(config('scraper.api_rate_limit', 60)));
        RateLimiter::for('api.per_token', fn (Request $request) => Limit::perMinute(config('scraper.api_per_token_limit', 30))->by($request->user()?->id ?: $request->ip()));

        Event::listen(AiCallCompleted::class, UsageTrackingListener::class);
        Event::listen(AiCallCompleted::class, CostAlertListener::class);

        AiUsageLog::observe(AiUsageLogObserver::class);
    }

    /**
     * Configure default behaviors for production-ready applications.
     */
    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(
            fn (): ?Password => app()->isProduction()
                ? Password::min(12)
                    ->mixedCase()
                    ->letters()
                    ->numbers()
                    ->symbols()
                    ->uncompromised()
                : null,
        );
    }
}
