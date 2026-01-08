<?php

namespace Database\Seeders;

use App\Models\Membership;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $organization = Organization::query()->firstOrCreate(
            ['slug' => 'hail-studio'],
            ['name' => 'hail Studio']
        );

        $user = User::query()->firstOrCreate(
            ['email' => 'admin@hail.studio'],
            [
                'name' => 'Hail Admin',
                'password' => Hash::make('password'),
            ]
        );

        Membership::query()->firstOrCreate([
            'user_id' => $user->id,
            'organization_id' => $organization->id,
        ], [
            'role' => Membership::ROLE_OWNER,
        ]);

        $this->call(ComponentCatalogSeeder::class);
        $this->call(LayoutCatalogSeeder::class);
    }
}
