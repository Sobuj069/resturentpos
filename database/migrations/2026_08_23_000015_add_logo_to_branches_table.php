<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('branches', 'logo')) {
            Schema::table('branches', function (Blueprint $table) {
                $table->string('logo')->nullable()->after('restaurant_name');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('branches', 'logo')) {
            Schema::table('branches', function (Blueprint $table) {
                $table->dropColumn('logo');
            });
        }
    }
};
