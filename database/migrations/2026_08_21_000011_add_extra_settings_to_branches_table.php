<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('branches', function (Blueprint $table) {
            $table->string('bkash_number')->nullable()->default('01711-223344');
            $table->string('nagad_number')->nullable()->default('01711-223344');
            $table->string('restaurant_name')->nullable()->default("Sultan's Dine");
        });
    }

    public function down(): void
    {
        Schema::table('branches', function (Blueprint $table) {
            $table->dropColumn(['bkash_number', 'nagad_number', 'restaurant_name']);
        });
    }
};
