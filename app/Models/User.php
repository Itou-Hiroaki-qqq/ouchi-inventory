<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * 自分が他人を招待して共有しているリレーション
     */
    public function sharedWith(): HasMany
    {
        return $this->hasMany(\App\Models\Share::class, 'owner_id');
    }

    /**
     * 他人から共有されているリレーション
     */
    public function sharedBy(): HasMany
    {
        return $this->hasMany(\App\Models\Share::class, 'shared_user_id');
    }
}
