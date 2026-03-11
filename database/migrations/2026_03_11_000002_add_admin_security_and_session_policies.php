<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'session_version')) {
                $table->unsignedInteger('session_version')->default(1)->after('remember_token');
            }
        });

        Schema::table('admins', function (Blueprint $table) {
            if (!Schema::hasColumn('admins', 'two_factor_enabled')) {
                $table->boolean('two_factor_enabled')->default(false)->after('role');
            }
            if (!Schema::hasColumn('admins', 'two_factor_type')) {
                $table->string('two_factor_type')->nullable()->after('two_factor_enabled');
            }
            if (!Schema::hasColumn('admins', 'two_factor_secret')) {
                $table->text('two_factor_secret')->nullable()->after('two_factor_type');
            }
            if (!Schema::hasColumn('admins', 'failed_login_attempts')) {
                $table->unsignedInteger('failed_login_attempts')->default(0)->after('remember_token');
            }
            if (!Schema::hasColumn('admins', 'account_locked_until')) {
                $table->timestamp('account_locked_until')->nullable()->after('failed_login_attempts');
            }
            if (!Schema::hasColumn('admins', 'last_login_at')) {
                $table->timestamp('last_login_at')->nullable()->after('account_locked_until');
            }
            if (!Schema::hasColumn('admins', 'last_login_ip')) {
                $table->string('last_login_ip', 45)->nullable()->after('last_login_at');
            }
            if (!Schema::hasColumn('admins', 'ip_allowlist_enabled')) {
                $table->boolean('ip_allowlist_enabled')->default(false)->after('last_login_ip');
            }
            if (!Schema::hasColumn('admins', 'session_version')) {
                $table->unsignedInteger('session_version')->default(1)->after('ip_allowlist_enabled');
            }
        });

        Schema::create('admin_ip_allowlists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_id')->constrained('admins')->onDelete('cascade');
            $table->string('ip_address', 45);
            $table->string('label')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->unique(['admin_id', 'ip_address']);
        });

        Schema::create('admin_login_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_id')->nullable()->constrained('admins')->nullOnDelete();
            $table->string('ip_address', 45);
            $table->text('user_agent')->nullable();
            $table->string('status');
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admin_login_logs');
        Schema::dropIfExists('admin_ip_allowlists');

        Schema::table('admins', function (Blueprint $table) {
            $columns = [
                'session_version',
                'ip_allowlist_enabled',
                'last_login_ip',
                'last_login_at',
                'account_locked_until',
                'failed_login_attempts',
                'two_factor_secret',
                'two_factor_type',
                'two_factor_enabled',
            ];

            foreach ($columns as $col) {
                if (Schema::hasColumn('admins', $col)) {
                    $table->dropColumn($col);
                }
            }
        });

        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'session_version')) {
                $table->dropColumn('session_version');
            }
        });
    }
};

