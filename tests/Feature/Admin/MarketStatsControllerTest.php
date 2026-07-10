<?php

declare(strict_types=1);

use App\Models\User;
use Database\Seeders\Auth\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(RoleSeeder::class);
    $user = User::factory()->create();
    $user->assignRole('super_admin');
    $this->actingAs($user);
});

test('market stats page loads successfully for yerevan', function () {
    $this->get('/yerevan/admin/market-stats')
        ->assertOk()
        ->assertInertia(
            fn ($page) => $page
                ->component('admin/market-stats')
                ->has('categories')
        );
});

test('market stats only shows yerevan categories', function () {
    $response = $this->get('/yerevan/admin/market-stats');

    $categories = $response->original->getData()['page']['props']['categories'];

    expect(collect($categories)->every(
        fn ($cat) => str_starts_with($cat['source'], 'listam_')
    ))->toBeTrue();
});

test('market stats shows empty categories for baku with no profiles', function () {
    $this->get('/baku/admin/market-stats')
        ->assertOk()
        ->assertInertia(
            fn ($page) => $page
                ->component('admin/market-stats')
                ->where('categories', [])
        );
});
