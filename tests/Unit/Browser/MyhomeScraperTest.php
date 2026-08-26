<?php

declare(strict_types=1);

use App\Modules\Scraper\Browser\MyhomeScraper;
use App\Modules\Scraper\Browser\ProxyResolver;
use App\Modules\Scraper\Contracts\ScraperProfileInterface;

/**
 * Tests for MyhomeScraper — covers the two pure PHP methods
 * that can be tested without a real browser session:
 *   - isListingUrl()  → filters genuine listing URLs from nav/footer links
 *   - extractId()     → pulls numeric ID from URL slug
 *
 * Browser-dependent methods (crawlIndexPage, crawlDetailPage, extractPhone)
 * require a live Chrome session and are verified via tinker on production.
 */
$profile = Mockery::mock(ScraperProfileInterface::class);
$profile->allows('getRequestDelay')->andReturn(0);
$profile->allows('getName')->andReturn('myhome_apartments_sale');

$proxyResolver = Mockery::mock(ProxyResolver::class);
$proxyResolver->allows('resolve')->andReturn(null);

// Expose protected methods via anonymous subclass
$scraper = new class($profile, $proxyResolver) extends MyhomeScraper
{
    public function publicIsListingUrl(string $href): bool
    {
        return $this->isListingUrl($href);
    }

    public function publicExtractId(string $url): ?string
    {
        return $this->extractId($url);
    }
};

// -------------------------------------------------------------------------
// isListingUrl — listing URL detection
// -------------------------------------------------------------------------

test('it accepts a valid apartment listing URL', function () use ($scraper) {
    expect(
        $scraper->publicIsListingUrl(
            'https://www.myhome.ge/en/real-estate/4-room-apartment-for-sale-in-saburtalo-25738134/'
        )
    )->toBeTrue();
});

test('it accepts a valid commercial listing URL', function () use ($scraper) {
    expect(
        $scraper->publicIsListingUrl(
            'https://www.myhome.ge/en/real-estate/office-commercial-area-for-rent-in-saburtalo-22829831/'
        )
    )->toBeTrue();
});

test('it accepts a listing URL without trailing slash', function () use ($scraper) {
    expect(
        $scraper->publicIsListingUrl(
            'https://www.myhome.ge/en/real-estate/2-room-apartment-for-sale-in-sanzona-25815643'
        )
    )->toBeTrue();
});

test('it rejects a nav link with no numeric ID', function () use ($scraper) {
    expect(
        $scraper->publicIsListingUrl(
            'https://www.myhome.ge/en/real-estate/sale/apartment'
        )
    )->toBeFalse();
});

test('it rejects a category browse link', function () use ($scraper) {
    expect(
        $scraper->publicIsListingUrl(
            'https://www.myhome.ge/en/real-estate/rent/commercial-area'
        )
    )->toBeFalse();
});

test('it rejects a footer link with city but no listing ID', function () use ($scraper) {
    expect(
        $scraper->publicIsListingUrl(
            'https://www.myhome.ge/en/real-estate/apartment/tbilisi/saburtalo'
        )
    )->toBeFalse();
});

test('it rejects the auth redirect URL', function () use ($scraper) {
    expect(
        $scraper->publicIsListingUrl(
            'https://auth.tnet.ge/en/user/login/?Continue=https://www.myhome.ge/real-estate/sale/apartment'
        )
    )->toBeFalse();
});

test('it rejects a room-filter browse link', function () use ($scraper) {
    expect(
        $scraper->publicIsListingUrl(
            'https://www.myhome.ge/en/real-estate/sale/apartment/1-room'
        )
    )->toBeFalse();
});

// -------------------------------------------------------------------------
// extractId — numeric ID extraction from URL slug
// -------------------------------------------------------------------------

test('it extracts ID from a standard apartment URL', function () use ($scraper) {
    expect(
        $scraper->publicExtractId(
            'https://www.myhome.ge/en/real-estate/4-room-apartment-for-sale-in-saburtalo-25738134/'
        )
    )->toBe('25738134');
});

test('it extracts ID from a commercial listing URL', function () use ($scraper) {
    expect(
        $scraper->publicExtractId(
            'https://www.myhome.ge/en/real-estate/office-commercial-area-for-rent-in-saburtalo-22829831/'
        )
    )->toBe('22829831');
});

test('it extracts ID from a URL without trailing slash', function () use ($scraper) {
    expect(
        $scraper->publicExtractId(
            'https://www.myhome.ge/en/real-estate/2-room-apartment-for-sale-in-sanzona-25815643'
        )
    )->toBe('25815643');
});

test('it returns null for a URL with no numeric suffix', function () use ($scraper) {
    expect(
        $scraper->publicExtractId(
            'https://www.myhome.ge/en/real-estate/sale/apartment'
        )
    )->toBeNull();
});
