<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Item;
use App\Models\ItemVariant;
use App\Models\Modifier;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderItemModifier;
use App\Models\Payment;
use App\Models\RestaurantTable;
use App\Models\Shift;
use App\Models\User;
use App\Services\InventoryService;
use App\Services\MushakService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PosController extends Controller
{
    protected InventoryService $inventoryService;
    protected MushakService $mushakService;

    public function __construct(InventoryService $inventoryService, MushakService $mushakService)
    {
        $this->inventoryService = $inventoryService;
        $this->mushakService = $mushakService;
    }

    /**
     * Display the high-speed POS terminal UI
     */
    public function index(Request $request): View
    {
        $branch = Branch::first();
        $categories = Category::where('is_active', true)
            ->with(['items' => function ($q) {
                $q->where('is_available', true)->with(['variants', 'modifiers']);
            }])
            ->orderBy('sort_order')
            ->get();

        $tables = RestaurantTable::where('is_active', true)
            ->with(['currentOrder.items.modifiers', 'currentOrder.customer', 'activeOrders.items.modifiers', 'activeOrders.customer'])
            ->orderBy('sort_order')
            ->get();

        $activeShift = Shift::where('status', 'open')->latest()->first();
        $waiters = User::whereIn('role', ['waiter', 'cashier', 'manager'])->where('is_active', true)->get();

        return view('pos.index', compact('branch', 'categories', 'tables', 'activeShift', 'waiters'));
    }

    /**
     * Get real-time POS state as JSON
     */
    public function getCatalog(Request $request): JsonResponse
    {
        $branch = Branch::first();
        $categories = Category::where('is_active', true)
            ->with(['items' => function ($q) {
                $q->where('is_available', true)->with(['variants', 'modifiers']);
            }])
            ->orderBy('sort_order')
            ->get();

        $tables = RestaurantTable::where('is_active', true)
            ->with(['currentOrder.items.modifiers', 'currentOrder.customer', 'activeOrders.items.modifiers', 'activeOrders.customer'])
            ->orderBy('sort_order')
            ->get();

        $activeShift = Shift::where('status', 'open')->latest()->first();

        return response()->json([
            'success' => true,
            'branch' => $branch,
            'categories' => $categories,
            'tables' => $tables,
            'shift' => $activeShift,
        ]);
    }

    /**
     * Get lightweight live table statuses for real-time instant syncing
     */
    public function getLiveTables(Request $request): JsonResponse
    {
        $tables = RestaurantTable::where('is_active', true)
            ->with(['currentOrder.items.modifiers', 'currentOrder.customer', 'activeOrders.items.modifiers', 'activeOrders.customer'])
            ->orderBy('sort_order')
            ->get();

        return response()->json([
            'success' => true,
            'tables' => $tables,
            'timestamp' => now()->timestamp,
        ]);
    }

    /**
     * Store new Order (KOT / Save / Pay)
     */
    public function storeOrder(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'order_id' => 'nullable|exists:orders,id',
                'order_type' => 'required|in:dine_in,takeaway,delivery',
                'table_id' => 'nullable|exists:tables,id',
                'token_number' => 'nullable|string',
                'customer_name' => 'nullable|string',
                'customer_phone' => 'nullable|string',
                'customer_address' => 'nullable|string',
                'items' => 'required|array|min:1',
                'items.*.item_id' => 'required|exists:items,id',
                'items.*.variant_id' => 'nullable|exists:item_variants,id',
                'items.*.quantity' => 'required|integer|min:1',
                'items.*.unit_price' => 'nullable|numeric|min:0',
                'items.*.notes' => 'nullable|string',
                'items.*.modifiers' => 'nullable|array',
                'items.*.is_existing' => 'nullable|boolean',
                'discount_type' => 'nullable|in:fixed,percentage',
                'discount_value' => 'nullable|numeric|min:0',
                'redeemed_points' => 'nullable|integer|min:0',
                'vat_percent' => 'nullable|numeric|min:0',
                'service_charge' => 'nullable|numeric|min:0',
                'payment_status' => 'nullable|in:unpaid,paid,partial',
                'payment_method' => 'nullable|string',
                'paid_amount' => 'nullable|numeric|min:0',
                'payments' => 'nullable|array',
                'notes' => 'nullable|string',
                'offline_uuid' => 'nullable|string',
                'waiter_id' => 'nullable|exists:users,id',
            ]);

            $branch = Branch::first();
            $activeShift = Shift::where('status', 'open')->latest()->first();

            // Calculate totals
            $subtotal = 0;
            $orderItemsData = [];

            foreach ($validated['items'] as $itemData) {
                $item = Item::find($itemData['item_id']);
                if (!$item) continue;
                
                $variant = !empty($itemData['variant_id']) ? ItemVariant::find($itemData['variant_id']) : null;
                $unitPrice = isset($itemData['unit_price']) && is_numeric($itemData['unit_price'])
                    ? (float)$itemData['unit_price']
                    : ($variant ? (float)$variant->price : (float)$item->selling_price);
                
                $qty = max(1, (int)($itemData['quantity'] ?? 1));
                $itemSubtotal = $unitPrice * $qty;

            // Add modifiers price if any
            $modTotal = 0;
            if (!empty($itemData['modifiers'])) {
                foreach ($itemData['modifiers'] as $modId) {
                    $mod = Modifier::find($modId);
                    if ($mod) {
                        $modTotal += ($mod->price * $qty);
                    }
                }
            }

            $lineTotal = $itemSubtotal + $modTotal;
            $subtotal += $lineTotal;

            $orderItemsData[] = [
                'item' => $item,
                'variant_id' => $itemData['variant_id'] ?? null,
                'unit_price' => $unitPrice,
                'quantity' => $qty,
                'subtotal' => $lineTotal,
                'notes' => $itemData['notes'] ?? null,
                'modifiers' => $itemData['modifiers'] ?? [],
                'is_existing' => $itemData['is_existing'] ?? false,
            ];
        }

        // Discount calculation
        $discountType = $validated['discount_type'] ?? null;
        $discountValue = (float)($validated['discount_value'] ?? 0);
        $discountAmount = 0;

        if ($discountType === 'percentage') {
            $discountAmount = ($subtotal * $discountValue) / 100;
        } elseif ($discountType === 'fixed') {
            $discountAmount = min($subtotal, $discountValue);
        }

        $taxableAmount = max(0, $subtotal - $discountAmount);
        $vatPercent = isset($validated['vat_percent']) ? (float)$validated['vat_percent'] : ($branch->default_vat_rate ?? 5.00);
        $vatAmount = ($taxableAmount * $vatPercent) / 100;
        $serviceCharge = (float)($validated['service_charge'] ?? 0);
        $grandTotal = round($taxableAmount + $vatAmount + $serviceCharge, 2);

        $paidAmount = (float)($validated['paid_amount'] ?? 0);
        $changeAmount = max(0, $paidAmount - $grandTotal);
        $isPaid = ($validated['payment_status'] ?? 'unpaid') === 'paid' || $paidAmount >= $grandTotal;

        $order = DB::transaction(function () use (
            $validated, $branch, $activeShift, $subtotal, $discountType, $discountValue,
            $discountAmount, $vatPercent, $vatAmount, $serviceCharge, $grandTotal,
            $paidAmount, $changeAmount, $isPaid, $orderItemsData
        ) {
            $order = null;
            if (!empty($validated['order_id'])) {
                $order = Order::find($validated['order_id']);
            }

            if ($order) {
                // Update existing order
                $order->update([
                    'subtotal' => $subtotal,
                    'discount_type' => $discountType,
                    'discount_value' => $discountValue,
                    'discount_amount' => $discountAmount,
                    'vat_percent' => $vatPercent,
                    'vat_amount' => $vatAmount,
                    'service_charge' => $serviceCharge,
                    'grand_total' => $grandTotal,
                    'paid_amount' => $paidAmount,
                    'change_amount' => $changeAmount,
                    'payment_status' => $isPaid ? 'paid' : $order->payment_status,
                    'payment_method' => $validated['payment_method'] ?? $order->payment_method,
                    'status' => $isPaid ? 'completed' : $order->status,
                    'completed_at' => $isPaid ? now() : $order->completed_at,
                ]);
            } else {
                $orderNumber = Order::generateOrderNumber();
                $mushakNumber = Order::generateMushakNumber($branch);

                $order = Order::create([
                    'branch_id' => $branch->id,
                    'shift_id' => $activeShift?->id,
                    'table_id' => $validated['table_id'] ?? null,
                    'user_id' => auth()->id() ?? User::where('role', 'cashier')->first()?->id ?? 1,
                    'waiter_id' => $validated['waiter_id'] ?? null,
                    'order_number' => $orderNumber,
                    'invoice_number' => 'INV-' . strtoupper(substr(uniqid(), 7)),
                    'mushak_number' => $mushakNumber,
                    'order_type' => $validated['order_type'],
                    'token_number' => $validated['token_number'] ?? (string)rand(10, 99),
                    'customer_name' => $validated['customer_name'] ?? 'Guest',
                    'customer_phone' => $validated['customer_phone'] ?? null,
                    'customer_address' => $validated['customer_address'] ?? null,
                    'subtotal' => $subtotal,
                    'discount_type' => $discountType,
                    'discount_value' => $discountValue,
                    'discount_amount' => $discountAmount,
                    'vat_percent' => $vatPercent,
                    'vat_amount' => $vatAmount,
                    'service_charge' => $serviceCharge,
                    'grand_total' => $grandTotal,
                    'paid_amount' => $paidAmount,
                    'change_amount' => $changeAmount,
                    'payment_status' => $isPaid ? 'paid' : ($paidAmount > 0 ? 'partial' : 'unpaid'),
                    'payment_method' => $validated['payment_method'] ?? 'cash',
                    'status' => $isPaid ? 'completed' : 'cooking',
                    'notes' => $validated['notes'] ?? null,
                    'offline_uuid' => $validated['offline_uuid'] ?? null,
                    'billed_at' => now(),
                    'completed_at' => $isPaid ? now() : null,
                ]);
            }

            // Save new items (skip already existing items if updating)
            foreach ($orderItemsData as $data) {
                if (!empty($data['is_existing'])) {
                    continue; // Skip items already saved in DB
                }

                $item = $data['item'];
                $itemVat = ($data['subtotal'] * $vatPercent) / 100;

                $orderItem = OrderItem::create([
                    'order_id' => $order->id,
                    'item_id' => $item->id,
                    'variant_id' => $data['variant_id'],
                    'item_name' => $item->name,
                    'variant_name' => $data['variant_id'] ? $item->variants->firstWhere('id', $data['variant_id'])?->name : null,
                    'unit_price' => $data['unit_price'],
                    'quantity' => $data['quantity'],
                    'subtotal' => $data['subtotal'],
                    'vat_amount' => $itemVat,
                    'total_price' => $data['subtotal'] + $itemVat,
                    'notes' => $data['notes'],
                    'kitchen_station' => $item->kitchen_station ?? 'main_kitchen',
                    'kitchen_status' => 'pending',
                ]);

                // Modifiers
                if (!empty($data['modifiers'])) {
                    foreach ($data['modifiers'] as $modId) {
                        $mod = Modifier::find($modId);
                        if ($mod) {
                            OrderItemModifier::create([
                                'order_item_id' => $orderItem->id,
                                'modifier_id' => $mod->id,
                                'name' => $mod->name,
                                'price' => $mod->price,
                            ]);
                        }
                    }
                }
            }

            // Save Payments if paid
            if ($isPaid && !empty($validated['payments'])) {
                foreach ($validated['payments'] as $pay) {
                    Payment::create([
                        'order_id' => $order->id,
                        'user_id' => $order->user_id,
                        'payment_method' => $pay['method'] ?? 'cash',
                        'amount' => (float)$pay['amount'],
                        'transaction_ref' => $pay['ref'] ?? null,
                    ]);
                }
            } elseif ($isPaid && $paidAmount > 0) {
                Payment::create([
                    'order_id' => $order->id,
                    'user_id' => $order->user_id,
                    'payment_method' => $validated['payment_method'] ?? 'cash',
                    'amount' => $grandTotal,
                ]);
            }

            // Update Table status
            if (!empty($validated['table_id'])) {
                $table = RestaurantTable::find($validated['table_id']);
                if ($table) {
                    $otherActiveOrder = Order::where('table_id', $table->id)
                        ->where('id', '!=', $order->id)
                        ->where('payment_status', '!=', 'paid')
                        ->where('status', '!=', 'cancelled')
                        ->latest()
                        ->first();

                    if ($isPaid) {
                        if ($otherActiveOrder) {
                            $table->status = 'occupied';
                            $table->current_order_id = $otherActiveOrder->id;
                        } else {
                            $table->status = 'available';
                            $table->current_order_id = null;
                        }
                    } else {
                        $table->status = 'occupied';
                        $table->current_order_id = $order->id;
                    }
                    $table->save();
                }
            }

            // Update shift sales if paid
            if ($isPaid && $activeShift) {
                $activeShift->total_sales += $grandTotal;
                $method = strtolower($validated['payment_method'] ?? 'cash');
                if ($method === 'cash') $activeShift->cash_sales += $grandTotal;
                elseif ($method === 'bkash') $activeShift->bkash_sales += $grandTotal;
                elseif ($method === 'nagad') $activeShift->nagad_sales += $grandTotal;
                elseif ($method === 'card') $activeShift->card_sales += $grandTotal;
                $activeShift->expected_cash = $activeShift->opening_float + $activeShift->cash_sales;
                $activeShift->save();
            }

            // Customer & Loyalty Points Tracking
            if (!empty($validated['customer_phone'])) {
                $customer = \App\Models\Customer::firstOrCreate(
                    ['phone' => $validated['customer_phone']],
                    ['name' => $validated['customer_name'] ?? 'Guest (' . $validated['customer_phone'] . ')']
                );

                $order->update(['customer_id' => $customer->id]);

                // Deduct redeemed points if used
                $redeemedPoints = (int)($validated['redeemed_points'] ?? 0);
                if ($redeemedPoints > 0) {
                    $customer->reward_points = max(0, $customer->reward_points - $redeemedPoints);
                    $customer->save();

                    \App\Models\LoyaltyTransaction::create([
                        'customer_id' => $customer->id,
                        'order_id' => $order->id,
                        'type' => 'redeemed',
                        'points' => -$redeemedPoints,
                        'balance_after' => $customer->reward_points,
                        'discount_value' => $discountAmount,
                        'description' => "পয়েন্ট রিডিম করে ৳{$discountAmount} ডিসকাউন্ট গ্রহণ",
                    ]);
                    $order->update(['points_redeemed' => $redeemedPoints]);
                }

                // Award points on paid orders (1 point per ৳100)
                if ($isPaid) {
                    $earnedPoints = floor($grandTotal / 100);
                    if ($earnedPoints > 0) {
                        $customer->increment('reward_points', $earnedPoints);
                        \App\Models\LoyaltyTransaction::create([
                            'customer_id' => $customer->id,
                            'order_id' => $order->id,
                            'type' => 'earned',
                            'points' => $earnedPoints,
                            'balance_after' => $customer->reward_points,
                            'description' => "অর্ডার #{$order->order_number} থেকে অর্জিত পয়েন্ট",
                        ]);
                        $order->update(['points_earned' => $earnedPoints]);
                    }

                    $customer->increment('total_visits');
                    $customer->increment('total_spent', $grandTotal);
                    $customer->recalculateTier();
                }
            }

            // Trigger Recipe BOM inventory deduction
            $this->inventoryService->deductOrderIngredients($order);

            return $order;
        });

        $order->load(['items.modifiers', 'table', 'payments', 'customer', 'waiter']);
        $mushakData = $this->mushakService->formatMushak63Invoice($order);
        $freshTables = RestaurantTable::where('is_active', true)
            ->with(['currentOrder.items.modifiers', 'currentOrder.customer', 'activeOrders.items.modifiers', 'activeOrders.customer'])
            ->orderBy('sort_order')
            ->get();

            return response()->json([
                'success' => true,
                'message' => 'Order created successfully!',
                'order' => $order,
                'mushak' => $mushakData,
                'tables' => $freshTables,
            ]);
        } catch (\Illuminate\Validation\ValidationException $ve) {
            return response()->json([
                'success' => false,
                'message' => 'ভ্যালিডেশন এরর: ' . implode(', ', array_map(fn($e) => implode(' ', $e), $ve->errors())),
                'errors' => $ve->errors(),
            ], 422);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'সার্ভার এরর: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Settle payment for an existing occupied table / pending order
     */
    public function settlePayment(Request $request, Order $order): JsonResponse
    {
        try {
        $validated = $request->validate([
            'payment_method' => 'required|string',
            'paid_amount' => 'required|numeric|min:' . $order->grand_total,
            'payments' => 'nullable|array',
        ]);

        $paidAmount = (float)$validated['paid_amount'];
        $changeAmount = max(0, $paidAmount - $order->grand_total);

        DB::transaction(function () use ($order, $validated, $paidAmount, $changeAmount) {
            $order->paid_amount = $paidAmount;
            $order->change_amount = $changeAmount;
            $order->payment_status = 'paid';
            $order->payment_method = $validated['payment_method'];
            $order->status = 'completed';
            $order->completed_at = now();
            $order->save();

            // Settle Table
            if ($order->table_id) {
                $table = RestaurantTable::find($order->table_id);
                if ($table) {
                    $otherActiveOrder = Order::where('table_id', $table->id)
                        ->where('id', '!=', $order->id)
                        ->where('payment_status', '!=', 'paid')
                        ->where('status', '!=', 'cancelled')
                        ->latest()
                        ->first();

                    if ($otherActiveOrder) {
                        $table->status = 'occupied';
                        $table->current_order_id = $otherActiveOrder->id;
                    } else {
                        $table->status = 'available';
                        $table->current_order_id = null;
                    }
                    $table->save();
                }
            }

            // Save Payments
            if (!empty($validated['payments'])) {
                foreach ($validated['payments'] as $pay) {
                    Payment::create([
                        'order_id' => $order->id,
                        'user_id' => auth()->id() ?? $order->user_id,
                        'payment_method' => $pay['method'] ?? 'cash',
                        'amount' => (float)$pay['amount'],
                        'transaction_ref' => $pay['ref'] ?? null,
                    ]);
                }
            } else {
                Payment::create([
                    'order_id' => $order->id,
                    'user_id' => auth()->id() ?? $order->user_id,
                    'payment_method' => $validated['payment_method'],
                    'amount' => $order->grand_total,
                ]);
            }

            // Update shift
            $activeShift = Shift::where('status', 'open')->latest()->first();
            if ($activeShift) {
                $activeShift->total_sales += $order->grand_total;
                $method = strtolower($validated['payment_method']);
                if ($method === 'cash') $activeShift->cash_sales += $order->grand_total;
                elseif ($method === 'bkash') $activeShift->bkash_sales += $order->grand_total;
                elseif ($method === 'nagad') $activeShift->nagad_sales += $order->grand_total;
                elseif ($method === 'card') $activeShift->card_sales += $order->grand_total;
                $activeShift->expected_cash = $activeShift->opening_float + $activeShift->cash_sales;
                $activeShift->save();
            }
        });

        $order->load(['items.modifiers', 'table', 'payments']);
        $mushakData = $this->mushakService->formatMushak63Invoice($order);
        $freshTables = RestaurantTable::where('is_active', true)
            ->with(['currentOrder.items.modifiers', 'currentOrder.customer', 'activeOrders.items.modifiers', 'activeOrders.customer'])
            ->orderBy('sort_order')
            ->get();

            return response()->json([
                'success' => true,
                'message' => 'Payment settled successfully!',
                'order' => $order,
                'mushak' => $mushakData,
                'tables' => $freshTables,
            ]);
        } catch (\Illuminate\Validation\ValidationException $ve) {
            return response()->json([
                'success' => false,
                'message' => 'ভ্যালিডেশন এরর: ' . implode(', ', array_map(fn($e) => implode(' ', $e), $ve->errors())),
                'errors' => $ve->errors(),
            ], 422);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'সার্ভার এরর: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Start/Open Cashier Shift
     */
    public function openShift(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'opening_float' => 'required|numeric|min:0',
        ]);

        $existing = Shift::where('status', 'open')->first();
        if ($existing) {
            return response()->json(['success' => false, 'message' => 'A shift is already open!'], 422);
        }

        $shift = Shift::create([
            'branch_id' => Branch::first()->id ?? null,
            'user_id' => auth()->id() ?? 1,
            'opened_at' => now(),
            'opening_float' => $validated['opening_float'],
            'expected_cash' => $validated['opening_float'],
            'status' => 'open',
        ]);

        return response()->json(['success' => true, 'shift' => $shift]);
    }

    /**
     * Close Cashier Shift & Generate Z-Report
     */
    public function closeShift(Request $request, Shift $shift): JsonResponse
    {
        $validated = $request->validate([
            'actual_cash_counted' => 'required|numeric|min:0',
            'closing_note' => 'nullable|string',
        ]);

        $counted = (float)$validated['actual_cash_counted'];
        $diff = $counted - $shift->expected_cash;

        $shift->actual_cash_counted = $counted;
        $shift->cash_difference = $diff;
        $shift->closing_note = $validated['closing_note'] ?? null;
        $shift->closed_at = now();
        $shift->status = 'closed';
        $shift->save();

        return response()->json([
            'success' => true,
            'message' => 'Shift closed successfully. Z-Report generated.',
            'shift' => $shift,
        ]);
    }

    /**
     * Get Mushak 6.3 Print payload for an order
     */
    public function getMushakInvoice(Order $order): JsonResponse
    {
        $order->load(['items.modifiers', 'table', 'branch', 'user']);
        $mushak = $this->mushakService->formatMushak63Invoice($order);

        return response()->json([
            'success' => true,
            'mushak' => $mushak,
        ]);
    }
}
