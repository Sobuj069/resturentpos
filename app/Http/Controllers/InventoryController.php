<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Ingredient;
use App\Models\Item;
use App\Models\ItemRecipe;
use App\Models\StockLog;
use App\Models\Wastage;
use App\Services\InventoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InventoryController extends Controller
{
    protected InventoryService $inventoryService;

    public function __construct(InventoryService $inventoryService)
    {
        $this->inventoryService = $inventoryService;
    }

    /**
     * Display inventory overview & stock dashboard
     */
    public function index(): View
    {
        $ingredients = Ingredient::with('recipes.item')
            ->orderBy('name')
            ->get();

        $stockLogs = StockLog::with(['ingredient', 'user'])
            ->latest('id')
            ->limit(30)
            ->get();

        $wastages = Wastage::with(['ingredient', 'item', 'user'])
            ->latest('id')
            ->limit(20)
            ->get();

        $items = Item::with(['variants', 'recipes.ingredient'])->get();

        $lowStockCount = $ingredients->filter->isLowStock()->count();
        $totalStockValue = $ingredients->sum(fn($i) => $i->current_stock * $i->cost_per_unit);

        return view('inventory.index', compact(
            'ingredients',
            'stockLogs',
            'wastages',
            'items',
            'lowStockCount',
            'totalStockValue'
        ));
    }

    /**
     * Store or Update Ingredient
     */
    public function storeIngredient(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'id' => 'nullable|exists:ingredients,id',
            'name' => 'required|string|max:255',
            'bangla_name' => 'nullable|string|max:255',
            'unit' => 'required|string|in:kg,gm,litre,ml,pcs,pkt',
            'current_stock' => 'required|numeric|min:0',
            'alert_stock' => 'required|numeric|min:0',
            'cost_per_unit' => 'required|numeric|min:0',
        ]);

        $ingredient = Ingredient::updateOrCreate(
            ['id' => $validated['id'] ?? null],
            [
                'branch_id' => Branch::first()->id ?? null,
                'name' => $validated['name'],
                'bangla_name' => $validated['bangla_name'] ?? null,
                'unit' => $validated['unit'],
                'current_stock' => $validated['current_stock'],
                'alert_stock' => $validated['alert_stock'],
                'cost_per_unit' => $validated['cost_per_unit'],
            ]
        );

        return response()->json([
            'success' => true,
            'message' => "কাঁচামাল '{$ingredient->name}' সফলভাবে সংরক্ষিত হয়েছে!",
            'ingredient' => $ingredient,
        ]);
    }

    /**
     * Delete Ingredient
     */
    public function deleteIngredient(Ingredient $ingredient): JsonResponse
    {
        $ingredient->delete();
        return response()->json([
            'success' => true,
            'message' => 'উপাদানটি মুছে ফেলা হয়েছে!',
        ]);
    }

    /**
     * Add raw stock purchase (GRN)
     */
    public function addPurchase(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'ingredient_id' => 'required|exists:ingredients,id',
            'quantity' => 'required|numeric|min:0.01',
            'cost_per_unit' => 'required|numeric|min:0',
        ]);

        $ingredient = $this->inventoryService->addStockPurchase(
            $validated['ingredient_id'],
            (float)$validated['quantity'],
            (float)$validated['cost_per_unit'],
            Branch::first()->id ?? null,
            auth()->id() ?? 1
        );

        return response()->json([
            'success' => true,
            'message' => "Stock added successfully! New balance: {$ingredient->current_stock} {$ingredient->unit}",
            'ingredient' => $ingredient,
        ]);
    }

    /**
     * Save/Update BOM Recipe for a Menu Item
     */
    public function saveRecipe(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'item_id' => 'required|exists:items,id',
            'variant_id' => 'nullable|exists:item_variants,id',
            'recipes' => 'required|array',
            'recipes.*.ingredient_id' => 'required|exists:ingredients,id',
            'recipes.*.quantity_required' => 'required|numeric|min:0.0001',
        ]);

        // Clear existing recipe mapping
        ItemRecipe::where('item_id', $validated['item_id'])
            ->where('variant_id', $validated['variant_id'])
            ->delete();

        foreach ($validated['recipes'] as $rec) {
            ItemRecipe::create([
                'item_id' => $validated['item_id'],
                'variant_id' => $validated['variant_id'],
                'ingredient_id' => $rec['ingredient_id'],
                'quantity_required' => $rec['quantity_required'],
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Recipe Bill of Materials (BOM) updated successfully!',
        ]);
    }

    /**
     * Record kitchen waste
     */
    public function recordWastage(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'ingredient_id' => 'nullable|exists:ingredients,id',
            'item_id' => 'nullable|exists:items,id',
            'quantity' => 'required|numeric|min:0.01',
            'unit' => 'required|string',
            'cost_impact' => 'required|numeric|min:0',
            'reason' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        $wastage = $this->inventoryService->recordWastage([
            'branch_id' => Branch::first()->id ?? null,
            'logged_by' => auth()->id() ?? 1,
            ...$validated,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Wastage recorded successfully!',
            'wastage' => $wastage,
        ]);
    }
}
