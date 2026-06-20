<?php

declare(strict_types=1);

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

    Route::middleware(['auth:sanctum', 'throttle:api.per_token'])->group(function () {
        Route::post('/scrape/start', [ScrapeController::class, 'start']);
        Route::get('/scrape/status', [ScrapeController::class, 'status']);
        Route::post('/scrape/cancel', [ScrapeController::class, 'cancel']);

        Route::get('/listings', [ListingController::class, 'index']);
        Route::get('/listings/stats', [ListingController::class, 'stats']);
    });
});
