<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Ingredient;
use App\Models\Item;
use App\Models\ItemRecipe;
use App\Models\ItemVariant;
use App\Models\Modifier;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class MenuController extends Controller
{
    /**
     * Display Menu Management Interface
     */
    public function index(Request $request): View
    {
        $categories = Category::with(['items' => function ($q) {
            $q->with(['variants', 'modifiers', 'recipes.ingredient']);
        }])->orderBy('sort_order')->get();

        $allModifiers = Modifier::orderBy('name')->get();
        $allIngredients = Ingredient::orderBy('name')->get();

        return view('menu.index', compact('categories', 'allModifiers', 'allIngredients'));
    }

    /**
     * Store or Update Category
     */
    public function storeCategory(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'id' => 'nullable|exists:categories,id',
            'name' => 'required|string|max:100',
            'bangla_name' => 'nullable|string|max:100',
            'icon' => 'nullable|string|max:50',
            'sort_order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        $category = Category::updateOrCreate(
            ['id' => $validated['id'] ?? null],
            [
                'name' => $validated['name'],
                'bangla_name' => $validated['bangla_name'] ?? null,
                'icon' => $validated['icon'] ?? 'utensils',
                'sort_order' => $validated['sort_order'] ?? 0,
                'is_active' => $request->boolean('is_active', true),
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'ক্যাটাগরি সফলভাবে সংরক্ষিত হয়েছে!',
            'category' => $category,
        ]);
    }

    /**
     * Delete Category
     */
    public function deleteCategory(Category $category): JsonResponse
    {
        $category->delete();
        return response()->json([
            'success' => true,
            'message' => 'ক্যাটাগরি মুছে ফেলা হয়েছে!',
        ]);
    }

    /**
     * Store or Update Item
     */
    public function storeItem(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'id' => 'nullable|exists:items,id',
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:150',
            'bangla_name' => 'nullable|string|max:150',
            'sku' => 'nullable|string|max:50',
            'barcode' => 'nullable|string|max:50',
            'cost_price' => 'nullable|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'vat_percent' => 'nullable|numeric|min:0|max:100',
            'kitchen_station' => 'required|string|in:main_kitchen,grill,drinks_bar,dessert',
            'is_available' => 'boolean',
            'has_variants' => 'boolean',
            'variants' => 'nullable|array',
            'variants.*.name' => 'required_with:variants|string',
            'variants.*.price' => 'required_with:variants|numeric|min:0',
            'variants.*.cost_price' => 'nullable|numeric|min:0',
            'modifier_ids' => 'nullable|array',
            'modifier_ids.*' => 'exists:modifiers,id',
        ]);

        return DB::transaction(function () use ($validated, $request) {
            $hasVariants = $request->boolean('has_variants', false);

            $item = Item::updateOrCreate(
                ['id' => $validated['id'] ?? null],
                [
                    'category_id' => $validated['category_id'],
                    'name' => $validated['name'],
                    'bangla_name' => $validated['bangla_name'] ?? null,
                    'sku' => $validated['sku'] ?: strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $validated['name']), 0, 3)) . '-' . rand(100, 999),
                    'barcode' => $validated['barcode'] ?? null,
                    'cost_price' => $validated['cost_price'] ?? 0,
                    'selling_price' => $validated['selling_price'],
                    'vat_percent' => $validated['vat_percent'] ?? 5.00,
                    'kitchen_station' => $validated['kitchen_station'],
                    'has_variants' => $hasVariants,
                    'is_available' => $request->boolean('is_available', true),
                ]
            );

            // Handle variants
            if ($hasVariants && !empty($validated['variants'])) {
                // Delete old variants not in list or recreate
                $item->variants()->delete();
                foreach ($validated['variants'] as $v) {
                    if (!empty($v['name']) && isset($v['price'])) {
                        $item->variants()->create([
                            'name' => $v['name'],
                            'price' => $v['price'],
                            'cost_price' => $v['cost_price'] ?? 0,
                            'is_available' => true,
                        ]);
                    }
                }
            } else {
                $item->variants()->delete();
            }

            // Handle modifiers
            if (isset($validated['modifier_ids'])) {
                $item->modifiers()->sync($validated['modifier_ids']);
            }

            return response()->json([
                'success' => true,
                'message' => 'মেনু আইটেম সফলভাবে সংরক্ষিত হয়েছে!',
                'item' => $item->load(['variants', 'modifiers']),
            ]);
        });
    }

    /**
     * Delete Item
     */
    public function deleteItem(Item $item): JsonResponse
    {
        $item->delete();
        return response()->json([
            'success' => true,
            'message' => 'আইটেম মুছে ফেলা হয়েছে!',
        ]);
    }

    /**
     * Store or Update Modifier
     */
    public function storeModifier(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'id' => 'nullable|exists:modifiers,id',
            'name' => 'required|string|max:100',
            'bangla_name' => 'nullable|string|max:100',
            'price' => 'required|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        $modifier = Modifier::updateOrCreate(
            ['id' => $validated['id'] ?? null],
            [
                'name' => $validated['name'],
                'bangla_name' => $validated['bangla_name'] ?? null,
                'price' => $validated['price'],
                'is_active' => $request->boolean('is_active', true),
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'মডিফায়ার সংরক্ষিত হয়েছে!',
            'modifier' => $modifier,
        ]);
    }

    /**
     * Delete Modifier
     */
    public function deleteModifier(Modifier $modifier): JsonResponse
    {
        $modifier->delete();
        return response()->json([
            'success' => true,
            'message' => 'মডিফায়ার মুছে ফেলা হয়েছে!',
        ]);
    }
}
