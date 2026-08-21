<?php

declare(strict_types=1);

use App\Models\User;
use App\Modules\Scraper\Models\Listing;
use Database\Seeders\Auth\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(RoleSeeder::class);
    $user = User::factory()->create();
    $user->assignRole('super_admin');
    $this->actingAs($user);
});

test('global market stats page loads successfully', function () {
    $this->get('/global/admin/market-stats')
        ->assertOk()
        ->assertInertia(
            fn ($page) => $page
                ->component('admin/global-market-stats')
                ->has('marketStats')
                ->has('allCategories')
        );
});

test('global market stats returns all configured markets', function () {
    $this->get('/global/admin/market-stats')
        ->assertOk()
        ->assertInertia(
            fn ($page) => $page
                ->component('admin/global-market-stats')
                ->where('marketStats', fn ($markets) => count($markets) === 3)
        );
});

test('global market stats shows yerevan data when listings exist', function () {
    Listing::factory()->create([
        'source_profile_name' => 'listam_apartments_sale',
        'external_id' => 'test-1',
        'price_per_sqm' => 2000,
    ]);

    $this->get('/global/admin/market-stats')
        ->assertOk()
        ->assertInertia(
            fn ($page) => $page
                ->component('admin/global-market-stats')
                ->has('marketStats')
        );
});

test('global market stats includes all categories', function () {
    $this->get('/global/admin/market-stats')
        ->assertOk()
        ->assertInertia(
            fn ($page) => $page
                ->component('admin/global-market-stats')
                ->where('allCategories', fn ($cats) => count($cats) > 0)
        );
});

test('global dashboard page loads successfully', function () {
    $this->get('/global/admin/dashboard')
        ->assertOk()
        ->assertInertia(
            fn ($page) => $page
                ->component('admin/global-dashboard')
                ->has('marketStats')
                ->has('totalListingsAllMarkets')
        );
});

test('global assistant page loads successfully', function () {
    $this->get('/global/admin/assistant')
        ->assertOk()
        ->assertInertia(
            fn ($page) => $page
                ->component('admin/assistant')
        );
});
