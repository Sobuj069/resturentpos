<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('branch_id')->nullable()->constrained('branches')->nullOnDelete();
            $table->string('role')->default('cashier')->comment('admin, manager, cashier, waiter, kitchen');
            $table->string('pin_code', 10)->nullable()->comment('4-digit POS quick login PIN');
            $table->string('phone')->nullable();
            $table->boolean('is_active')->default(true);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['branch_id']);
            $table->dropColumn(['branch_id', 'role', 'pin_code', 'phone', 'is_active']);
        });
    }
};
