<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
