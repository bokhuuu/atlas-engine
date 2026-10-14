<?php

declare(strict_types=1);

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('atlas:capture-screenshots')->dailyAt('00:00');

Schedule::command('scraper:clean-zombies --hours=8')->dailyAt('03:00');

Schedule::command('atlas:fetch-exchange-rates')->dailyAt('04:00');

// Yerevan
// Schedule::command('scraper:run listam_apartments_sale --pages=2')->days([1, 3, 5])->at('05:00');
// Schedule::command('scraper:run listam_apartments_rent --pages=2')->days([1, 3, 5])->at('05:45');
// Schedule::command('scraper:run listam_houses_sale --pages=1')->days([1, 3, 5])->at('07:00');
// Schedule::command('scraper:run listam_commercial_sale --pages=1')->days([1, 3, 5])->at('07:30');
// Schedule::command('scraper:run listam_commercial_rent --pages=1')->days([1, 3, 5])->at('08:00');
// Schedule::command('scraper:run listam_land_sale --pages=1')->days([1, 3, 5])->at('08:30');
// Schedule::command('scraper:run listam_new_developments_sale --pages=1')->days([1, 3, 5])->at('09:00');

// Baku market - bina.az
Schedule::command('scraper:run binaz_land_sale --pages=1')->daily()->at('08:00');
Schedule::command('scraper:run binaz_land_sale_sea_breeze --pages=1')->daily()->at('08:10');
Schedule::command('scraper:run binaz_offices_sale --pages=1')->daily()->at('08:20');
Schedule::command('scraper:run binaz_offices_rent --pages=1')->daily()->at('08:30');
Schedule::command('scraper:run binaz_apartments_sale --pages=1')->daily()->at('09:00');
Schedule::command('scraper:run binaz_apartments_rent --pages=1')->daily()->at('09:10');
Schedule::command('scraper:run binaz_houses_sale --pages=1')->daily()->at('09:20');
Schedule::command('scraper:run binaz_houses_rent --pages=1')->daily()->at('09:30');
Schedule::command('scraper:run binaz_commercial_sale --pages=1')->daily()->at('10:00');
Schedule::command('scraper:run binaz_commercial_rent --pages=1')->daily()->at('10:10');

// Baku embeddings
Schedule::command('atlas:embed-listings --source=binaz_land_sale')->daily()->at('11:00');
Schedule::command('atlas:embed-listings --source=binaz_land_sale_sea_breeze')->daily()->at('11:10');
Schedule::command('atlas:embed-listings --source=binaz_offices_sale')->daily()->at('11:20');
Schedule::command('atlas:embed-listings --source=binaz_offices_rent')->daily()->at('11:30');
Schedule::command('atlas:embed-listings --source=binaz_apartments_sale')->daily()->at('12:00');
Schedule::command('atlas:embed-listings --source=binaz_apartments_rent')->daily()->at('12:10');
Schedule::command('atlas:embed-listings --source=binaz_houses_sale')->daily()->at('12:20');
Schedule::command('atlas:embed-listings --source=binaz_houses_rent')->daily()->at('12:30');
Schedule::command('atlas:embed-listings --source=binaz_commercial_sale')->daily()->at('13:00');
Schedule::command('atlas:embed-listings --source=binaz_commercial_rent')->daily()->at('13:10');

// Schedule::command('atlas:capture-screenshots --market=baku')->dailyAt('15:00');

// Requires residential proxies to bypass list.am server IP block on non-browser requests
// Schedule::command('atlas:check-listings')->days([0, 2, 4, 6])->at('10:00');

// Tbilisi market - myhome.ge
// Schedules commented out pending Colliers confirmation on volume/strategy.
// Schedule::command('scraper:run myhome_apartments_sale --pages=2')->dailyAt('16:00');
// Schedule::command('scraper:run myhome_apartments_rent --pages=2')->dailyAt('16:10');
// Schedule::command('scraper:run myhome_houses_sale --pages=1')->dailyAt('16:20');
// Schedule::command('scraper:run myhome_houses_rent --pages=1')->dailyAt('16:30');
// Schedule::command('scraper:run myhome_country_houses_sale --pages=1')->dailyAt('16:40');
// Schedule::command('scraper:run myhome_country_houses_rent --pages=1')->dailyAt('16:50');
// Schedule::command('scraper:run myhome_land_sale --pages=1')->dailyAt('17:00');
// Schedule::command('scraper:run myhome_commercial_sale --pages=1')->dailyAt('17:10');
// Schedule::command('scraper:run myhome_commercial_rent --pages=1')->dailyAt('17:20');
// Schedule::command('atlas:embed-listings --source=myhome_apartments_sale')->dailyAt('18:00');
// Schedule::command('atlas:embed-listings --source=myhome_apartments_rent')->dailyAt('18:10');
// Schedule::command('atlas:embed-listings --source=myhome_houses_sale')->dailyAt('18:20');
// Schedule::command('atlas:embed-listings --source=myhome_houses_rent')->dailyAt('18:30');
// Schedule::command('atlas:embed-listings --source=myhome_country_houses_sale')->dailyAt('18:40');
// Schedule::command('atlas:embed-listings --source=myhome_country_houses_rent')->dailyAt('18:50');
// Schedule::command('atlas:embed-listings --source=myhome_land_sale')->dailyAt('19:00');
// Schedule::command('atlas:embed-listings --source=myhome_commercial_sale')->dailyAt('19:10');
// Schedule::command('atlas:embed-listings --source=myhome_commercial_rent')->dailyAt('19:20');
