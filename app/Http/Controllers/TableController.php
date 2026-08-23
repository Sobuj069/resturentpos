<?php

namespace App\Http\Controllers;

use App\Models\RestaurantTable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TableController extends Controller
{
    /**
     * Display Floor & Table Management
     */
    public function index(Request $request): View
    {
        $tables = RestaurantTable::with(['currentOrder.items.modifiers', 'currentOrder.customer'])
            ->orderBy('floor_name')
            ->orderBy('sort_order')
            ->get();

        $floors = RestaurantTable::select('floor_name')
            ->distinct()
            ->pluck('floor_name')
            ->filter()
            ->values();

        if ($floors->isEmpty()) {
            $floors = collect(['Ground Floor', '1st Floor', 'Rooftop Lounge', 'VIP Zone']);
        }

        return view('tables.index', compact('tables', 'floors'));
    }

    /**
     * Store or Update Table
     */
    public function storeTable(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'id' => 'nullable|exists:tables,id',
            'name' => 'required|string|max:50',
            'floor_name' => 'required|string|max:50',
            'capacity' => 'required|integer|min:1|max:50',
            'shape' => 'nullable|string|in:square,round,rectangle',
            'status' => 'nullable|string|in:available,occupied,billed,cleaning',
            'sort_order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        $table = RestaurantTable::updateOrCreate(
            ['id' => $validated['id'] ?? null],
            [
                'name' => $validated['name'],
                'floor_name' => $validated['floor_name'],
                'capacity' => $validated['capacity'],
                'shape' => $validated['shape'] ?? 'square',
                'status' => $validated['status'] ?? 'available',
                'sort_order' => $validated['sort_order'] ?? 0,
                'is_active' => $request->boolean('is_active', true),
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'টেবিল সফলভাবে সংরক্ষিত হয়েছে!',
            'table' => $table,
        ]);
    }

    /**
     * Get live table statuses for floor management & live floor stats
     */
    public function getLiveStatus(Request $request): JsonResponse
    {
        $tables = RestaurantTable::where('is_active', true)
            ->with(['currentOrder.items.modifiers', 'currentOrder.customer'])
            ->orderBy('sort_order')
            ->get();

        return response()->json([
            'success' => true,
            'tables' => $tables,
            'timestamp' => now()->timestamp,
        ]);
    }

    /**
     * Update Table Live Status
     */
    public function updateStatus(Request $request, RestaurantTable $table): JsonResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:available,occupied,billed,cleaning',
        ]);

        $table->update(['status' => $validated['status']]);

        $freshTables = RestaurantTable::where('is_active', true)
            ->with(['currentOrder.items.modifiers', 'currentOrder.customer'])
            ->orderBy('sort_order')
            ->get();

        return response()->json([
            'success' => true,
            'message' => "টেবিল {$table->name} এর স্ট্যাটাস পরিবর্তন হয়েছে!",
            'table' => $table,
            'tables' => $freshTables,
        ]);
    }

    /**
     * Delete Table
     */
    public function deleteTable(RestaurantTable $table): JsonResponse
    {
        $table->delete();
        return response()->json([
            'success' => true,
            'message' => 'টেবিল মুছে ফেলা হয়েছে!',
        ]);
    }
}
