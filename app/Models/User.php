<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
    ];

    public function isSuperAdmin(): bool
    {
        return (bool) $this->roles()
            ->whereIn('slug', ['super-admin'])
            ->count();
    }

    public function fullName(): Attribute
    {
            return new Attribute(
                fn ($value, $attributes) => ucwords($attributes['first_name'].' '.$attributes['last_name'])
            );
        }

    public function hasAbilityTo($permissible, $action): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        return (bool) $this->roles()
            ->whereHas('permissions', function ($query) use ($permissible, $action) {
                $query->where('content_type', $permissible)->where('slug', $action);
            })
            ->count();
    }


    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }
}
