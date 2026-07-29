<?php

declare(strict_types=1);

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('atlas:capture-screenshots')->dailyAt('00:00');

Schedule::command('atlas:fetch-exchange-rates')->dailyAt('04:00');

// Daily, staggered, apartments get 2 pages
Schedule::command('scraper:run listam_apartments_sale --pages=2')->dailyAt('05:00');
Schedule::command('scraper:run listam_apartments_rent --pages=2')->dailyAt('05:10');
Schedule::command('scraper:run listam_houses_sale --pages=1')->dailyAt('05:20');
Schedule::command('scraper:run listam_commercial_sale --pages=1')->dailyAt('05:30');
Schedule::command('scraper:run listam_commercial_rent --pages=1')->dailyAt('05:40');
Schedule::command('scraper:run listam_land_sale --pages=1')->dailyAt('05:50');
Schedule::command('scraper:run listam_new_developments_sale --pages=1')->dailyAt('06:00');

// Baku market — bina.az land listings
Schedule::command('scraper:run binaz_land_sale --pages=1')->dailyAt('12:00');
// Schedule::command('atlas:capture-screenshots --market=baku')->dailyAt('15:00');

// Requires residential proxies to bypass list.am server IP block on non-browser requests
// Schedule::command('atlas:check-listings')->days([0, 2, 4, 6])->at('10:00');
