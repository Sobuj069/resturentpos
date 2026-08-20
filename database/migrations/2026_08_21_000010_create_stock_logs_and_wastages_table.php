<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->nullable()->constrained('branches')->nullOnDelete();
            $table->foreignId('ingredient_id')->constrained('ingredients')->cascadeOnDelete();
            $table->string('type')->comment('purchase, order_deduction, wastage, adjustment, return');
            $table->decimal('quantity_change', 12, 3);
            $table->decimal('balance_after', 12, 3);
            $table->unsignedBigInteger('reference_id')->nullable()->comment('order_id or purchase_id or wastage_id');
            $table->text('notes')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('wastages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->nullable()->constrained('branches')->nullOnDelete();
            $table->foreignId('ingredient_id')->nullable()->constrained('ingredients')->nullOnDelete();
            $table->foreignId('item_id')->nullable()->constrained('items')->nullOnDelete();
            $table->decimal('quantity', 12, 3);
            $table->string('unit')->default('kg');
            $table->decimal('cost_impact', 10, 2)->default(0.00);
            $table->string('reason')->comment('spoiled, burnt, expired, spill, customer_reject');
            $table->text('notes')->nullable();
            $table->foreignId('logged_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wastages');
        Schema::dropIfExists('stock_logs');
    }
};
