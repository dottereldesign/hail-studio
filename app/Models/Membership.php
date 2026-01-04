<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Membership extends Model
{
    use HasFactory;

    public const ROLE_OWNER = 'OWNER';
    public const ROLE_ADMIN = 'ADMIN';
    public const ROLE_EDITOR = 'EDITOR';
    public const ROLE_VIEWER = 'VIEWER';

    public const ROLES = [
        self::ROLE_OWNER,
        self::ROLE_ADMIN,
        self::ROLE_EDITOR,
        self::ROLE_VIEWER,
    ];

    protected $fillable = [
        'user_id',
        'organization_id',
        'role',
    ];

    /**
     * @return BelongsTo<User, Membership>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo<Organization, Membership>
     */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }
}
