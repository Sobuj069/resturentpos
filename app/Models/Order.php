<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'subtotal' => 'float',
        'discount_value' => 'float',
        'discount_amount' => 'float',
        'vat_percent' => 'float',
        'vat_amount' => 'float',
        'sd_amount' => 'float',
        'service_charge' => 'float',
        'grand_total' => 'float',
        'paid_amount' => 'float',
        'change_amount' => 'float',
        'is_synced' => 'boolean',
        'points_earned' => 'integer',
        'points_redeemed' => 'integer',
        'billed_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function table(): BelongsTo
    {
        return $this->belongsTo(RestaurantTable::class, 'table_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function waiter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'waiter_id');
    }

    public function shift(): BelongsTo
    {
        return $this->belongsTo(Shift::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public static function generateOrderNumber(): string
    {
        $date = now()->format('Ymd');
        $lastOrder = self::whereDate('created_at', now()->today())->latest('id')->first();
        $seq = $lastOrder ? ($lastOrder->id % 1000) + 1 : 1;
        return 'ORD-' . $date . '-' . str_pad($seq, 4, '0', STR_PAD_LEFT);
    }

    public static function generateMushakNumber(Branch $branch): string
    {
        $year = now()->format('Y');
        $count = self::whereYear('created_at', $year)->whereNotNull('mushak_number')->count() + 1;
        return 'M6.3-' . $branch->code . '-' . $year . '-' . str_pad($count, 6, '0', STR_PAD_LEFT);
    }
}
