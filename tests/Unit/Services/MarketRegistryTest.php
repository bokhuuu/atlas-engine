<?php

declare(strict_types=1);

use App\Services\MarketRegistry;

test('allSlugs returns all configured markets', function () {
    $registry = new MarketRegistry;

    expect($registry->allSlugs())
        ->toContain('yerevan')
        ->toContain('baku')
        ->toContain('tbilisi');
});

test('isValid returns true for known slugs', function () {
    $registry = new MarketRegistry;

    expect($registry->isValid('yerevan'))->toBeTrue()
        ->and($registry->isValid('baku'))->toBeTrue()
        ->and($registry->isValid('tbilisi'))->toBeTrue();
});

test('isValid returns false for unknown and reserved slugs', function () {
    $registry = new MarketRegistry;

    expect($registry->isValid('london'))->toBeFalse()
        ->and($registry->isValid('global'))->toBeFalse();
});

test('fromSlug returns correct market data', function () {
    $registry = new MarketRegistry;
    $market = $registry->fromSlug('yerevan');

    expect($market)->not->toBeNull()
        ->and($market['label'])->toBe('Yerevan')
        ->and($market['flag'])->toBe('🇦🇲');
});

test('fromSlug returns null for unknown slug', function () {
    $registry = new MarketRegistry;

    expect($registry->fromSlug('london'))->toBeNull();
});

test('fromSourcePrefix resolves correct market slug', function () {
    $registry = new MarketRegistry;

    expect($registry->fromSourcePrefix('listam'))->toBe('yerevan')
        ->and($registry->fromSourcePrefix('binaz'))->toBe('baku')
        ->and($registry->fromSourcePrefix('unknown'))->toBeNull();
});

test('defaultSlug returns yerevan', function () {
    $registry = new MarketRegistry;

    expect($registry->defaultSlug())->toBe('yerevan');
});
