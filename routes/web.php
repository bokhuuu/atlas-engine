<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\AiFeedbackController;
use App\Http\Controllers\Admin\AssistantController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DataHubController;
use App\Http\Controllers\Admin\MarketStatsController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\SystemHealthController;
use App\Http\Controllers\Admin\TokenController;
use App\Http\Controllers\Admin\UserController;
use App\Modules\Scraper\Api\ExportController;
use App\Services\MarketRegistry;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/'.config('scraper.default_market'))->name('home');

Route::redirect('/admin', '/'.config('scraper.default_market').'/admin/dashboard');
Route::redirect('/admin/dashboard', '/'.config('scraper.default_market').'/admin/dashboard');

foreach (app(MarketRegistry::class)->allSlugs() as $slug) {
    Route::middleware(['market'])
        ->prefix($slug)
        ->group(function () use ($slug) {

            Route::inertia('/', 'public/market-snapshot')->name("market.{$slug}.home");

            Route::middleware(['auth', 'admin'])
                ->prefix('admin')
                ->name('admin.')
                ->group(function () {
                    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
                    Route::get('market-stats', [MarketStatsController::class, 'index'])->name('market-stats');
                    Route::get('assistant', [AssistantController::class, 'index'])->name('assistant');
                    Route::get('exports/{source}', [ExportController::class, 'download'])->name('exports.download');

                    Route::middleware(['role:super_admin|admin'])->prefix('users')->name('users.')->group(function () {
                        Route::get('/', [UserController::class, 'index'])->name('index');
                        Route::get('/create', [UserController::class, 'create'])->name('create');
                        Route::get('/trashed', [UserController::class, 'trashed'])->name('trashed');
                        Route::post('/', [UserController::class, 'store'])->name('store');
                        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
                        Route::put('/{user}', [UserController::class, 'update'])->name('update');
                        Route::delete('/{user}', [UserController::class, 'destroy'])->name('delete');
                        Route::patch('/{user}/restore', [UserController::class, 'restore'])->name('restore');
                        Route::delete('/{user}/force-delete', [UserController::class, 'forceDelete'])->name('force-delete');
                    });

                    Route::middleware(['role:super_admin|admin'])->prefix('settings')->name('settings.')->group(function () {
                        Route::get('/', [SettingController::class, 'index'])->name('index');
                        Route::put('/', [SettingController::class, 'update'])->name('update');
                    });

                    Route::prefix('roles')->name('roles.')->group(function () {
                        Route::get('/', [RoleController::class, 'index'])->name('index');
                        Route::put('/{role}', [RoleController::class, 'update'])->name('update');
                    });

                    Route::get('activity-log', [ActivityLogController::class, 'index'])->name('activity-log.index');

                    Route::prefix('notifications')->name('notifications.')->group(function () {
                        Route::post('/{id}/read', [NotificationController::class, 'markAsRead'])->name('read');
                        Route::post('/read-all', [NotificationController::class, 'markAllAsRead'])->name('read-all');
                    });

                    Route::prefix('tokens')->name('tokens.')->group(function () {
                        Route::get('/', [TokenController::class, 'index'])->name('index');
                        Route::post('/', [TokenController::class, 'store'])->name('store');
                        Route::delete('/{id}', [TokenController::class, 'destroy'])->name('destroy');
                    });

                    Route::prefix('system-health')->name('system-health.')->group(function () {
                        Route::get('/', [SystemHealthController::class, 'index'])->name('index');
                        Route::post('/cache', [SystemHealthController::class, 'clearCache'])->name('cache.clear');
                        Route::post('/maintenance', [SystemHealthController::class, 'toggleMaintenance'])->name('maintenance.toggle');
                    });

                    Route::middleware(['role:super_admin|admin'])
                        ->get('ai-feedback', [AiFeedbackController::class, 'index'])
                        ->name('ai-feedback.index');

                    Route::get('data-hub', [DataHubController::class, 'index'])->name('data-hub.index');
                });
        });
}

require __DIR__.'/settings.php';
