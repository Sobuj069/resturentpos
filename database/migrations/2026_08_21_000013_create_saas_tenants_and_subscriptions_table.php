<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('tenants')) {
            Schema::create('tenants', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique();
                $table->string('subdomain')->nullable()->unique();
                $table->string('owner_name');
                $table->string('email')->unique();
                $table->string('phone')->nullable();
                $table->string('logo')->nullable();
                $table->string('package_plan')->default('growth'); // starter, growth, enterprise
                $table->decimal('monthly_fee', 10, 2)->default(2500.00);
                $table->string('billing_cycle')->default('monthly'); // monthly, yearly
                $table->string('subscription_status')->default('active'); // trial, active, past_due, suspended
                $table->timestamp('trial_ends_at')->nullable();
                $table->timestamp('subscription_expires_at')->nullable();
                $table->integer('max_branches')->default(3);
                $table->integer('max_staff')->default(15);
                $table->json('features')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        if (Schema::hasTable('branches') && !Schema::hasColumn('branches', 'tenant_id')) {
            Schema::table('branches', function (Blueprint $table) {
                $table->foreignId('tenant_id')->nullable()->after('id')->constrained('tenants')->nullOnDelete();
            });
        }

        if (Schema::hasTable('users') && !Schema::hasColumn('users', 'tenant_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->foreignId('tenant_id')->nullable()->after('id')->constrained('tenants')->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('users') && Schema::hasColumn('users', 'tenant_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropForeign(['tenant_id']);
                $table->dropColumn('tenant_id');
            });
        }

        if (Schema::hasTable('branches') && Schema::hasColumn('branches', 'tenant_id')) {
            Schema::table('branches', function (Blueprint $table) {
                $table->dropForeign(['tenant_id']);
                $table->dropColumn('tenant_id');
            });
        }

        Schema::dropIfExists('tenants');
    }
};
