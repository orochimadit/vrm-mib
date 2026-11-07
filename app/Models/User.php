<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $guarded = [];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin_mcm';
    }

    public function isInsurance(): bool
    {
        return $this->role === 'insurance';
    }

    public function isSurveyor(): bool
    {
        return $this->role === 'surveyor';
    }

    public function isShipowner(): bool
    {
        return $this->role === 'shipowner';
    }

    public function isBroker(): bool
    {
        return $this->role === 'broker';
    }

    public function getRoleLabelAttribute(): string
    {
        return match ($this->role) {
            'admin_mcm' => 'Admin MCM (Superadmin)',
            'insurance' => 'Insurance Partner (Underwriter)',
            'surveyor' => 'Marine Surveyor',
            'shipowner' => 'Ship Owner (Pemilik Kapal)',
            'broker' => 'Insurance Broker',
            default => ucfirst(str_replace('_', ' ', $this->role ?? 'User')),
        };
    }
}
