<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Category;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class ExpenseController extends Controller
{
    /**
     * Expense Management & Profit/Loss (P&L) Dashboard
     */
    public function index(Request $request): View
    {
        $startDate = $request->input('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', now()->toDateString());

        // Ensure Staff Salary category exists
        ExpenseCategory::firstOrCreate(
            ['name' => 'Staff Salary & Wages'],
            ['bangla_name' => 'স্টাফ বেতন ও দৈনিক মজুরি', 'icon' => 'users', 'is_active' => true]
        );

        $categories = ExpenseCategory::withCount('expenses')->get();
        $staffList = User::where('is_active', true)->orderBy('name')->get();

        $selectedStaffId = $request->input('staff_id');
        $selectedCategoryId = $request->input('category_id');

        $query = Expense::with(['category', 'user', 'staffUser'])
            ->whereBetween('expense_date', [$startDate, $endDate]);

        if ($selectedStaffId) {
            $query->where('staff_user_id', $selectedStaffId);
        }

        if ($selectedCategoryId) {
            $query->where('category_id', $selectedCategoryId);
        }

        $expenses = $query->latest('expense_date')->paginate(15)->withQueryString();

        // 1. Total Operating Expenses in period
        $totalExpenses = Expense::whereBetween('expense_date', [$startDate, $endDate])->sum('amount');

        // 2. Total Gross Sales in period
        $totalSales = Order::whereBetween('created_at', [
            Carbon::parse($startDate)->startOfDay(),
            Carbon::parse($endDate)->endOfDay(),
        ])->where('payment_status', 'paid')->sum('grand_total');

        // 3. Estimated Cost of Goods Sold (COGS) from Recipe BOM / Cost Price
        $orderItemIds = Order::whereBetween('created_at', [
            Carbon::parse($startDate)->startOfDay(),
            Carbon::parse($endDate)->endOfDay(),
        ])->where('payment_status', 'paid')->pluck('id');

        $totalCogs = OrderItem::whereIn('order_id', $orderItemIds)
            ->with('item')
            ->get()
            ->sum(fn($oi) => ($oi->item->cost_price ?? ($oi->unit_price * 0.45)) * $oi->quantity);

        // 4. Net Profit Calculation
        $grossProfit = max(0, $totalSales - $totalCogs);
        $netProfit = $grossProfit - $totalExpenses;
        $netProfitMargin = $totalSales > 0 ? ($netProfit / $totalSales) * 100 : 0;

        return view('expenses.index', compact(
            'expenses',
            'categories',
            'staffList',
            'startDate',
            'endDate',
            'selectedStaffId',
            'selectedCategoryId',
            'totalExpenses',
            'totalSales',
            'totalCogs',
            'grossProfit',
            'netProfit',
            'netProfitMargin'
        ));
    }

    /**
     * Get Staff Salary Ledger & Payout Statement
     */
    public function staffLedger(User $user): JsonResponse
    {
        $payouts = Expense::where('staff_user_id', $user->id)
            ->with(['user', 'branch'])
            ->latest('expense_date')
            ->get();

        $totalPaid = $payouts->sum('amount');
        $thisMonthPaid = $payouts->filter(fn($p) => Carbon::parse($p->expense_date)->isCurrentMonth())->sum('amount');
        $advances = $payouts->where('salary_period', 'advance')->sum('amount');

        return response()->json([
            'success' => true,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'role' => $user->role,
                'phone' => $user->phone,
                'salary_type' => $user->salary_type,
                'base_salary' => (float)$user->base_salary,
            ],
            'summary' => [
                'total_paid' => $totalPaid,
                'this_month_paid' => $thisMonthPaid,
                'total_advances' => $advances,
                'total_payout_count' => $payouts->count(),
            ],
            'payouts' => $payouts->map(fn($p) => [
                'id' => $p->id,
                'title' => $p->title,
                'amount' => (float)$p->amount,
                'salary_period' => $p->salary_period ?? 'salary',
                'payment_method' => strtoupper($p->payment_method),
                'expense_date' => $p->expense_date->format('d M, Y'),
                'issued_by' => $p->user->name ?? 'Admin',
                'receipt_number' => $p->receipt_number ?? ('PAY-' . $p->id),
                'notes' => $p->notes ?? '—',
            ]),
        ]);
    }

    /**
     * Store or Update Expense
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'id' => 'nullable|exists:expenses,id',
            'category_id' => 'required|exists:expense_categories,id',
            'staff_user_id' => 'nullable|exists:users,id',
            'salary_period' => 'nullable|in:daily,weekly,monthly,advance',
            'title' => 'required|string|max:150',
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|in:cash,bkash,nagad,bank',
            'expense_date' => 'required|date',
            'receipt_number' => 'nullable|string|max:50',
            'notes' => 'nullable|string',
        ]);

        $branch = Branch::first();

        $expense = Expense::updateOrCreate(
            ['id' => $validated['id'] ?? null],
            [
                'branch_id' => $branch->id ?? 1,
                'user_id' => auth()->id() ?? 1,
                ...$validated,
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'খরচ/বেতন এন্ট্রি সফলভাবে সম্পন্ন হয়েছে!',
            'expense' => $expense->load(['category', 'staffUser']),
        ]);
    }

    /**
     * Store Expense Category
     */
    public function storeCategory(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'bangla_name' => 'nullable|string|max:100',
            'icon' => 'nullable|string|max:50',
        ]);

        $category = ExpenseCategory::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'খরচের ক্যাটাগরি তৈরি হয়েছে!',
            'category' => $category,
        ]);
    }

    /**
     * Delete Expense
     */
    public function destroy(Expense $expense): JsonResponse
    {
        $expense->delete();
        return response()->json([
            'success' => true,
            'message' => 'খরচের রেকর্ড মুছে ফেলা হয়েছে!',
        ]);
    }
}
