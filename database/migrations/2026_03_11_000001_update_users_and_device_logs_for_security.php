<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'mobile_verified_at')) {
                $table->timestamp('mobile_verified_at')->nullable()->after('mobile_phone');
            }
        });

        Schema::table('device_logs', function (Blueprint $table) {
            if (Schema::hasColumn('device_logs', 'device_fingerprint')) {
                try {
                    $table->dropUnique('device_logs_device_fingerprint_unique');
                } catch (\Throwable $e) {
                }
            }

            if (!Schema::hasColumn('device_logs', 'device_type')) {
                $table->string('device_type')->nullable()->after('device_name');
            }

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

        Schema::table('device_logs', function (Blueprint $table) {
            $table->unique(['user_id', 'device_fingerprint'], 'device_logs_user_fingerprint_unique');
        });
    }

    public function down(): void
    {
        Schema::table('device_logs', function (Blueprint $table) {
            try {
                $table->dropUnique('device_logs_user_fingerprint_unique');
            } catch (\Throwable $e) {
            }
        });

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
            if (Schema::hasColumn('device_logs', 'device_type')) {
                $table->dropColumn('device_type');
            }

            try {
                $table->unique('device_fingerprint');
            } catch (\Throwable $e) {
            }
        });

        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'mobile_verified_at')) {
                $table->dropColumn('mobile_verified_at');
            }
        });
    }
};

