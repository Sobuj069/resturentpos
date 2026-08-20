<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Item;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\InventoryService;
use App\Services\MushakService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DeliveryWebhookController extends Controller
{
    protected InventoryService $inventoryService;
    protected MushakService $mushakService;

    public function __construct(InventoryService $inventoryService, MushakService $mushakService)
    {
        $this->inventoryService = $inventoryService;
        $this->mushakService = $mushakService;
    }

    /**
     * Handle Foodpanda Delivery Webhook
     */
    public function handleFoodpanda(Request $request): JsonResponse
    {
        $payload = $request->all();
        Log::info('Foodpanda Webhook received:', $payload);

        $branch = Branch::first();
        $orderNumber = 'FP-' . ($payload['order_id'] ?? rand(10000, 99999));

        $order = DB::transaction(function () use ($payload, $branch, $orderNumber) {
            $subtotal = 0;
            $itemsData = [];

            foreach ($payload['items'] ?? [] as $it) {
                $item = Item::where('name', 'LIKE', '%' . ($it['name'] ?? '') . '%')->first() ?? Item::first();
                $qty = (int)($it['quantity'] ?? 1);
                $price = (float)($it['price'] ?? $item->selling_price);
                $lineSubtotal = $price * $qty;
                $subtotal += $lineSubtotal;

                $itemsData[] = [
                    'item' => $item,
                    'qty' => $qty,
                    'price' => $price,
                    'subtotal' => $lineSubtotal,
                    'notes' => $it['notes'] ?? null,
                ];
            }

            if (empty($itemsData)) {
                $item = Item::first();
                $itemsData[] = ['item' => $item, 'qty' => 1, 'price' => $item->selling_price, 'subtotal' => $item->selling_price, 'notes' => 'Foodpanda Order'];
                $subtotal = $item->selling_price;
            }

            $vatPercent = $branch->default_vat_rate ?? 5.0;
            $vatAmount = ($subtotal * $vatPercent) / 100;
            $grandTotal = $subtotal + $vatAmount;

            $order = Order::create([
                'branch_id' => $branch->id,
                'order_number' => $orderNumber,
                'mushak_number' => Order::generateMushakNumber($branch),
                'order_type' => 'delivery',
                'token_number' => 'FP' . substr($orderNumber, -3),
                'customer_name' => $payload['customer_name'] ?? 'Foodpanda Customer',
                'customer_phone' => $payload['customer_phone'] ?? null,
                'customer_address' => $payload['delivery_address'] ?? 'Foodpanda Delivery',
                'subtotal' => $subtotal,
                'vat_percent' => $vatPercent,
                'vat_amount' => $vatAmount,
                'grand_total' => $grandTotal,
                'paid_amount' => $grandTotal,
                'payment_status' => 'paid',
                'payment_method' => 'foodpanda',
                'status' => 'cooking',
                'notes' => 'Foodpanda Online Order',
                'billed_at' => now(),
            ]);

            foreach ($itemsData as $data) {
                $item = $data['item'];
                OrderItem::create([
                    'order_id' => $order->id,
                    'item_id' => $item->id,
                    'item_name' => $item->name,
                    'unit_price' => $data['price'],
                    'quantity' => $data['qty'],
                    'subtotal' => $data['subtotal'],
                    'vat_amount' => ($data['subtotal'] * $vatPercent) / 100,
                    'total_price' => $data['subtotal'] * (1 + $vatPercent / 100),
                    'notes' => $data['notes'],
                    'kitchen_station' => $item->kitchen_station ?? 'main_kitchen',
                    'kitchen_status' => 'pending',
                ]);
            }

            $this->inventoryService->deductOrderIngredients($order);
            return $order;
        });

        return response()->json([
            'status' => 'acknowledged',
            'order_id' => $order->id,
            'order_number' => $order->order_number,
        ]);
    }

    /**
     * Handle Pathao Food Delivery Webhook
     */
    public function handlePathao(Request $request): JsonResponse
    {
        $payload = $request->all();
        Log::info('Pathao Food Webhook received:', $payload);

        $branch = Branch::first();
        $orderNumber = 'PTH-' . ($payload['order_id'] ?? rand(10000, 99999));

        $order = DB::transaction(function () use ($payload, $branch, $orderNumber) {
            $item = Item::first();
            $subtotal = $item->selling_price;
            $vatPercent = $branch->default_vat_rate ?? 5.0;
            $vatAmount = ($subtotal * $vatPercent) / 100;
            $grandTotal = $subtotal + $vatAmount;

            $order = Order::create([
                'branch_id' => $branch->id,
                'order_number' => $orderNumber,
                'mushak_number' => Order::generateMushakNumber($branch),
                'order_type' => 'delivery',
                'token_number' => 'PT' . substr($orderNumber, -3),
                'customer_name' => $payload['customer_name'] ?? 'Pathao Guest',
                'customer_phone' => $payload['customer_phone'] ?? null,
                'customer_address' => $payload['delivery_address'] ?? 'Pathao Delivery',
                'subtotal' => $subtotal,
                'vat_percent' => $vatPercent,
                'vat_amount' => $vatAmount,
                'grand_total' => $grandTotal,
                'paid_amount' => $grandTotal,
                'payment_status' => 'paid',
                'payment_method' => 'pathao_food',
                'status' => 'cooking',
                'notes' => 'Pathao Food Delivery Order',
                'billed_at' => now(),
            ]);

            OrderItem::create([
                'order_id' => $order->id,
                'item_id' => $item->id,
                'item_name' => $item->name,
                'unit_price' => $item->selling_price,
                'quantity' => 1,
                'subtotal' => $subtotal,
                'vat_amount' => $vatAmount,
                'total_price' => $grandTotal,
                'kitchen_station' => $item->kitchen_station ?? 'main_kitchen',
                'kitchen_status' => 'pending',
            ]);

            $this->inventoryService->deductOrderIngredients($order);
            return $order;
        });

        return response()->json([
            'status' => 'acknowledged',
            'order_id' => $order->id,
            'order_number' => $order->order_number,
        ]);
    }

    /**
     * Offline Sync: Receive batched orders created while offline in IndexedDB
     */
    public function batchSyncOfflineOrders(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'orders' => 'required|array',
        ]);

        $syncedIds = [];
        $branch = Branch::first();

        foreach ($validated['orders'] as $offlineOrder) {
            if (empty($offlineOrder['offline_uuid'])) continue;

            $exists = Order::where('offline_uuid', $offlineOrder['offline_uuid'])->first();
            if ($exists) {
                $syncedIds[] = $offlineOrder['offline_uuid'];
                continue;
            }

            try {
                DB::transaction(function () use ($offlineOrder, $branch, &$syncedIds) {
                    $order = Order::create([
                        'branch_id' => $branch->id,
                        'order_number' => $offlineOrder['order_number'] ?? Order::generateOrderNumber(),
                        'mushak_number' => Order::generateMushakNumber($branch),
                        'order_type' => $offlineOrder['order_type'] ?? 'takeaway',
                        'customer_name' => $offlineOrder['customer_name'] ?? 'Offline Guest',
                        'subtotal' => $offlineOrder['subtotal'] ?? 0,
                        'vat_percent' => $offlineOrder['vat_percent'] ?? 5.0,
                        'vat_amount' => $offlineOrder['vat_amount'] ?? 0,
                        'grand_total' => $offlineOrder['grand_total'] ?? 0,
                        'paid_amount' => $offlineOrder['paid_amount'] ?? 0,
                        'payment_status' => $offlineOrder['payment_status'] ?? 'paid',
                        'payment_method' => $offlineOrder['payment_method'] ?? 'cash',
                        'status' => 'completed',
                        'offline_uuid' => $offlineOrder['offline_uuid'],
                        'is_synced' => true,
                        'created_at' => $offlineOrder['created_at'] ?? now(),
                    ]);

                    if (!empty($offlineOrder['items'])) {
                        foreach ($offlineOrder['items'] as $it) {
                            OrderItem::create([
                                'order_id' => $order->id,
                                'item_id' => $it['item_id'],
                                'item_name' => $it['name'] ?? 'Item',
                                'unit_price' => $it['unit_price'] ?? 0,
                                'quantity' => $it['quantity'] ?? 1,
                                'subtotal' => $it['subtotal'] ?? 0,
                                'total_price' => $it['line_total'] ?? 0,
                                'kitchen_status' => 'served',
                            ]);
                        }
                    }

                    $this->inventoryService->deductOrderIngredients($order);
                    $syncedIds[] = $offlineOrder['offline_uuid'];
                });
            } catch (\Throwable $e) {
                Log::error('Offline batch sync error:', ['message' => $e->getMessage()]);
            }
        }

        return response()->json([
            'success' => true,
            'synced_uuids' => $syncedIds,
            'message' => count($syncedIds) . ' offline orders synchronized successfully!',
        ]);
    }
}
