<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar_path',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * @return HasMany<Membership>
     */
    public function memberships(): HasMany
    {
        return $this->hasMany(Membership::class);
    }

    /**
     * @return HasOne<Membership>
     */
    public function currentMembership(): HasOne
    {
        return $this->hasOne(Membership::class)->latestOfMany();
    }

    public function role(): ?string
    {
        $membership = $this->relationLoaded('currentMembership')
            ? $this->currentMembership
            : $this->currentMembership()->first();

        return $membership?->role;
    }

    /**
     * @param array<int, string> $roles
     */
    public function hasAnyRole(array $roles): bool
    {
        $role = $this->role();

        return $role !== null && in_array($role, $roles, true);
    }

    public function currentOrganizationId(): ?int
    {
        $membership = $this->relationLoaded('currentMembership')
            ? $this->currentMembership
            : $this->currentMembership()->first();

        return $membership?->organization_id;
    }

    public function getAvatarUrlAttribute(): ?string
    {
        if (! $this->avatar_path) {
            return null;
        }

        return \Illuminate\Support\Facades\Storage::url($this->avatar_path);
    }
}
