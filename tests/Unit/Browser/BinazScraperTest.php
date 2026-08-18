<?php

declare(strict_types=1);

use App\Modules\Scraper\Browser\BinazScraper;
use App\Modules\Scraper\Browser\ProxyResolver;
use App\Modules\Scraper\Profiles\BinazOfficeSaleProfile;
use Symfony\Component\DomCrawler\Crawler;

function makeExtractIsNewBuilding(Crawler $crawler): ?bool
{
    $scraper = new BinazScraper(new BinazOfficeSaleProfile, app(ProxyResolver::class));
    $method = new ReflectionMethod(BinazScraper::class, 'extractIsNewBuilding');

    return $method->invoke($scraper, $crawler);
}

test('extractIsNewBuilding returns true for new building', function () {
    $html = '<div class="product-properties__i">
        <label>Категория</label>
        <span class="product-properties__i-value">Новостройка</span>
    </div>';

    expect(makeExtractIsNewBuilding(new Crawler($html)))->toBeTrue();
});

test('extractIsNewBuilding returns false for secondary building', function () {
    $html = '<div class="product-properties__i">
        <label>Категория</label>
        <span class="product-properties__i-value">Вторичка</span>
    </div>';

    expect(makeExtractIsNewBuilding(new Crawler($html)))->toBeFalse();
});

test('extractIsNewBuilding returns null when category row absent', function () {
    $html = '<div class="product-properties__i">
        <label>Площадь</label>
        <span class="product-properties__i-value">85 м²</span>
    </div>';

    expect(makeExtractIsNewBuilding(new Crawler($html)))->toBeNull();
});
