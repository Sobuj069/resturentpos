<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class RestaurantTable extends Model
{
    use HasFactory;

    protected $table = 'tables';

    protected $guarded = ['id'];

    protected $casts = [
        'capacity' => 'integer',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    protected static function booted(): void
    {
        static::creating(function ($table) {
            if (empty($table->qr_code_token)) {
                $table->qr_code_token = Str::random(16);
            }
        });
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function currentOrder(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'current_order_id');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'table_id');
    }

    public function activeOrders(): HasMany
    {
        return $this->hasMany(Order::class, 'table_id')
            ->where('payment_status', '!=', 'paid')
            ->where('status', '!=', 'cancelled')
            ->with(['items.modifiers', 'customer']);
    }

    public function getQrUrlAttribute(): string
    {
        return url('/order/table/' . ($this->qr_code_token ?: $this->id));
    }
}
