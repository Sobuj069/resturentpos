<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ingredients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->nullable()->constrained('branches')->nullOnDelete();
            $table->string('name');
            $table->string('bangla_name')->nullable();
            $table->string('unit')->default('kg')->comment('kg, gm, litre, ml, pcs, pkt');
            $table->decimal('current_stock', 12, 3)->default(0.000);
            $table->decimal('alert_stock', 12, 3)->default(5.000);
            $table->decimal('cost_per_unit', 10, 2)->default(0.00);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('item_recipes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained('items')->cascadeOnDelete();
            $table->foreignId('variant_id')->nullable()->constrained('item_variants')->cascadeOnDelete();
            $table->foreignId('ingredient_id')->constrained('ingredients')->cascadeOnDelete();
            $table->decimal('quantity_required', 12, 4)->default(0.0000)->comment('Quantity in ingredient unit');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('item_recipes');
        Schema::dropIfExists('ingredients');
    }
};
