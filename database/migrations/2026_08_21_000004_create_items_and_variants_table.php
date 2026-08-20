<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained('branches')->nullOnDelete();
            $table->string('name');
            $table->string('bangla_name')->nullable();
            $table->string('sku')->nullable()->unique();
            $table->string('barcode')->nullable()->index();
            $table->text('description')->nullable();
            $table->decimal('cost_price', 10, 2)->default(0.00);
            $table->decimal('selling_price', 10, 2)->default(0.00);
            $table->decimal('vat_percent', 5, 2)->default(5.00);
            $table->decimal('sd_percent', 5, 2)->default(0.00);
            $table->string('image')->nullable();
            $table->string('kitchen_station')->default('main_kitchen')->comment('main_kitchen, grill, drinks_bar, dessert');
            $table->boolean('has_variants')->default(false);
            $table->boolean('is_available')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
        });

        Schema::create('item_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained('items')->cascadeOnDelete();
            $table->string('name')->comment('e.g., Regular, Large, Half, Full, 1:1, 1:2');
            $table->decimal('cost_price', 10, 2)->default(0.00);
            $table->decimal('price', 10, 2)->default(0.00);
            $table->boolean('is_available')->default(true);
            $table->timestamps();
        });

        Schema::create('modifiers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->nullable()->constrained('branches')->nullOnDelete();
            $table->string('name');
            $table->string('bangla_name')->nullable();
            $table->decimal('price', 10, 2)->default(0.00);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('item_modifier', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained('items')->cascadeOnDelete();
            $table->foreignId('modifier_id')->constrained('modifiers')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('item_modifier');
        Schema::dropIfExists('modifiers');
        Schema::dropIfExists('item_variants');
        Schema::dropIfExists('items');
    }
};
