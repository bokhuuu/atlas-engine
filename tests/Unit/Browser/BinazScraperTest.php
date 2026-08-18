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

test('extractFloor returns floor number from apartment format', function () {
    $html = '<div class="product-properties__i">
        <label>Этаж</label>
        <span class="product-properties__i-value">16 / 17</span>
    </div>';

    $method = new ReflectionMethod(BinazScraper::class, 'extractFloor');
    $scraper = new BinazScraper(new BinazOfficeSaleProfile, app(ProxyResolver::class));

    expect($method->invoke($scraper, new Crawler($html)))->toBe('16');
});

test('extractFloor returns value as-is for office format', function () {
    $html = '<div class="product-properties__i">
        <label>Этаж</label>
        <span class="product-properties__i-value">5</span>
    </div>';

    $method = new ReflectionMethod(BinazScraper::class, 'extractFloor');
    $scraper = new BinazScraper(new BinazOfficeSaleProfile, app(ProxyResolver::class));

    expect($method->invoke($scraper, new Crawler($html)))->toBe('5');
});

test('extractTotalFloors returns total from apartment slash format', function () {
    $html = '<div class="product-properties__i">
        <label>Этаж</label>
        <span class="product-properties__i-value">16 / 17</span>
    </div>';

    $method = new ReflectionMethod(BinazScraper::class, 'extractTotalFloors');
    $scraper = new BinazScraper(new BinazOfficeSaleProfile, app(ProxyResolver::class));

    expect($method->invoke($scraper, new Crawler($html)))->toBe('17');
});

test('extractTotalFloors falls back to separate row for offices', function () {
    $html = '<div class="product-properties__i">
        <label>Этажей в здании</label>
        <span class="product-properties__i-value">12</span>
    </div>';

    $method = new ReflectionMethod(BinazScraper::class, 'extractTotalFloors');
    $scraper = new BinazScraper(new BinazOfficeSaleProfile, app(ProxyResolver::class));

    expect($method->invoke($scraper, new Crawler($html)))->toBe('12');
});

test('extractTotalFloors returns null when no floor data present', function () {
    $html = '<div class="product-properties__i">
        <label>Площадь</label>
        <span class="product-properties__i-value">85 м²</span>
    </div>';

    $method = new ReflectionMethod(BinazScraper::class, 'extractTotalFloors');
    $scraper = new BinazScraper(new BinazOfficeSaleProfile, app(ProxyResolver::class));

    expect($method->invoke($scraper, new Crawler($html)))->toBeNull();
});
