<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tenant extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'is_active' => 'boolean',
        'trial_ends_at' => 'datetime',
        'subscription_expires_at' => 'datetime',
        'features' => 'array',
        'monthly_fee' => 'decimal:2',
    ];

    public function branches(): HasMany
    {
        return $this->hasMany(Branch::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function isSubscribed(): bool
    {
        return in_array($this->subscription_status, ['active', 'trial']) &&
               ($this->subscription_expires_at === null || $this->subscription_expires_at->isFuture());
    }
}
