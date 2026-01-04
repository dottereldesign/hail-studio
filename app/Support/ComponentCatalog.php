<?php

namespace App\Support;

use Illuminate\Support\Str;

class ComponentCatalog
{
    /**
     * @return array<int, array{name: string, slug: string}>
     */
    public static function categories(): array
    {
        $names = [
            'Navbars',
            'Footers',
            'Hero Sections',
            'Header Sections',
            'Gallery Sections',
            'Feature Sections',
            'CTA Sections',
            'FAQ Sections',
            'Contact Sections',
            'Testimonial Sections',
            'Logo Sections',
            'Comparison Sections',
            'Banners',
            'Pricing Sections',
            'Career Sections',
            'Stats Sections',
            'Event Sections',
            'Event Archives',
            'Blog Archives',
            'Blog Sections',
            'Product Archives',
            'Product Sections',
            'Single Products',
            'Single Blogs',
            'Cart Pages',
            'Portfolio Headers',
            'Portfolio Sections',
            'Team Sections',
            'Login Sections',
            'Signup Sections',
            'Step Sections',
            '404 Pages',
            'Notice Pages',
            'Link Pages',
        ];

        return array_map(function (string $name) {
            return [
                'name' => $name,
                'slug' => Str::slug($name),
            ];
        }, $names);
    }

    /**
     * @return array<int, array{name: string, slug: string, image_url: string, payload: array<string, mixed>}>
     */
    public static function itemsForCategory(string $categoryName, string $categorySlug): array
    {
        $items = [];

        for ($index = 1; $index <= 2; $index++) {
            $items[] = [
                'name' => sprintf('%s %d', $categoryName, $index),
                'slug' => sprintf('%s-%d', $categorySlug, $index),
                'image_url' => '/components/placeholder.svg',
                'payload' => [
                    'id' => sprintf('%s-%d', $categorySlug, $index),
                    'name' => sprintf('%s %d', $categoryName, $index),
                    'category' => $categoryName,
                    'layout' => [
                        'variant' => 'default',
                        'padding' => 'md',
                        'theme' => 'agency',
                    ],
                ],
            ];
        }

        return $items;
    }
}
