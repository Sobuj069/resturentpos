<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DeliveryHubController extends Controller
{
    /**
     * Live Delivery Command Center UI
     */
    public function index(Request $request): View
    {
        $deliveryOrders = Order::where('order_type', 'delivery')
            ->with(['items.item', 'payments'])
            ->latest()
            ->paginate(20);

        $pendingCount = Order::where('order_type', 'delivery')->where('status', 'pending')->count();
        $cookingCount = Order::where('order_type', 'delivery')->where('status', 'cooking')->count();
        $readyCount = Order::where('order_type', 'delivery')->where('status', 'ready')->count();

        return view('delivery.index', compact('deliveryOrders', 'pendingCount', 'cookingCount', 'readyCount'));
    }

    /**
     * Update Delivery Order Status (Accept / Cooking / Out for Delivery / Delivered)
     */
    public function updateDeliveryStatus(Request $request, Order $order): JsonResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,cooking,ready,out_for_delivery,delivered,cancelled',
            'rider_name' => 'nullable|string',
            'rider_phone' => 'nullable|string',
        ]);

        $order->update([
            'status' => $validated['status'] === 'delivered' ? 'completed' : $validated['status'],
        ]);

        return response()->json([
            'success' => true,
            'message' => "ডেলিভারি অর্ডার #{$order->order_number} স্ট্যাটাস আপডেট হয়েছে!",
            'order' => $order,
        ]);
    }
}
