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

// Yerevan
Schedule::command('scraper:run listam_apartments_sale --pages=2')->days([1, 3, 5])->at('05:00');
Schedule::command('scraper:run listam_apartments_rent --pages=2')->days([1, 3, 5])->at('05:10');
Schedule::command('scraper:run listam_houses_sale --pages=1')->days([1, 3, 5])->at('05:20');
Schedule::command('scraper:run listam_commercial_sale --pages=1')->days([1, 3, 5])->at('05:30');
Schedule::command('scraper:run listam_commercial_rent --pages=1')->days([1, 3, 5])->at('05:40');
Schedule::command('scraper:run listam_land_sale --pages=1')->days([1, 3, 5])->at('05:50');
Schedule::command('scraper:run listam_new_developments_sale --pages=1')->days([1, 3, 5])->at('06:00');

// Baku market - bina.az land listings
Schedule::command('scraper:run binaz_land_sale --pages=1')->dailyAt('12:00');
Schedule::command('scraper:run binaz_land_sale_sea_breeze --pages=1')->dailyAt('12:10');
Schedule::command('scraper:run binaz_offices_sale --pages=1')->dailyAt('12:20');
Schedule::command('scraper:run binaz_offices_rent --pages=1')->dailyAt('12:30');
// Schedule::command('scraper:run binaz_apartments_sale --pages=1')->dailyAt('12:40');
// Schedule::command('scraper:run binaz_apartments_rent --pages=1')->dailyAt('12:50');
Schedule::command('scraper:run binaz_houses_sale --pages=1')->dailyAt('12:40');
Schedule::command('scraper:run binaz_houses_rent --pages=1')->dailyAt('12:50');
Schedule::command('atlas:embed-listings --source=binaz_land_sale')->dailyAt('13:00');
Schedule::command('atlas:embed-listings --source=binaz_land_sale_sea_breeze')->dailyAt('13:10');
Schedule::command('atlas:embed-listings --source=binaz_offices_sale')->dailyAt('13:20');
Schedule::command('atlas:embed-listings --source=binaz_offices_rent')->dailyAt('13:30');
// Schedule::command('atlas:embed-listings --source=binaz_apartments_sale')->dailyAt('13:40');
// Schedule::command('atlas:embed-listings --source=binaz_apartments_rent')->dailyAt('13:50');
Schedule::command('atlas:embed-listings --source=binaz_houses_sale')->dailyAt('14:00');
Schedule::command('atlas:embed-listings --source=binaz_houses_rent')->dailyAt('14:10');
// Schedule::command('atlas:capture-screenshots --market=baku')->dailyAt('15:00');

// Requires residential proxies to bypass list.am server IP block on non-browser requests
// Schedule::command('atlas:check-listings')->days([0, 2, 4, 6])->at('10:00');
