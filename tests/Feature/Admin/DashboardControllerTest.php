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

test('dashboard page loads successfully for yerevan', function () {
    $this->get('/yerevan/admin/dashboard')
        ->assertOk()
        ->assertInertia(
            fn ($page) => $page
                ->component('admin/dashboard')
                ->has('scraperCategories')
                ->has('grandTotalListings')
                ->has('stats')
        );
});

test('dashboard only shows yerevan listings', function () {
    Listing::factory()->create([
        'source_profile_name' => 'listam_apartments_sale',
        'external_id' => 'yerevan-1',
    ]);

    $this->get('/yerevan/admin/dashboard')
        ->assertOk()
        ->assertInertia(
            fn ($page) => $page
                ->component('admin/dashboard')
                ->has('scraperCategories')
                ->where('grandTotalListings', 1)
        );
});

test('dashboard shows empty categories for baku with no data', function () {
    $this->get('/baku/admin/dashboard')
        ->assertOk()
        ->assertInertia(
            fn ($page) => $page
                ->component('admin/dashboard')
                ->where('grandTotalListings', 0)
        );
});
