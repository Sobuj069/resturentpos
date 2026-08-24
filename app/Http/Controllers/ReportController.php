<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Shift;
use App\Models\User;
use App\Models\Wastage;
use App\Services\GeminiService;
use App\Services\MushakService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ReportController extends Controller
{
    protected GeminiService $geminiService;
    protected MushakService $mushakService;

    public function __construct(GeminiService $geminiService, MushakService $mushakService)
    {
        $this->geminiService = $geminiService;
        $this->mushakService = $mushakService;
    }

    /**
     * Executive Dashboard & Analytics
     */
    public function dashboard(): View
    {
        $today = now()->startOfDay();

        $todayOrders = Order::where('created_at', '>=', $today)->where('status', '!=', 'cancelled')->get();
        $todaySales = $todayOrders->where('payment_status', 'paid')->sum('grand_total');
        $todayVat = $todayOrders->sum('vat_amount');
        $todayOrderCount = $todayOrders->count();

        // Payment Method breakdown
        $payments = Payment::where('created_at', '>=', $today)
            ->select('payment_method', DB::raw('SUM(amount) as total'))
            ->groupBy('payment_method')
            ->pluck('total', 'payment_method')
            ->toArray();

        // Top 5 selling items today
        $topItems = OrderItem::whereHas('order', function ($q) use ($today) {
                $q->where('created_at', '>=', $today)->where('status', '!=', 'cancelled');
            })
            ->select('item_name', DB::raw('SUM(quantity) as total_qty'), DB::raw('SUM(total_price) as total_revenue'))
            ->groupBy('item_name')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();

        // Recent Orders
        $recentOrders = Order::with(['items', 'table', 'user'])
            ->latest('id')
            ->limit(10)
            ->get();

        // Recent Shifts
        $recentShifts = Shift::with('user')->latest('id')->limit(5)->get();

        return view('reports.dashboard', compact(
            'todaySales',
            'todayVat',
            'todayOrderCount',
            'payments',
            'topItems',
            'recentOrders',
            'recentShifts'
        ));
    }

    /**
     * NBR Mushak 6.3 Register
     */
    public function mushakRegister(Request $request): View
    {
        $startDate = $request->query('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->query('end_date', now()->format('Y-m-d'));

        $orders = Order::whereNotNull('mushak_number')
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->with(['branch', 'user', 'items'])
            ->latest('id')
            ->paginate(25);

        $totalMushakSales = Order::whereNotNull('mushak_number')
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->sum('subtotal');

        $totalMushakVat = Order::whereNotNull('mushak_number')
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->sum('vat_amount');

        return view('reports.mushak', compact('orders', 'startDate', 'endDate', 'totalMushakSales', 'totalMushakVat'));
    }

    /**
     * Trigger Gemini AI Daily Business Analysis
     */
    public function getAiInsight(): JsonResponse
    {
        $today = now()->startOfDay();
        $orders = Order::where('created_at', '>=', $today)->get();
        $totalSales = $orders->where('payment_status', 'paid')->sum('grand_total');
        $totalVat = $orders->sum('vat_amount');

        $topItem = OrderItem::whereHas('order', fn($q) => $q->where('created_at', '>=', $today))
            ->select('item_name', DB::raw('SUM(quantity) as qty'))
            ->groupBy('item_name')
            ->orderByDesc('qty')
            ->first();

        $wastageCost = Wastage::where('created_at', '>=', $today)->sum('cost_impact');

        $stats = [
            'date' => now()->format('Y-m-d'),
            'total_sales' => $totalSales,
            'total_orders' => $orders->count(),
            'total_vat' => $totalVat,
            'top_selling_dish' => $topItem ? "{$topItem->item_name} ({$topItem->qty} servings)" : 'N/A',
            'wastage_cost' => $wastageCost,
        ];

        $insight = $this->geminiService->getDailyExecutiveSummary($stats);

        return response()->json([
            'success' => true,
            'stats' => $stats,
            'insight' => $insight,
        ]);
    }

    /**
     * Cashier Shift Opening & Closing Audit Report
     */
    public function shiftReport(Request $request): View
    {
        $startDate = $request->query('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->query('end_date', now()->format('Y-m-d'));
        $userId = $request->query('user_id');

        $query = Shift::with('user')
            ->whereDate('opened_at', '>=', $startDate)
            ->whereDate('opened_at', '<=', $endDate);

        if ($userId) {
            $query->where('user_id', $userId);
        }

        $shifts = $query->latest('opened_at')->paginate(20)->withQueryString();

        // Summary Stats
        $totalShifts = Shift::whereDate('opened_at', '>=', $startDate)->whereDate('opened_at', '<=', $endDate)->count();
        $totalOpeningFloat = Shift::whereDate('opened_at', '>=', $startDate)->whereDate('opened_at', '<=', $endDate)->sum('opening_float');
        $totalCashSales = Shift::whereDate('opened_at', '>=', $startDate)->whereDate('opened_at', '<=', $endDate)->sum('cash_sales');
        $totalVariance = Shift::whereDate('opened_at', '>=', $startDate)->whereDate('opened_at', '<=', $endDate)->sum('cash_difference');

        $cashiers = User::whereIn('role', ['cashier', 'admin', 'manager'])->orderBy('name')->get();

        return view('reports.shifts', compact(
            'shifts',
            'startDate',
            'endDate',
            'userId',
            'totalShifts',
            'totalOpeningFloat',
            'totalCashSales',
            'totalVariance',
            'cashiers'
        ));
    }
}
