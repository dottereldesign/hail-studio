<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Layout extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_id',
        'layout_category_id',
        'name',
        'slug',
        'image_url',
        'payload',
        'position',
    ];

    protected $casts = [
        'payload' => 'array',
    ];

    public function getImageUrlAttribute(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        if (Str::startsWith($value, ['http://', 'https://', '/storage/'])) {
            return $value;
        }

        return Storage::url($value);
    }

    /**
     * @return BelongsTo<LayoutCategory, Layout>
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(LayoutCategory::class, 'layout_category_id');
    }

    /**
     * @return BelongsTo<Organization, Layout>
     */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }
}
