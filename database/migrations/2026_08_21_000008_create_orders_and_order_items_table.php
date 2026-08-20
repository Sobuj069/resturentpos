<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->nullable()->constrained('branches')->nullOnDelete();
            $table->foreignId('shift_id')->nullable()->constrained('shifts')->nullOnDelete();
            $table->foreignId('table_id')->nullable()->constrained('tables')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete()->comment('Cashier/Creator');
            $table->foreignId('waiter_id')->nullable()->constrained('users')->nullOnDelete();

            $table->string('order_number')->unique();
            $table->string('invoice_number')->nullable()->unique();
            $table->string('mushak_number')->nullable()->comment('NBR Mushak 6.3 Chalan Number');
            $table->string('order_type')->default('dine_in')->comment('dine_in, takeaway, delivery, drive_thru');
            $table->string('token_number', 20)->nullable()->comment('Kitchen/Takeaway token');

            $table->string('customer_name')->nullable()->default('Guest');
            $table->string('customer_phone')->nullable();
            $table->text('customer_address')->nullable();

            $table->decimal('subtotal', 10, 2)->default(0.00);
            $table->string('discount_type')->nullable()->comment('percentage, fixed');
            $table->decimal('discount_value', 10, 2)->default(0.00);
            $table->decimal('discount_amount', 10, 2)->default(0.00);
            $table->decimal('vat_percent', 5, 2)->default(5.00);
            $table->decimal('vat_amount', 10, 2)->default(0.00);
            $table->decimal('sd_amount', 10, 2)->default(0.00)->comment('Supplementary Duty');
            $table->decimal('service_charge', 10, 2)->default(0.00);
            $table->decimal('grand_total', 10, 2)->default(0.00);

            $table->decimal('paid_amount', 10, 2)->default(0.00);
            $table->decimal('change_amount', 10, 2)->default(0.00);

            $table->string('payment_status')->default('unpaid')->comment('unpaid, partial, paid');
            $table->string('payment_method')->nullable()->comment('cash, bkash, nagad, rocket, card, split');
            $table->string('status')->default('pending')->comment('pending, cooking, ready, completed, cancelled');

            $table->text('notes')->nullable();
            $table->string('offline_uuid')->nullable()->unique();
            $table->boolean('is_synced')->default(true);
            $table->timestamp('billed_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->foreignId('item_id')->constrained('items')->cascadeOnDelete();
            $table->foreignId('variant_id')->nullable()->constrained('item_variants')->nullOnDelete();

            $table->string('item_name');
            $table->string('variant_name')->nullable();
            $table->decimal('unit_price', 10, 2)->default(0.00);
            $table->integer('quantity')->default(1);
            $table->decimal('subtotal', 10, 2)->default(0.00);
            $table->decimal('vat_amount', 10, 2)->default(0.00);
            $table->decimal('total_price', 10, 2)->default(0.00);

            $table->text('notes')->nullable();
            $table->string('kitchen_station')->default('main_kitchen');
            $table->string('kitchen_status')->default('pending')->comment('pending, cooking, ready, served');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('ready_at')->nullable();
            $table->timestamp('served_at')->nullable();
            $table->timestamps();
        });

        Schema::create('order_item_modifiers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_item_id')->constrained('order_items')->cascadeOnDelete();
            $table->foreignId('modifier_id')->nullable()->constrained('modifiers')->nullOnDelete();
            $table->string('name');
            $table->decimal('price', 10, 2)->default(0.00);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_item_modifiers');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
    }
};
