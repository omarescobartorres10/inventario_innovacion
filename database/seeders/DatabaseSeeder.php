<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $adminEmail = env('SEED_ADMIN_EMAIL');
        $adminPassword = env('SEED_ADMIN_PASSWORD');

        if (filled($adminEmail) && filled($adminPassword)) {
            $adminUsername = env('SEED_ADMIN_USERNAME');

            if (! filled($adminUsername)) {
                $adminUsername = Str::slug(env('SEED_ADMIN_NAME', 'Administrador General'), '');
            }

            User::query()->updateOrCreate(
                ['email' => $adminEmail],
                [
                    'name' => env('SEED_ADMIN_NAME', 'Administrador General'),
                    'username' => $adminUsername,
                    'phone' => env('SEED_ADMIN_PHONE', '7000-0000'),
                    'position' => env('SEED_ADMIN_POSITION', 'Administrador'),
                    'email' => $adminEmail,
                    'role' => User::ROLE_ADMIN,
                    'permissions' => User::defaultModulePermissions(),
                    'is_active' => true,
                    'password' => Hash::make($adminPassword),
                ]
            );
        }

        $this->call([
            InventorySeeder::class,
        ]);
    }
}
