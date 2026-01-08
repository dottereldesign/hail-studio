<?php

namespace App\Support;

class LayoutCatalog
{
    /**
     * @return array<int, array{name: string, slug: string}>
     */
    public static function categories(): array
    {
        return CatalogCategories::categories();
    }
}
