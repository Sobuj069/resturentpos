<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\LoyaltyTransaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomerController extends Controller
{
    /**
     * Customer CRM & Loyalty Dashboard
     */
    public function index(Request $request): View
    {
        $query = Customer::withCount('orders')->with('loyaltyTransactions');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($tier = $request->input('tier')) {
            $query->where('membership_tier', $tier);
        }

        $customers = $query->orderByDesc('total_spent')->paginate(15)->withQueryString();

        $totalCustomers = Customer::count();
        $totalPointsIssued = Customer::sum('reward_points');
        $platinumCount = Customer::where('membership_tier', 'platinum')->count();
        $goldCount = Customer::where('membership_tier', 'gold')->count();

        return view('customers.index', compact(
            'customers',
            'totalCustomers',
            'totalPointsIssued',
            'platinumCount',
            'goldCount'
        ));
    }

    /**
     * Fast Customer Search & Auto-Register API for POS Terminal
     */
    public function search(Request $request): JsonResponse
    {
        $phone = trim($request->query('phone') ?? '');
        if (!$phone) {
            return response()->json(['success' => false, 'customer' => null, 'is_new' => false]);
        }

        // Exact match first, then partial match
        $customer = Customer::where('phone', $phone)->first()
            ?? Customer::where('phone', 'like', "%{$phone}%")->first();

        if ($customer) {
            return response()->json([
                'success' => true,
                'customer' => $customer,
                'is_new' => false,
            ]);
        }

        // Auto-create new customer if requested (e.g. on Enter key)
        if ($request->boolean('auto_create') && strlen($phone) >= 6) {
            $name = $request->input('name') ?: 'গ্রাহক (' . substr($phone, -4) . ')';
            $customer = Customer::create([
                'name' => $name,
                'phone' => $phone,
                'membership_tier' => 'bronze',
                'reward_points' => 0,
                'total_spent' => 0,
                'total_visits' => 0,
            ]);

            return response()->json([
                'success' => true,
                'customer' => $customer,
                'is_new' => true,
                'message' => 'নতুন কাস্টমার প্রোফাইল তৈরি হয়েছে!',
            ]);
        }

        return response()->json([
            'success' => true,
            'customer' => null,
            'is_new' => false,
        ]);
    }

    /**
     * Store or Update Customer Profile
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'id' => 'nullable|exists:customers,id',
            'name' => 'required|string|max:100',
            'phone' => 'required|string|max:20|unique:customers,phone,' . ($request->id ?? 'NULL') . ',id',
            'email' => 'nullable|email|max:100',
            'address' => 'nullable|string',
            'date_of_birth' => 'nullable|date',
            'reward_points' => 'nullable|integer|min:0',
            'membership_tier' => 'required|in:bronze,silver,gold,platinum',
            'notes' => 'nullable|string',
        ]);

        $customer = Customer::updateOrCreate(
            ['id' => $validated['id'] ?? null],
            $validated
        );

        return response()->json([
            'success' => true,
            'message' => 'কাস্টমার প্রোফাইল সফলভাবে সংরক্ষিত হয়েছে!',
            'customer' => $customer,
        ]);
    }

    /**
     * Add / Adjust Loyalty Points Manually
     */
    public function adjustPoints(Request $request, Customer $customer): JsonResponse
    {
        $validated = $request->validate([
            'points' => 'required|integer',
            'description' => 'required|string',
        ]);

        $newBalance = max(0, $customer->reward_points + $validated['points']);

        LoyaltyTransaction::create([
            'customer_id' => $customer->id,
            'type' => $validated['points'] >= 0 ? 'bonus' : 'adjusted',
            'points' => $validated['points'],
            'balance_after' => $newBalance,
            'description' => $validated['description'],
        ]);

        $customer->update(['reward_points' => $newBalance]);

        return response()->json([
            'success' => true,
            'message' => 'লয়্যালটি পয়েন্টস সফলভাবে আপডেট হয়েছে!',
            'customer' => $customer,
        ]);
    }

    /**
     * Send Simulated / BD Bulk SMS Promo
     */
    public function sendSms(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'phone' => 'required|string',
            'message' => 'required|string|max:500',
        ]);

        // Simulated SMS gateway (Greenweb / BulkSMSBD / SSL Wireless standard payload)
        return response()->json([
            'success' => true,
            'message' => "এসএমএস সফলভাবে {$validated['phone']} নম্বরে পাঠানো হয়েছে!",
            'sms_details' => [
                'recipient' => $validated['phone'],
                'length' => strlen($validated['message']),
                'status' => 'DELIVERED',
                'gateway' => 'Bangladesh Telecom SMS Gateway (Simulated)',
            ],
        ]);
    }

    /**
     * Delete Customer
     */
    public function destroy(Customer $customer): JsonResponse
    {
        $customer->delete();
        return response()->json([
            'success' => true,
            'message' => 'কাস্টমার রেকর্ড মুছে ফেলা হয়েছে!',
        ]);
    }
}
