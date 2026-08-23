<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    public function up(): void
    {
        // Ensure default SuperAdmin account exists in database
        User::updateOrCreate(
            ['email' => 'superadmin@pos.com'],
            [
                'name' => 'System Super Admin',
                'phone' => '01700000000',
                'password' => Hash::make('password'),
                'role' => 'superadmin',
                'pin_code' => '7777',
                'is_active' => true,
            ]
        );

        // Also ensure admin@pos.com has superadmin role access if used
        User::where('email', 'admin@pos.com')->update([
            'role' => 'superadmin'
        ]);
    }

    public function down(): void
    {
        //
    }
};
