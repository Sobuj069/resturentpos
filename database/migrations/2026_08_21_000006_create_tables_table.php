<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->nullable()->constrained('branches')->nullOnDelete();
            $table->string('floor_name')->default('Main Hall')->comment('Main Hall, AC Room, 1st Floor, Rooftop, VIP');
            $table->string('name')->comment('e.g., T-01, T-02, VIP-1');
            $table->integer('capacity')->default(4);
            $table->string('status')->default('available')->comment('available, occupied, billed, reserved');
            $table->unsignedBigInteger('current_order_id')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tables');
    }
};
