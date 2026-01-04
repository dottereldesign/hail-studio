<?php

namespace Database\Seeders;

use App\Models\Component;
use App\Models\ComponentCategory;
use App\Models\Organization;
use App\Support\ComponentCatalog;
use Illuminate\Database\Seeder;

class ComponentCatalogSeeder extends Seeder
{
    public function run(): void
    {
        $organization = Organization::query()->firstOrFail();

        foreach (ComponentCatalog::categories() as $index => $categoryData) {
            $category = ComponentCategory::query()->updateOrCreate(
                [
                    'organization_id' => $organization->id,
                    'slug' => $categoryData['slug'],
                ],
                [
                    'name' => $categoryData['name'],
                    'position' => $index + 1,
                ]
            );

            $items = ComponentCatalog::itemsForCategory($categoryData['name'], $categoryData['slug']);

            foreach ($items as $itemIndex => $itemData) {
                Component::query()->updateOrCreate(
                    [
                        'organization_id' => $organization->id,
                        'slug' => $itemData['slug'],
                    ],
                    [
                        'component_category_id' => $category->id,
                        'name' => $itemData['name'],
                        'image_url' => $itemData['image_url'],
                        'payload' => $itemData['payload'],
                        'position' => $itemIndex + 1,
                    ]
                );
            }
        }
    }
}
