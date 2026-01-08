<?php

namespace Database\Seeders;

use App\Models\LayoutCategory;
use App\Models\Organization;
use App\Support\LayoutCatalog;
use Illuminate\Database\Seeder;

class LayoutCatalogSeeder extends Seeder
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

        foreach (LayoutCatalog::categories() as $index => $categoryData) {
            LayoutCategory::query()->updateOrCreate(
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
