<?php

declare(strict_types=1);

use App\Http\Controllers\Public\SnapshotController;
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

    Route::prefix('public')->group(function () {
        Route::get('/snapshot', SnapshotController::class);
    });

    Route::middleware(['auth:sanctum', 'throttle:api.per_token'])->group(function () {
        Route::post('/scrape/start', [ScrapeController::class, 'start']);
        Route::get('/scrape/status', [ScrapeController::class, 'status']);
        Route::post('/scrape/cancel', [ScrapeController::class, 'cancel']);

        Route::post('scrape/start-all', [ScrapeController::class, 'startAll']);
        Route::get('scrape/status-all', [ScrapeController::class, 'statusAll']);
        Route::post('scrape/cancel-all', [ScrapeController::class, 'cancelAll']);

        Route::get('/listings', [ListingController::class, 'index']);
        Route::get('/listings/stats', [ListingController::class, 'stats']);
    });
});
