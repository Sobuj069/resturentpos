<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Customer;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\RestaurantTable;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class EnterpriseSeeder extends Seeder
{
    public function run(): void
    {
        $branch = Branch::first();
        $admin = User::where('role', 'admin')->first() ?? User::first();

        // 1. Ensure all tables have QR code tokens
        foreach (RestaurantTable::all() as $tbl) {
            if (empty($tbl->qr_code_token)) {
                $tbl->update(['qr_code_token' => Str::random(16)]);
            }
        }

        // 2. Expense Categories
        $categories = [
            ['name' => 'Raw Meat & Fish Bazaar', 'bangla_name' => 'কাঁচাবাজার ও মাংস ক্রয়', 'icon' => 'shopping-bag'],
            ['name' => 'LPG Gas & Utilities', 'bangla_name' => 'গ্যাস সিলিন্ডার ও বিদ্যুৎ বিল', 'icon' => 'flame'],
            ['name' => 'Staff Food & Refreshment', 'bangla_name' => 'স্টাফ নাস্তা ও খাবার', 'icon' => 'coffee'],
            ['name' => 'Packaging & Parcel Boxes', 'bangla_name' => 'প্যাকেজিং বক্স ও ফয়েল পেপার', 'icon' => 'package'],
            ['name' => 'Kitchen Cleaning & Hygiene', 'bangla_name' => 'ক্লিনিং সামগ্রী ও সাবান', 'icon' => 'sparkles'],
            ['name' => 'Maintenance & Repairs', 'bangla_name' => 'মেরামত ও রক্ষণাবেক্ষণ', 'icon' => 'wrench'],
        ];

        foreach ($categories as $cat) {
            ExpenseCategory::firstOrCreate(['name' => $cat['name']], $cat);
        }

        // 3. Sample Expenses for today & yesterday
        $bazaarCat = ExpenseCategory::where('name', 'Raw Meat & Fish Bazaar')->first();
        $gasCat = ExpenseCategory::where('name', 'LPG Gas & Utilities')->first();

        if ($bazaarCat) {
            Expense::firstOrCreate(
                ['title' => 'সকালবেলা কাঁচাবাজার ও মুরগি ক্রয়', 'expense_date' => now()->toDateString()],
                [
                    'branch_id' => $branch->id ?? 1,
                    'category_id' => $bazaarCat->id,
                    'user_id' => $admin->id ?? 1,
                    'amount' => 3500.00,
                    'payment_method' => 'cash',
                    'notes' => 'টাটকা মুরগি ও ধনেপাতা ক্রয়',
                ]
            );
        }

        if ($gasCat) {
            Expense::firstOrCreate(
                ['title' => 'LPG ১২ কেজি গ্যাস সিলিন্ডার রিফিল', 'expense_date' => now()->toDateString()],
                [
                    'branch_id' => $branch->id ?? 1,
                    'category_id' => $gasCat->id,
                    'user_id' => $admin->id ?? 1,
                    'amount' => 1450.00,
                    'payment_method' => 'cash',
                    'notes' => 'রান্নার গ্যাসের জন্য',
                ]
            );
        }

        // 4. Sample Customers
        $customers = [
            ['name' => 'Ashfaqul Islam', 'phone' => '01711223344', 'email' => 'ashfaq@gmail.com', 'total_visits' => 12, 'total_spent' => 28500.00, 'reward_points' => 285, 'membership_tier' => 'gold'],
            ['name' => 'Sadia Rahman', 'phone' => '01819334455', 'email' => 'sadia.r@yahoo.com', 'total_visits' => 6, 'total_spent' => 14200.00, 'reward_points' => 142, 'membership_tier' => 'silver'],
            ['name' => 'Zubair Hossain', 'phone' => '01912556677', 'email' => 'zubair@gmail.com', 'total_visits' => 24, 'total_spent' => 56000.00, 'reward_points' => 560, 'membership_tier' => 'platinum'],
            ['name' => 'Tanjim Ahmed', 'phone' => '01611778899', 'email' => 'tanjim@pos.com', 'total_visits' => 2, 'total_spent' => 4500.00, 'reward_points' => 45, 'membership_tier' => 'bronze'],
        ];

        foreach ($customers as $c) {
            Customer::firstOrCreate(['phone' => $c['phone']], $c);
        }

        // 5. Seed SaaS Tenants
        $sultanTenant = \App\Models\Tenant::firstOrCreate(
            ['slug' => 'sultans-dine'],
            [
                'name' => "Sultan's Dine Bangladesh",
                'slug' => 'sultans-dine',
                'subdomain' => 'sultansdine',
                'owner_name' => 'MD. Kamrul Hasan',
                'email' => 'admin@sultansdine.com',
                'phone' => '01711223344',
                'package_plan' => 'enterprise',
                'monthly_fee' => 5000.00,
                'billing_cycle' => 'monthly',
                'subscription_status' => 'active',
                'trial_ends_at' => null,
                'subscription_expires_at' => now()->addMonths(12),
                'max_branches' => 10,
                'max_staff' => 50,
                'is_active' => true,
            ]
        );

        \App\Models\Tenant::firstOrCreate(
            ['slug' => 'kacchi-bhai'],
            [
                'name' => 'Kacchi Bhai Restaurant',
                'slug' => 'kacchi-bhai',
                'subdomain' => 'kacchibhai',
                'owner_name' => 'Al-Amin Hossain',
                'email' => 'info@kacchibhai.com',
                'phone' => '01819334455',
                'package_plan' => 'growth',
                'monthly_fee' => 2500.00,
                'billing_cycle' => 'monthly',
                'subscription_status' => 'active',
                'subscription_expires_at' => now()->addMonths(6),
                'max_branches' => 3,
                'max_staff' => 15,
                'is_active' => true,
            ]
        );

        \App\Models\Tenant::firstOrCreate(
            ['slug' => 'chillox-burgers'],
            [
                'name' => 'Chillox Burgers & Shakes',
                'slug' => 'chillox-burgers',
                'subdomain' => 'chillox',
                'owner_name' => 'Tanvir Shafi',
                'email' => 'contact@chilloxbd.com',
                'phone' => '01912556677',
                'package_plan' => 'starter',
                'monthly_fee' => 1200.00,
                'billing_cycle' => 'monthly',
                'subscription_status' => 'trial',
                'trial_ends_at' => now()->addDays(10),
                'subscription_expires_at' => now()->addDays(10),
                'max_branches' => 1,
                'max_staff' => 5,
                'is_active' => true,
            ]
        );

        // Link primary branch and users to Sultan's Dine tenant
        if ($branch && $sultanTenant) {
            $branch->update(['tenant_id' => $sultanTenant->id]);
            User::whereNull('tenant_id')->update(['tenant_id' => $sultanTenant->id]);
        }
    }
}
