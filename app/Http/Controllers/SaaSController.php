<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Category;
use App\Models\Order;
use App\Models\RestaurantTable;
use App\Models\Shift;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

class SaaSController extends Controller
{
    /**
     * SaaS SuperAdmin Multi-Tenant Command Center
     */
    public function dashboard(): View
    {
        $tenants = Tenant::with(['branches.orders', 'users'])->latest()->get();
        $totalTenants = $tenants->count();
        $activeTenants = $tenants->where('subscription_status', 'active')->count();
        $trialTenants = $tenants->where('subscription_status', 'trial')->count();
        $totalMrr = $tenants->where('subscription_status', 'active')->sum('monthly_fee');

        $totalBranches = Branch::count();
        $totalOrdersCount = Order::count();
        $totalSystemSales = Order::where('payment_status', 'paid')->sum('grand_total');

        // All Staff / Restaurant Users for SuperAdmin Impersonation
        $allUsers = User::with(['branch', 'tenant'])->orderBy('role')->get();

        // Recent System Activity Across All Restaurants
        $recentOrders = Order::with(['table', 'user', 'branch.tenant', 'customer'])
            ->latest()
            ->take(25)
            ->get();

        $recentShifts = Shift::with(['user', 'branch.tenant'])
            ->latest()
            ->take(15)
            ->get();

        return view('saas.dashboard', compact(
            'tenants',
            'totalTenants',
            'activeTenants',
            'trialTenants',
            'totalMrr',
            'totalBranches',
            'totalOrdersCount',
            'totalSystemSales',
            'allUsers',
            'recentOrders',
            'recentShifts'
        ));
    }

    /**
     * SuperAdmin Impersonate / Login As ANY User
     */
    public function impersonate(User $user): RedirectResponse
    {
        $currentAuth = Auth::user();

        // Ensure only SuperAdmin (or active impersonation) can trigger this
        if (!$currentAuth->isSuperAdmin() && !session()->has('impersonating_superadmin_id')) {
            return redirect()->route('pos.index')->with('error', 'অননুমোদিত এক্সেস!');
        }

        // Store original superadmin ID if starting impersonation
        if (!session()->has('impersonating_superadmin_id')) {
            session(['impersonating_superadmin_id' => $currentAuth->id]);
        }

        Auth::login($user);

        return redirect()->route('pos.index')->with('success', "সুপার-অ্যাডমিন মোড: আপনি বর্তমানে '{$user->name}' ({$user->role}) হিসেবে যুক্ত আছেন।");
    }

    /**
     * Leave Impersonation and return to SuperAdmin
     */
    public function leaveImpersonation(): RedirectResponse
    {
        $superAdminId = session('impersonating_superadmin_id');

        if ($superAdminId) {
            $superAdmin = User::find($superAdminId);
            session()->forget('impersonating_superadmin_id');

            if ($superAdmin) {
                Auth::login($superAdmin);
                return redirect()->route('saas.dashboard')->with('success', 'সফলভাবে সুপার-অ্যাডমিন কমান্ড সেন্টারে ফিরে এসেছেন!');
            }
        }

        return redirect()->route('pos.index');
    }

    /**
     * Public Restaurant Registration / Onboarding View
     */
    public function registerForm(): View
    {
        return view('saas.register');
    }

    /**
     * Store New Tenant Restaurant
     */
    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'restaurant_name' => 'required|string|max:100',
            'owner_name' => 'required|string|max:100',
            'email' => 'required|email|unique:tenants,email|unique:users,email',
            'phone' => ['required', 'regex:/^01[3-9]\d{8}$/', 'unique:tenants,phone', 'unique:users,phone'],
            'password' => 'required|string|min:6',
            'package_plan' => 'required|in:starter,growth,enterprise',
            'subdomain' => 'nullable|string|alpha_dash|unique:tenants,subdomain',
        ], [
            'phone.regex' => 'মোবাইল নম্বরটি অবশ্যই সঠিক ১১ ডিজিটের বাংলাদেশী নম্বর হতে হবে (যেমনঃ 017XXXXXXXX)।',
            'phone.unique' => 'এই মোবাইল নম্বরটি দিয়ে ইতিমধ্যে একটি অ্যাকাউন্ট তৈরি করা আছে।',
        ]);

        $monthlyFee = match ($validated['package_plan']) {
            'starter' => 1200.00,
            'enterprise' => 5000.00,
            default => 2500.00,
        };

        $maxBranches = match ($validated['package_plan']) {
            'starter' => 1,
            'enterprise' => 99,
            default => 3,
        };

        $slug = Str::slug($validated['restaurant_name']) . '-' . rand(100, 999);
        $subdomain = $validated['subdomain'] ?? Str::slug($validated['restaurant_name']);

        // 1. Create Tenant
        $tenant = Tenant::create([
            'name' => $validated['restaurant_name'],
            'slug' => $slug,
            'subdomain' => $subdomain,
            'owner_name' => $validated['owner_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'package_plan' => $validated['package_plan'],
            'monthly_fee' => $monthlyFee,
            'subscription_status' => 'trial',
            'trial_ends_at' => now()->addDays(14),
            'subscription_expires_at' => now()->addDays(14),
            'max_branches' => $maxBranches,
            'max_staff' => $validated['package_plan'] === 'starter' ? 5 : 25,
            'is_active' => true,
        ]);

        // 2. Create Default Branch
        $branch = Branch::create([
            'tenant_id' => $tenant->id,
            'name' => 'Main Outlet',
            'code' => 'BR-' . rand(100, 999),
            'restaurant_name' => $validated['restaurant_name'],
            'phone' => $validated['phone'],
            'email' => $validated['email'],
            'address' => 'Dhaka, Bangladesh',
            'default_vat_rate' => 5.00,
            'currency_symbol' => '৳',
            'is_active' => true,
        ]);

        // 3. Create Tenant Admin User
        $user = User::create([
            'tenant_id' => $tenant->id,
            'branch_id' => $branch->id,
            'name' => $validated['owner_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'password' => Hash::make($validated['password']),
            'role' => 'admin',
            'pin_code' => '1234',
            'is_active' => true,
        ]);

        // 4. Seed Default Category & Tables
        Category::create([
            'name' => 'Fast Food & Beverages',
            'slug' => 'fast-food-' . rand(100, 999),
            'bangla_name' => 'ফাস্ট ফুড ও পানীয়',
            'icon' => 'pizza',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        for ($i = 1; $i <= 5; $i++) {
            RestaurantTable::create([
                'branch_id' => $branch->id,
                'name' => 'Table ' . str_pad($i, 2, '0', STR_PAD_LEFT),
                'floor_name' => 'Main Dining',
                'capacity' => 4,
                'status' => 'available',
                'sort_order' => $i,
                'is_active' => true,
            ]);
        }

        Auth::login($user, true);

        return redirect()->route('reports.dashboard')->with('success', "অভিনন্দন! আপনার রেস্টুরেন্ট '{$tenant->name}' সফলভাবে নিবন্ধিত হয়েছে। ১৪ দিনের ফ্রি ট্রায়াল শুরু হয়েছে!");
    }

    /**
     * SuperAdmin Update Tenant Subscription & Status
     */
    public function updateTenant(Request $request, Tenant $tenant): JsonResponse
    {
        $validated = $request->validate([
            'package_plan' => 'nullable|in:starter,growth,enterprise',
            'subscription_status' => 'nullable|in:trial,active,past_due,suspended',
            'max_branches' => 'nullable|integer|min:1',
            'max_staff' => 'nullable|integer|min:1',
            'is_active' => 'nullable|boolean',
        ]);

        $tenant->update($validated);

        return response()->json([
            'success' => true,
            'message' => "Tenant '{$tenant->name}' সফলভাবে আপডেট হয়েছে!",
            'tenant' => $tenant,
        ]);
    }

    /**
     * SuperAdmin Delete Tenant
     */
    public function deleteTenant(Tenant $tenant): JsonResponse
    {
        $name = $tenant->name;
        $tenant->delete();

        return response()->json([
            'success' => true,
            'message' => "রেস্টুরেন্ট টেন্যান্ট '{$name}' মুছে ফেলা হয়েছে!",
        ]);
    }

    /**
     * SaaS Pricing Plans View
     */
    public function plans(): View
    {
        return view('saas.plans');
    }
}
