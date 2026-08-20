<?php

namespace App\Providers;

use App\Models\Branch;
use App\Models\Shift;
use App\Models\User;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Share live dynamic branch, active cashier shift, and users to all views
        View::composer('*', function ($view) {
            $branch = Branch::first() ?? new Branch([
                'name' => "Sultan's Dine",
                'restaurant_name' => "Sultan's Dine",
                'code' => 'MAIN',
                'phone' => '+880 1711-223344',
                'bin_number' => '001928374-0102',
                'default_vat_rate' => 5.00,
                'currency_symbol' => '৳',
                'bkash_number' => '01711-223344',
                'nagad_number' => '01711-223344',
            ]);

            $activeShift = Shift::where('status', 'open')->with('user')->latest()->first();
            $currentUser = $activeShift ? $activeShift->user : (User::where('role', 'cashier')->first() ?? User::first());

            $view->with([
                'currentBranch' => $branch,
                'activeShift' => $activeShift,
                'currentUser' => $currentUser,
            ]);
        });
    }
}
