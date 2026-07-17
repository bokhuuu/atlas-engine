<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('public overview page loads successfully', function () {
    $this->get('/')
        ->assertOk()
        ->assertInertia(
            fn ($page) => $page
                ->component('public/overview')
                ->has('markets')
                ->has('totalListingsAllMarkets')
        );
});

test('overview page shows all configured markets', function () {
    $this->get('/')
        ->assertOk()
        ->assertInertia(
            fn ($page) => $page
                ->component('public/overview')
                ->where('markets', fn ($markets) => count($markets) === 3)
        );
});

test('overview page shows correct total listings', function () {
    $this->get('/')
        ->assertOk()
        ->assertInertia(
            fn ($page) => $page
                ->component('public/overview')
                ->where('totalListingsAllMarkets', 0)
        );
});
