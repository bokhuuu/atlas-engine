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

test('data hub page loads successfully', function () {
    $this->get('/yerevan/admin/data-hub')
        ->assertOk()
        ->assertInertia(
            fn ($page) => $page
                ->component('admin/data-hub')
                ->has('categories')
                ->has('districts')
        );
});

test('data hub only shows yerevan categories', function () {
    $response = $this->get('/yerevan/admin/data-hub');

    $categories = $response->original->getData()['page']['props']['categories'];

    expect(collect($categories)->every(
        fn ($cat) => str_starts_with($cat['source'], 'listam_')
    ))->toBeTrue();
});

test('data hub shows binaz categories for baku', function () {
    $response = $this->get('/baku/admin/data-hub');

    $categories = $response->original->getData()['page']['props']['categories'];

    expect(collect($categories)->every(
        fn ($cat) => str_starts_with($cat['source'], 'binaz_')
    ))->toBeTrue();
});
