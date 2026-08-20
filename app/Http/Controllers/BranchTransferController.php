<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Ingredient;
use App\Models\StockTransfer;
use App\Models\StockTransferItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class BranchTransferController extends Controller
{
    /**
     * Inter-Branch Stock Transfer Dashboard
     */
    public function index(Request $request): View
    {
        $transfers = StockTransfer::with(['sourceBranch', 'destinationBranch', 'requester', 'items.ingredient'])
            ->latest()
            ->paginate(15);

        $branches = Branch::where('is_active', true)->get();
        $ingredients = Ingredient::orderBy('name')->get();

        return view('transfers.index', compact('transfers', 'branches', 'ingredients'));
    }

    /**
     * Create New Stock Transfer / Requisition
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'source_branch_id' => 'required|exists:branches,id',
            'destination_branch_id' => 'required|exists:branches,id|different:source_branch_id',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.ingredient_id' => 'required|exists:ingredients,id',
            'items.*.quantity_sent' => 'required|numeric|min:0.01',
            'items.*.unit' => 'required|string',
        ]);

        return DB::transaction(function () use ($validated) {
            $transfer = StockTransfer::create([
                'transfer_number' => 'TRF-' . now()->format('Ymd') . '-' . rand(1000, 9999),
                'source_branch_id' => $validated['source_branch_id'],
                'destination_branch_id' => $validated['destination_branch_id'],
                'requested_by' => auth()->id() ?? 1,
                'status' => 'dispatched',
                'dispatched_at' => now(),
                'notes' => $validated['notes'] ?? null,
            ]);

            foreach ($validated['items'] as $item) {
                StockTransferItem::create([
                    'transfer_id' => $transfer->id,
                    'ingredient_id' => $item['ingredient_id'],
                    'quantity_sent' => $item['quantity_sent'],
                    'unit' => $item['unit'],
                ]);

                // Deduct from source stock
                $ing = Ingredient::find($item['ingredient_id']);
                if ($ing) {
                    $ing->decrement('current_stock', $item['quantity_sent']);
                }
            }

            return response()->json([
                'success' => true,
                'message' => "স্টক ট্রান্সফার রিকুইজিশন #{$transfer->transfer_number} সফলভাবে পাঠানো হয়েছে!",
                'transfer' => $transfer->load(['items.ingredient']),
            ]);
        });
    }

    /**
     * Mark Transfer as Received at Destination
     */
    public function markReceived(Request $request, StockTransfer $transfer): JsonResponse
    {
        if ($transfer->status === 'received') {
            return response()->json(['success' => false, 'message' => 'এই ট্রান্সফারটি ইতিমধ্যে রিসিভ করা হয়েছে!'], 422);
        }

        DB::transaction(function () use ($transfer) {
            $transfer->update([
                'status' => 'received',
                'received_at' => now(),
                'approved_by' => auth()->id() ?? 1,
            ]);

            // Add stock to destination
            foreach ($transfer->items as $tItem) {
                $ing = Ingredient::find($tItem->ingredient_id);
                if ($ing) {
                    $ing->increment('current_stock', $tItem->quantity_sent);
                }
            }
        });

        return response()->json([
            'success' => true,
            'message' => 'মালামাল সফলভাবে রিসিভ করা হয়েছে এবং ইনভেন্টরি স্টক আপডেট হয়েছে!',
        ]);
    }
}
