<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('users', 'salary_type')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('salary_type')->default('monthly')->after('role'); // daily, weekly, monthly
                $table->decimal('base_salary', 10, 2)->default(0.00)->after('salary_type');
            });
        }

        if (!Schema::hasColumn('expenses', 'staff_user_id')) {
            Schema::table('expenses', function (Blueprint $table) {
                $table->unsignedBigInteger('staff_user_id')->nullable()->after('user_id');
                $table->string('salary_period')->nullable()->after('staff_user_id'); // daily, weekly, monthly, advance
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('users', 'salary_type')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn(['salary_type', 'base_salary']);
            });
        }

        if (Schema::hasColumn('expenses', 'staff_user_id')) {
            Schema::table('expenses', function (Blueprint $table) {
                $table->dropColumn(['staff_user_id', 'salary_period']);
            });
        }
    }
};
