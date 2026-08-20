<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'total_spent' => 'float',
        'total_visits' => 'integer',
        'reward_points' => 'integer',
        'is_active' => 'boolean',
        'date_of_birth' => 'date',
    ];

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function loyaltyTransactions(): HasMany
    {
        return $this->hasMany(LoyaltyTransaction::class)->latest();
    }

    public function recalculateTier(): void
    {
        if ($this->total_spent >= 50000) {
            $this->membership_tier = 'platinum';
        } elseif ($this->total_spent >= 25000) {
            $this->membership_tier = 'gold';
        } elseif ($this->total_spent >= 10000) {
            $this->membership_tier = 'silver';
        } else {
            $this->membership_tier = 'bronze';
        }
        $this->save();
    }
}
