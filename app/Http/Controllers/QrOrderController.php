<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Category;
use App\Models\Item;
use App\Models\ItemVariant;
use App\Models\Modifier;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderItemModifier;
use App\Models\RestaurantTable;
use App\Services\MushakService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class QrOrderController extends Controller
{
    protected MushakService $mushakService;

    public function __construct(MushakService $mushakService)
    {
        $this->mushakService = $mushakService;
    }

    /**
     * Public Contactless Digital Menu for Dining Guests
     */
    public function showGuestMenu(Request $request, string $token): View
    {
        $table = RestaurantTable::where('qr_code_token', $token)
            ->orWhere('id', $token)
            ->firstOrFail();

        $branch = Branch::first();
        $categories = Category::where('is_active', true)
            ->with(['items' => function ($q) {
                $q->where('is_available', true)->with(['variants', 'modifiers']);
            }])
            ->orderBy('sort_order')
            ->get();

        return view('qr_order.menu', compact('table', 'branch', 'categories'));
    }

    /**
     * Guest Place Self-Order via Mobile Web
     */
    public function placeGuestOrder(Request $request, string $token): JsonResponse
    {
        $table = RestaurantTable::where('qr_code_token', $token)
            ->orWhere('id', $token)
            ->firstOrFail();

        $validated = $request->validate([
            'customer_name' => 'nullable|string|max:100',
            'customer_phone' => 'nullable|string|max:20',
            'items' => 'required|array|min:1',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.variant_id' => 'nullable|exists:item_variants,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.notes' => 'nullable|string',
            'items.*.modifiers' => 'nullable|array',
        ]);

        return DB::transaction(function () use ($validated, $table) {
            $branch = Branch::first();
            $subtotal = 0;
            $itemsToCreate = [];

            foreach ($validated['items'] as $it) {
                $item = Item::find($it['item_id']);
                if (!$item) continue;

                $variant = !empty($it['variant_id']) ? ItemVariant::find($it['variant_id']) : null;
                $unitPrice = (float)($it['unit_price'] ?? ($variant ? $variant->price : $item->selling_price));
                $qty = max(1, (int)($it['quantity'] ?? 1));
                $lineSubtotal = $unitPrice * $qty;

                // Modifiers
                $modTotal = 0;
                $modsToAttach = [];
                if (!empty($it['modifiers'])) {
                    foreach ($it['modifiers'] as $modId) {
                        $mod = Modifier::find($modId);
                        if ($mod) {
                            $modTotal += ($mod->price * $qty);
                            $modsToAttach[] = $mod;
                        }
                    }
                }

                $totalLine = $lineSubtotal + $modTotal;
                $subtotal += $totalLine;

                $itemsToCreate[] = [
                    'item' => $item,
                    'variant' => $variant,
                    'unit_price' => $unitPrice,
                    'quantity' => $qty,
                    'subtotal' => $totalLine,
                    'notes' => $it['notes'] ?? null,
                    'modifiers' => $modsToAttach,
                ];
            }

            $vatRate = (float)($branch->default_vat_rate ?? 5.0);
            $vatAmount = ($subtotal * $vatRate) / 100;
            $grandTotal = round($subtotal + $vatAmount);

            $order = Order::create([
                'branch_id' => $branch->id ?? 1,
                'table_id' => $table->id,
                'order_number' => Order::generateOrderNumber(),
                'invoice_number' => 'INV-' . strtoupper(substr(uniqid(), 7)),
                'mushak_number' => Order::generateMushakNumber($branch),
                'order_type' => 'dine_in',
                'customer_name' => $validated['customer_name'] ?? ('QR Guest (' . $table->name . ')'),
                'customer_phone' => $validated['customer_phone'] ?? null,
                'subtotal' => $subtotal,
                'vat_percent' => $vatRate,
                'vat_amount' => $vatAmount,
                'grand_total' => $grandTotal,
                'paid_amount' => 0,
                'change_amount' => 0,
                'payment_status' => 'unpaid',
                'status' => 'confirmed',
                'kitchen_status' => 'pending',
                'token_number' => (string)rand(10, 99),
                'billed_at' => now(),
            ]);

            foreach ($itemsToCreate as $data) {
                $item = $data['item'];
                $variant = $data['variant'];
                $itemVat = ($data['subtotal'] * $vatRate) / 100;

                $orderItem = OrderItem::create([
                    'order_id' => $order->id,
                    'item_id' => $item->id,
                    'variant_id' => $variant?->id,
                    'item_name' => $item->name,
                    'variant_name' => $variant?->name,
                    'unit_price' => $data['unit_price'],
                    'quantity' => $data['quantity'],
                    'subtotal' => $data['subtotal'],
                    'vat_amount' => $itemVat,
                    'total_price' => $data['subtotal'] + $itemVat,
                    'notes' => $data['notes'],
                    'kitchen_station' => $item->kitchen_station ?? 'main_kitchen',
                    'kitchen_status' => 'pending',
                ]);

                if (!empty($data['modifiers'])) {
                    foreach ($data['modifiers'] as $mod) {
                        OrderItemModifier::create([
                            'order_item_id' => $orderItem->id,
                            'modifier_id' => $mod->id,
                            'name' => $mod->name,
                            'price' => $mod->price,
                        ]);
                    }
                }
            }

            // Mark table occupied & link active order
            $table->update([
                'status' => 'occupied',
                'current_order_id' => $order->id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'আপনার অর্ডার সফলভাবে কিচেনে পাঠানো হয়েছে!',
                'order_number' => $order->order_number,
                'grand_total' => $order->grand_total,
                'table_name' => $table->name,
            ]);
        });
    }

    /**
     * Printable Table QR Cards View
     */
    public function printTableCards(Request $request): View
    {
        $tables = RestaurantTable::where('is_active', true)->orderBy('floor_name')->orderBy('sort_order')->get();
        $branch = Branch::first();

        return view('qr_order.print_cards', compact('tables', 'branch'));
    }
}
