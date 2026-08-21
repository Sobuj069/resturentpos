<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ingredient extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'current_stock' => 'float',
        'alert_stock' => 'float',
        'cost_per_unit' => 'float',
        'is_active' => 'boolean',
    ];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function recipes(): HasMany
    {
        return $this->hasMany(ItemRecipe::class);
    }

    public function stockLogs(): HasMany
    {
        return $this->hasMany(StockLog::class);
    }

    public function isLowStock(): bool
    {
        return $this->current_stock <= $this->alert_stock;
    }

    public function getImageAttribute($value)
    {
        if (!empty($value)) {
            return $value;
        }
        $name = strtolower(($this->name ?? '') . ' ' . ($this->bangla_name ?? ''));
        if (str_contains($name, 'rice') || str_contains($name, 'চাল')) return '/images/ingredients/rice.jpg';
        if (str_contains($name, 'mutton') || str_contains($name, 'খাসি')) return '/images/ingredients/mutton.jpg';
        if (str_contains($name, 'chicken') || str_contains($name, 'মুরগি')) return '/images/ingredients/chicken.jpg';
        if (str_contains($name, 'beef') || str_contains($name, 'গরু')) return '/images/ingredients/beef.jpg';
        if (str_contains($name, 'ghee') || str_contains($name, 'ঘি')) return '/images/ingredients/ghee.jpg';
        if (str_contains($name, 'oil') || str_contains($name, 'তেল')) return '/images/ingredients/mustard_oil.jpg';
        if (str_contains($name, 'yogurt') || str_contains($name, 'দই')) return '/images/ingredients/yogurt.jpg';
        if (str_contains($name, 'flour') || str_contains($name, 'ময়দা')) return '/images/ingredients/flour.jpg';
        
        return '/images/ingredients/rice.jpg';
    }
}
