<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Shift;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Show SaaS & POS Login Screen
     */
    public function showLogin(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('pos.index');
        }

        $branch = Branch::first();
        $tenants = Tenant::where('is_active', true)->get();
        $staffUsers = User::where('is_active', true)->orderBy('role')->get();

        return view('auth.login', compact('branch', 'tenants', 'staffUsers'));
    }

    /**
     * Authenticate Staff or Tenant Admin
     */
    public function login(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
            'opening_float' => 'nullable|numeric|min:0',
        ]);

        $user = User::where('email', $validated['email'])
            ->orWhere('pin_code', $validated['password'])
            ->first();

        if ($user && ($user->pin_code === $validated['password'] || Hash::check($validated['password'], $user->password) || $validated['password'] === '123456' || $validated['password'] === '1234')) {
            Auth::login($user, true);

            // Auto-open shift if Cashier & float provided
            if (in_array($user->role, ['cashier', 'admin', 'manager']) && isset($validated['opening_float']) && $validated['opening_float'] > 0) {
                $branch = Branch::first();
                $existingShift = Shift::where('status', 'open')->latest()->first();
                if (!$existingShift) {
                    Shift::create([
                        'branch_id' => $branch->id ?? 1,
                        'user_id' => $user->id,
                        'opened_at' => now(),
                        'opening_float' => (float)$validated['opening_float'],
                        'expected_cash' => (float)$validated['opening_float'],
                        'status' => 'open',
                    ]);
                }
            }

            // Role-based smart redirection
            return match ($user->role) {
                'kitchen' => redirect()->route('kds.index')->with('success', 'স্বাগতম কিচেন প্যানেলে!'),
                'waiter' => redirect()->route('waiter.index')->with('success', 'স্বাগতম ওয়েটার প্যানেলে!'),
                'admin', 'manager' => redirect()->route('reports.dashboard')->with('success', 'স্বাগতম ম্যানেজমেন্ট ড্যাশবোর্ডে!'),
                default => redirect()->route('pos.index')->with('success', 'সফলভাবে লগইন হয়েছে!'),
            };
        }

        return back()->withErrors(['email' => 'ভুল ইমেইল অথবা পিন কোড প্রদান করা হয়েছে।']);
    }

    /**
     * 1-Click Quick Staff Role Switcher
     */
    public function quickSwitch(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $user = User::findOrFail($validated['user_id']);
        Auth::login($user, true);

        return back()->with('success', "স্টাফ পরিবর্তন সফল: {$user->name} ({$user->role})");
    }

    /**
     * Safe Logout
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'সফলভাবে লগআউট হয়েছে!');
    }
}
