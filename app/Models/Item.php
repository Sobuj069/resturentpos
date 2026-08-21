<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Item extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'cost_price' => 'float',
        'selling_price' => 'float',
        'vat_percent' => 'float',
        'sd_percent' => 'float',
        'has_variants' => 'boolean',
        'is_available' => 'boolean',
        'is_featured' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ItemVariant::class);
    }

    public function modifiers(): BelongsToMany
    {
        return $this->belongsToMany(Modifier::class, 'item_modifier');
    }

    public function recipes(): HasMany
    {
        return $this->hasMany(ItemRecipe::class);
    }

    public function getImageAttribute($value)
    {
        if (!empty($value)) {
            return $value;
        }
        $name = strtolower(($this->name ?? '') . ' ' . ($this->bangla_name ?? ''));
        if (str_contains($name, 'kacchi') || str_contains($name, 'কাচ্চি')) return '/images/food/kacchi.jpg';
        if (str_contains($name, 'tehari') || str_contains($name, 'তেহারী')) return '/images/food/tehari.jpg';
        if (str_contains($name, 'polao') || str_contains($name, 'roast') || str_contains($name, 'পোলাও') || str_contains($name, 'রোস্ট')) return '/images/food/morog_polao.jpg';
        if (str_contains($name, 'kebab') || str_contains($name, 'কাবাব') || str_contains($name, 'grill')) return '/images/food/reshmi_kebab.jpg';
        if (str_contains($name, 'kala bhuna') || str_contains($name, 'কালা ভুনা') || str_contains($name, 'beef') || str_contains($name, 'বিফ')) return '/images/food/kala_bhuna.jpg';
        if (str_contains($name, 'butter naan') || str_contains($name, 'বাটার নান')) return '/images/food/butter_naan.jpg';
        if (str_contains($name, 'garlic naan') || str_contains($name, 'গার্লিক নান') || str_contains($name, 'naan') || str_contains($name, 'নান')) return '/images/food/garlic_naan.jpg';
        if (str_contains($name, 'borhani') || str_contains($name, 'বোরহানি')) return '/images/food/borhani.jpg';
        if (str_contains($name, 'firni') || str_contains($name, 'ফিরনি') || str_contains($name, 'dessert')) return '/images/food/firni.jpg';
        
        return '/images/food/kacchi.jpg';
    }
}
