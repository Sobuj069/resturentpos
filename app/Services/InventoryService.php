<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Ingredient;
use App\Models\ItemRecipe;
use App\Models\StockLog;
use App\Models\Wastage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class InventoryService
{
    /**
     * Deduct raw ingredients for all items in an order based on BOM recipes
     */
    public function deductOrderIngredients(Order $order): void
    {
        DB::transaction(function () use ($order) {
            foreach ($order->items as $orderItem) {
                // Find recipes for this item (and variant if applicable)
                $recipes = ItemRecipe::where('item_id', $orderItem->item_id)
                    ->when($orderItem->variant_id, function ($q) use ($orderItem) {
                        $q->where(function ($sub) use ($orderItem) {
                            $sub->where('variant_id', $orderItem->variant_id)
                                ->orWhereNull('variant_id');
                        });
                    })
                    ->get();

                foreach ($recipes as $recipe) {
                    $qtyToDeduct = $recipe->quantity_required * $orderItem->quantity;
                    $ingredient = Ingredient::lockForUpdate()->find($recipe->ingredient_id);

                    if ($ingredient) {
                        $ingredient->current_stock = max(0, $ingredient->current_stock - $qtyToDeduct);
                        $ingredient->save();

                        StockLog::create([
                            'branch_id' => $order->branch_id,
                            'ingredient_id' => $ingredient->id,
                            'type' => 'order_deduction',
                            'quantity_change' => -$qtyToDeduct,
                            'balance_after' => $ingredient->current_stock,
                            'reference_id' => $order->id,
                            'notes' => "Order #{$order->order_number} ({$orderItem->quantity}x {$orderItem->item_name})",
                            'user_id' => $order->user_id,
                        ]);
                    }
                }
            }
        });
    }

    /**
     * Add new stock purchase
     */
    public function addStockPurchase(int $ingredientId, float $quantity, float $costPerUnit, ?int $branchId = null, ?int $userId = null): Ingredient
    {
        return DB::transaction(function () use ($ingredientId, $quantity, $costPerUnit, $branchId, $userId) {
            $ingredient = Ingredient::lockForUpdate()->findOrFail($ingredientId);
            $ingredient->current_stock += $quantity;
            $ingredient->cost_per_unit = $costPerUnit;
            $ingredient->save();

            StockLog::create([
                'branch_id' => $branchId ?? $ingredient->branch_id,
                'ingredient_id' => $ingredient->id,
                'type' => 'purchase',
                'quantity_change' => $quantity,
                'balance_after' => $ingredient->current_stock,
                'notes' => "Purchased {$quantity} {$ingredient->unit} @ ৳{$costPerUnit}/{$ingredient->unit}",
                'user_id' => $userId,
            ]);

            return $ingredient;
        });
    }

    /**
     * Record kitchen food waste
     */
    public function recordWastage(array $data): Wastage
    {
        return DB::transaction(function () use ($data) {
            $wastage = Wastage::create($data);

            if (!empty($data['ingredient_id'])) {
                $ingredient = Ingredient::lockForUpdate()->find($data['ingredient_id']);
                if ($ingredient) {
                    $ingredient->current_stock = max(0, $ingredient->current_stock - $data['quantity']);
                    $ingredient->save();

                    StockLog::create([
                        'branch_id' => $data['branch_id'] ?? $ingredient->branch_id,
                        'ingredient_id' => $ingredient->id,
                        'type' => 'wastage',
                        'quantity_change' => -$data['quantity'],
                        'balance_after' => $ingredient->current_stock,
                        'reference_id' => $wastage->id,
                        'notes' => "Wastage: " . ($data['reason'] ?? 'Kitchen Waste'),
                        'user_id' => $data['logged_by'] ?? null,
                    ]);
                }
            }

            return $wastage;
        });
    }
}
