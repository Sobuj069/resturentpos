<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Shift;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class SettingController extends Controller
{
    /**
     * Display System & Branch Settings
     */
    public function index(Request $request): View
    {
        $branch = Branch::first() ?? Branch::create([
            'name' => "Sultan's Dine - Dhanmondi",
            'restaurant_name' => "Sultan's Dine",
            'code' => 'DHD01',
            'phone' => '+880 1711-223344',
            'email' => 'dhanmondi@sultanspos.com',
            'address' => 'House #34, Road #10/A, Dhanmondi R/A, Dhaka-1209',
            'bin_number' => '001928374-0102',
            'mushak_code' => '6.3',
            'default_vat_rate' => 5.00,
            'default_sd_rate' => 0.00,
            'currency' => 'BDT',
            'currency_symbol' => '৳',
            'bkash_number' => '01711-223344',
            'nagad_number' => '01711-223344',
        ]);

        $users = User::orderBy('role')->orderBy('name')->get();
        $recentShifts = Shift::with('user')->latest()->take(10)->get();

        return view('settings.index', compact('branch', 'users', 'recentShifts'));
    }

    /**
     * Update Restaurant & Branch Settings
     */
    public function updateBranch(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'restaurant_name' => 'required|string|max:150',
            'name' => 'required|string|max:150',
            'code' => 'required|string|max:20',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:100',
            'address' => 'nullable|string',
            'bin_number' => 'nullable|string|max:50',
            'mushak_code' => 'nullable|string|max:20',
            'default_vat_rate' => 'required|numeric|min:0|max:100',
            'default_sd_rate' => 'nullable|numeric|min:0|max:100',
            'currency' => 'required|string|max:10',
            'currency_symbol' => 'required|string|max:10',
            'bkash_number' => 'nullable|string|max:30',
            'nagad_number' => 'nullable|string|max:30',
        ]);

        $branch = Branch::first() ?? new Branch();
        $branch->fill($validated);
        $branch->save();

        return response()->json([
            'success' => true,
            'message' => 'রেস্টুরেন্ট ও ব্রাঞ্চের সেটিংস সফলভাবে আপডেট হয়েছে!',
            'branch' => $branch,
        ]);
    }

    /**
     * Store or Update Staff User
     */
    public function storeUser(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'id' => 'nullable|exists:users,id',
            'name' => 'required|string|max:100',
            'email' => 'nullable|email|max:100',
            'role' => 'required|in:admin,manager,cashier,waiter,kitchen',
            'pin_code' => 'required|string|max:10',
            'phone' => 'nullable|string|max:30',
            'is_active' => 'boolean',
        ]);

        $user = User::updateOrCreate(
            ['id' => $validated['id'] ?? null],
            [
                'name' => $validated['name'],
                'email' => $validated['email'] ?: strtolower(str_replace(' ', '', $validated['name'])) . '@pos.com',
                'password' => Hash::make($validated['pin_code']),
                'role' => $validated['role'],
                'pin_code' => $validated['pin_code'],
                'phone' => $validated['phone'] ?? null,
                'is_active' => $request->boolean('is_active', true),
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'স্টাফ মেম্বার সফলভাবে সংরক্ষিত হয়েছে!',
            'user' => $user,
        ]);
    }

    /**
     * Delete Staff User
     */
    public function deleteUser(User $user): JsonResponse
    {
        if ($user->role === 'admin' && User::where('role', 'admin')->count() <= 1) {
            return response()->json(['success' => false, 'message' => 'অন্তত একজন অ্যাডমিন থাকা আবশ্যক!'], 422);
        }

        $user->delete();
        return response()->json([
            'success' => true,
            'message' => 'স্টাফ একাউন্ট মুছে ফেলা হয়েছে!',
        ]);
    }
}
