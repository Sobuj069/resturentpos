<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Customers & Loyalty
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->nullable()->constrained('branches')->nullOnDelete();
            $table->string('name');
            $table->string('phone')->unique()->index();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->integer('total_visits')->default(0);
            $table->decimal('total_spent', 12, 2)->default(0.00);
            $table->integer('reward_points')->default(0);
            $table->string('membership_tier')->default('bronze')->comment('bronze, silver, gold, platinum');
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('loyalty_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->foreignId('order_id')->nullable()->constrained('orders')->nullOnDelete();
            $table->string('type')->default('earned')->comment('earned, redeemed, adjusted, bonus');
            $table->integer('points');
            $table->integer('balance_after');
            $table->decimal('discount_value', 10, 2)->default(0.00);
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // 2. Expense Management
        Schema::create('expense_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('bangla_name')->nullable();
            $table->string('icon')->default('receipt');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->nullable()->constrained('branches')->nullOnDelete();
            $table->foreignId('category_id')->constrained('expense_categories')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('title');
            $table->decimal('amount', 12, 2);
            $table->string('payment_method')->default('cash');
            $table->date('expense_date');
            $table->string('receipt_number')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 3. Multi-Branch Stock Transfers
        Schema::create('stock_transfers', function (Blueprint $table) {
            $table->id();
            $table->string('transfer_number')->unique();
            $table->foreignId('source_branch_id')->constrained('branches')->cascadeOnDelete();
            $table->foreignId('destination_branch_id')->constrained('branches')->cascadeOnDelete();
            $table->foreignId('requested_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('status')->default('pending')->comment('pending, in_transit, received, cancelled');
            $table->timestamp('dispatched_at')->nullable();
            $table->timestamp('received_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('stock_transfer_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transfer_id')->constrained('stock_transfers')->cascadeOnDelete();
            $table->foreignId('ingredient_id')->constrained('ingredients')->cascadeOnDelete();
            $table->decimal('quantity_sent', 10, 3);
            $table->decimal('quantity_received', 10, 3)->nullable();
            $table->string('unit')->default('kg');
        });

        // 4. Update Tables table for QR tokens
        if (Schema::hasTable('tables') && !Schema::hasColumn('tables', 'qr_code_token')) {
            Schema::table('tables', function (Blueprint $table) {
                $table->string('qr_code_token', 64)->nullable()->unique()->after('name');
            });
        }

        // 5. Update Orders table to link Customer and Loyalty
        if (Schema::hasTable('orders') && !Schema::hasColumn('orders', 'customer_id')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->foreignId('customer_id')->nullable()->constrained('customers')->nullOnDelete()->after('table_id');
                $table->integer('points_earned')->default(0)->after('grand_total');
                $table->integer('points_redeemed')->default(0)->after('points_earned');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_transfer_items');
        Schema::dropIfExists('stock_transfers');
        Schema::dropIfExists('expenses');
        Schema::dropIfExists('expense_categories');
        Schema::dropIfExists('loyalty_transactions');
        Schema::dropIfExists('customers');
    }
};
