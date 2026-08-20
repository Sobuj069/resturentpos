<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Category;
use App\Models\Order;
use App\Models\RestaurantTable;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class WaiterController extends Controller
{
    /**
     * Handheld Captain / Waiter Terminal UI
     */
    public function index(Request $request): View
    {
        $tables = RestaurantTable::where('is_active', true)
            ->with(['currentOrder.items.item'])
            ->orderBy('floor_name')
            ->orderBy('sort_order')
            ->get();

        $categories = Category::where('is_active', true)
            ->with(['items' => function ($q) {
                $q->where('is_available', true)->with(['variants', 'modifiers']);
            }])
            ->orderBy('sort_order')
            ->get();

        $waiters = User::whereIn('role', ['waiter', 'captain', 'manager', 'cashier'])->where('is_active', true)->get();

        return view('waiter.index', compact('tables', 'categories', 'waiters'));
    }

    /**
     * Transfer Order from Table A to Table B
     */
    public function transferTable(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'source_table_id' => 'required|exists:tables,id',
            'target_table_id' => 'required|exists:tables,id|different:source_table_id',
        ]);

        return DB::transaction(function () use ($validated) {
            $source = RestaurantTable::findOrFail($validated['source_table_id']);
            $target = RestaurantTable::findOrFail($validated['target_table_id']);

            if (!$source->current_order_id) {
                return response()->json(['success' => false, 'message' => 'উৎস টেবিলে কোনো সক্রিয় অর্ডার নেই!'], 422);
            }

            $order = Order::findOrFail($source->current_order_id);

            // Update order's table reference
            $order->update(['table_id' => $target->id]);

            // If target already has an order, merge or replace
            $target->update([
                'status' => 'occupied',
                'current_order_id' => $order->id,
            ]);

            // Release source table
            $source->update([
                'status' => 'available',
                'current_order_id' => null,
            ]);

            return response()->json([
                'success' => true,
                'message' => "অর্ডার সফলভাবে {$source->name} থেকে {$target->name} টেবিলে ট্রান্সফার করা হয়েছে!",
            ]);
        });
    }
}
