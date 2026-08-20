<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shifts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->nullable()->constrained('branches')->nullOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamp('opened_at')->useCurrent();
            $table->timestamp('closed_at')->nullable();
            $table->decimal('opening_float', 10, 2)->default(0.00);
            $table->decimal('cash_sales', 10, 2)->default(0.00);
            $table->decimal('bkash_sales', 10, 2)->default(0.00);
            $table->decimal('nagad_sales', 10, 2)->default(0.00);
            $table->decimal('rocket_sales', 10, 2)->default(0.00);
            $table->decimal('card_sales', 10, 2)->default(0.00);
            $table->decimal('total_sales', 10, 2)->default(0.00);
            $table->decimal('expected_cash', 10, 2)->default(0.00);
            $table->decimal('actual_cash_counted', 10, 2)->nullable();
            $table->decimal('cash_difference', 10, 2)->nullable();
            $table->text('closing_note')->nullable();
            $table->string('status')->default('open')->comment('open, closed');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shifts');
    }
};
