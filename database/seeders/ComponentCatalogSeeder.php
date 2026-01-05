<?php

namespace Database\Seeders;

use App\Models\ComponentCategory;
use App\Models\Organization;
use App\Support\ComponentCatalog;
use Illuminate\Database\Seeder;

class ComponentCatalogSeeder extends Seeder
{
    public function run(): void
    {
        $organization = Organization::query()->first();

        if (! $organization) {
            $organization = Organization::query()->create([
                'name' => 'hail Studio',
                'slug' => 'hail-studio',
            ]);
        }

        foreach (ComponentCatalog::categories() as $index => $categoryData) {
            ComponentCategory::query()->updateOrCreate(
                [
                    'organization_id' => $organization->id,
                    'slug' => $categoryData['slug'],
                ],
                [
                    'name' => $categoryData['name'],
                    'position' => $index + 1,
                ]
            );
        }
    }
}
