<?php

declare(strict_types=1);

namespace Database\Seeders\Auth;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminUser = User::firstOrCreate(
            ['email' => 'zura.coding@gmail.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('AtlasAtlas2026!'),
            ]
        );

        $adminUser->assignRole(UserRole::SUPER_ADMIN->value);
    }
}
