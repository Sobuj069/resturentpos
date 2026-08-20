<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KdsController extends Controller
{
    /**
     * Display the Kitchen Display Screen
     */
    public function index(): View
    {
        return view('kds.index');
    }

    /**
     * Get active kitchen orders and tickets (for live polling or SSE)
     */
    public function getActiveTickets(Request $request): JsonResponse
    {
        $station = $request->query('station', 'all');

        $orders = Order::whereIn('status', ['pending', 'cooking', 'ready'])
            ->with(['items' => function ($q) use ($station) {
                $q->when($station !== 'all', function ($sub) use ($station) {
                    $sub->where('kitchen_station', $station);
                })->with('modifiers');
            }, 'table', 'waiter'])
            ->orderBy('created_at', 'asc')
            ->get();

        // Calculate elapsed minutes and urgency badge for each order
        $tickets = $orders->map(function ($order) {
            $elapsedSeconds = abs($order->created_at->diffInSeconds(now()));
            $elapsedMinutes = floor($elapsedSeconds / 60);
            $elapsedRemainder = $elapsedSeconds % 60;

            // Urgency: green (<5m), yellow (5-12m), red (>12m)
            $urgency = 'normal';
            if ($elapsedMinutes >= 12) {
                $urgency = 'critical';
            } elseif ($elapsedMinutes >= 5) {
                $urgency = 'warning';
            }

            return [
                'id' => $order->id,
                'order_number' => $order->order_number,
                'token_number' => $order->token_number,
                'order_type' => $order->order_type,
                'table_name' => $order->table ? $order->table->name : null,
                'waiter_name' => $order->waiter ? $order->waiter->name : null,
                'status' => $order->status,
                'notes' => $order->notes,
                'created_at' => $order->created_at->format('h:i A'),
                'created_timestamp' => $order->created_at->timestamp,
                'elapsed_minutes' => $elapsedMinutes,
                'elapsed_formatted' => sprintf('%02d:%02d', $elapsedMinutes, $elapsedRemainder),
                'urgency' => $urgency,
                'items' => $order->items->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'name' => $item->item_name,
                        'variant' => $item->variant_name,
                        'quantity' => $item->quantity,
                        'notes' => $item->notes,
                        'station' => $item->kitchen_station,
                        'kitchen_status' => $item->kitchen_status,
                        'modifiers' => $item->modifiers->pluck('name'),
                    ];
                }),
            ];
        });

        return response()->json([
            'success' => true,
            'tickets' => $tickets,
            'server_time' => now()->format('h:i:s A'),
        ]);
    }

    /**
     * Bump item kitchen status (pending -> cooking -> ready -> served)
     */
    public function updateItemStatus(Request $request, OrderItem $item): JsonResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,cooking,ready,served',
        ]);

        $item->kitchen_status = $validated['status'];
        if ($validated['status'] === 'cooking' && !$item->started_at) {
            $item->started_at = now();
        } elseif ($validated['status'] === 'ready') {
            $item->ready_at = now();
        } elseif ($validated['status'] === 'served') {
            $item->served_at = now();
        }
        $item->save();

        // Check if all items in order are ready/served
        $order = $item->order;
        $allReady = $order->items()->whereNotIn('kitchen_status', ['ready', 'served'])->count() === 0;
        if ($allReady && $order->status !== 'completed') {
            $order->status = 'ready';
            $order->save();
        }

        return response()->json([
            'success' => true,
            'item' => $item,
            'order_status' => $order->status,
        ]);
    }

    /**
     * Bump entire order ticket to Ready / Served
     */
    public function bumpOrder(Request $request, Order $order): JsonResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:cooking,ready,served,completed',
        ]);

        $status = $validated['status'];
        $itemStatus = $status === 'completed' ? 'served' : $status;

        $order->status = $status;
        $order->save();

        $order->items()->update(['kitchen_status' => $itemStatus]);

        return response()->json([
            'success' => true,
            'message' => "Order #{$order->order_number} bumped to {$status}!",
        ]);
    }
}
