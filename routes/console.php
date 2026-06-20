<?php

declare(strict_types=1);

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('scraper:run listam_apartments_sale --pages=3')->dailyAt('02:00');
Schedule::command('scraper:run listam_apartments_rent --pages=3')->dailyAt('02:00');
Schedule::command('scraper:run listam_houses_sale --pages=3')->dailyAt('02:00');
Schedule::command('scraper:run listam_commercial_sale --pages=3')->dailyAt('02:00');
Schedule::command('scraper:run listam_commercial_rent --pages=3')->dailyAt('02:00');
Schedule::command('scraper:run listam_land_sale --pages=3')->dailyAt('02:00');
Schedule::command('scraper:run listam_new_developments_sale --pages=3')->dailyAt('02:00');
