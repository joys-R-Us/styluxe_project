<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
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
        'role',
        'phone',
        'address',
        'avatar',
        'is_active',
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
        'is_active' => 'boolean',
        ];
    }

    // Relationships
    public function products()
    {
        return $this->hasMany(Products::class, 'added_by');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'client_id');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function stockLogs()
    {
        return $this->hasMany(StockLog::class);
    }

    // Helper methods
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isClient()
    {
        return $this->role === 'client';
    }

    public function canManageInventory()
    {
        return $this->role === 'admin';
    }

    public function getRoleBadgeClass()
    {
        $badges = [
            'admin' => 'badge-admin',
            'client' => 'badge-client',
        ];

        return $badges[$this->role] ?? 'badge-client';
    }
}
