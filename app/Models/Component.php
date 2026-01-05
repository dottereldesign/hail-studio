<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Component extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_id',
        'component_category_id',
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
     * @return BelongsTo<ComponentCategory, Component>
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(ComponentCategory::class, 'component_category_id');
    }

    /**
     * @return BelongsTo<Organization, Component>
     */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }
}
