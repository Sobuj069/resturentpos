<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->string('bin_number')->nullable()->comment('NBR Business Identification Number');
            $table->string('mushak_code')->nullable()->default('6.3');
            $table->decimal('default_vat_rate', 5, 2)->default(5.00);
            $table->decimal('default_sd_rate', 5, 2)->default(0.00);
            $table->string('currency')->default('BDT');
            $table->string('currency_symbol')->default('৳');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('branches');
    }
};
