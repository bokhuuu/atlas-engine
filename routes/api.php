<?php

declare(strict_types=1);

use App\Http\Controllers\Public\SnapshotController;
use App\Modules\AI\Api\ChatController;
use App\Modules\AI\Api\FeedbackController;
use App\Modules\AI\Api\HealthCheckController;
use App\Modules\AI\Api\WebhookController;
use App\Modules\Scraper\Api\ExportController;
use App\Modules\Scraper\Api\HealthController;
use App\Modules\Scraper\Api\ListingController;
use App\Modules\Scraper\Api\ScrapeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('v1')->group(function () {
    Route::get('/health', HealthController::class);

    Route::prefix('public')->middleware('throttle:60,1')->group(function () {
        Route::get('/snapshot', SnapshotController::class);
    });

    Route::middleware(['auth:sanctum'])->group(function () {
        Route::get('/listings/filter', [ListingController::class, 'filter']);
        Route::get('/exports/filtered', [ExportController::class, 'downloadFiltered']);
    });

    Route::middleware(['auth:sanctum', 'throttle:api.per_token'])->group(function () {
        Route::post('/scrape/start', [ScrapeController::class, 'start']);
        Route::get('/scrape/status', [ScrapeController::class, 'status']);
        Route::post('/scrape/cancel', [ScrapeController::class, 'cancel']);

        Route::post('scrape/start-all', [ScrapeController::class, 'startAll']);
        Route::get('scrape/status-all', [ScrapeController::class, 'statusAll']);
        Route::get('scrape/trend', [ScrapeController::class, 'trend']);
        Route::post('scrape/cancel-all', [ScrapeController::class, 'cancelAll']);

        Route::get('/listings', [ListingController::class, 'index']);
        Route::get('/listings/stats', [ListingController::class, 'stats']);
        Route::get('/listings/summary', [ListingController::class, 'summary']);
        Route::get('/listings/yield', [ListingController::class, 'yield']);

        Route::get('/exports', [ExportController::class, 'downloadAll']);
        Route::get('/exports/{source}', [ExportController::class, 'download']);

        Route::get('/ai/health', [HealthCheckController::class, 'check']);
        Route::post('/ai/feedback', [FeedbackController::class, 'store']);

        Route::get('/ai/history', [ChatController::class, 'history']);
        Route::get('/ai/usage/system', [ChatController::class, 'systemUsage'])
            ->middleware('role:admin|super_admin');
        Route::get('/ai/usage', [ChatController::class, 'usage']);
        Route::get('/ai/feedback', [ChatController::class, 'feedback']);
        Route::post('/ai/feedback', [FeedbackController::class, 'store']);
        Route::post('/ai/chat', [ChatController::class, 'chat']);
    });

    Route::post('/ai/webhook', [WebhookController::class, 'receive']);
});
