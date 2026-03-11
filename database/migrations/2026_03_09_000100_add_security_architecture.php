<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('two_factor_enabled')->default(false)->after('kyc_status');
            $table->string('two_factor_type')->nullable()->after('two_factor_enabled');
            $table->text('two_factor_secret')->nullable()->after('two_factor_type');
            $table->text('two_factor_recovery_codes')->nullable()->after('two_factor_secret');
            $table->unsignedInteger('failed_login_attempts')->default(0)->after('remember_token');
            $table->timestamp('account_locked_until')->nullable()->after('failed_login_attempts');
            $table->timestamp('last_login_at')->nullable()->after('account_locked_until');
            $table->string('last_login_ip', 45)->nullable()->after('last_login_at');
            $table->boolean('is_flagged')->default(false)->after('last_login_ip');
        });

        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('admin_id')->nullable()->constrained('admins')->nullOnDelete();
            $table->string('action');
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
        });

        Schema::create('fraud_alerts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('transaction_id')->nullable()->constrained('transactions')->nullOnDelete();
            $table->string('type');
            $table->string('severity')->default('medium');
            $table->text('description')->nullable();
            $table->boolean('is_resolved')->default(false);
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
        });

        Schema::create('aml_flags', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('transaction_id')->nullable()->constrained('transactions')->nullOnDelete();
            $table->string('type');
            $table->text('description')->nullable();
            $table->string('status')->default('pending');
            $table->timestamps();
        });

        Schema::create('api_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('method', 10);
            $table->string('path');
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->unsignedInteger('status_code')->nullable();
            $table->unsignedInteger('duration_ms')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });

        Schema::create('one_time_codes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('code');
            $table->string('purpose');
            $table->unsignedInteger('attempts')->default(0);
            $table->timestamp('expires_at');
            $table->timestamp('consumed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('transaction_limits', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_global')->default(true);
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->decimal('per_transaction_limit', 20, 8)->nullable();
            $table->decimal('daily_limit', 20, 8)->nullable();
            $table->decimal('monthly_limit', 20, 8)->nullable();
            $table->string('currency', 3)->nullable();
            $table->timestamps();
        });

        if (Schema::hasTable('device_logs')) {
            Schema::table('device_logs', function (Blueprint $table) {
                if (!Schema::hasColumn('device_logs', 'browser')) {
                    $table->string('browser')->nullable()->after('os');
                }
                if (!Schema::hasColumn('device_logs', 'ip_address')) {
                    $table->string('ip_address', 45)->nullable()->after('browser');
                }
                if (!Schema::hasColumn('device_logs', 'location')) {
                    $table->string('location')->nullable()->after('ip_address');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('device_logs')) {
            Schema::table('device_logs', function (Blueprint $table) {
                if (Schema::hasColumn('device_logs', 'location')) {
                    $table->dropColumn('location');
                }
                if (Schema::hasColumn('device_logs', 'ip_address')) {
                    $table->dropColumn('ip_address');
                }
                if (Schema::hasColumn('device_logs', 'browser')) {
                    $table->dropColumn('browser');
                }
            });
        }
        Schema::dropIfExists('transaction_limits');
        Schema::dropIfExists('one_time_codes');
        Schema::dropIfExists('api_logs');
        Schema::dropIfExists('aml_flags');
        Schema::dropIfExists('fraud_alerts');
        Schema::dropIfExists('audit_logs');
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'is_flagged')) $table->dropColumn('is_flagged');
            if (Schema::hasColumn('users', 'last_login_ip')) $table->dropColumn('last_login_ip');
            if (Schema::hasColumn('users', 'last_login_at')) $table->dropColumn('last_login_at');
            if (Schema::hasColumn('users', 'account_locked_until')) $table->dropColumn('account_locked_until');
            if (Schema::hasColumn('users', 'failed_login_attempts')) $table->dropColumn('failed_login_attempts');
            if (Schema::hasColumn('users', 'two_factor_recovery_codes')) $table->dropColumn('two_factor_recovery_codes');
            if (Schema::hasColumn('users', 'two_factor_secret')) $table->dropColumn('two_factor_secret');
            if (Schema::hasColumn('users', 'two_factor_type')) $table->dropColumn('two_factor_type');
            if (Schema::hasColumn('users', 'two_factor_enabled')) $table->dropColumn('two_factor_enabled');
        });
    }
};

