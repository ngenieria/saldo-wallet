<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('qr_codes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('token')->unique(); // Unique token for QR scanning
            $table->decimal('amount', 20, 8)->nullable(); // Can be null for open payments
            $table->string('currency', 3)->default('USD');
            $table->string('description')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });

        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_id')->unique(); // Unique reference
            $table->foreignId('sender_wallet_id')->nullable()->constrained('wallets')->nullOnDelete();
            $table->foreignId('receiver_wallet_id')->nullable()->constrained('wallets')->nullOnDelete();
            $table->decimal('amount', 20, 8);
            $table->string('currency', 3);
            $table->string('type'); // transfer, exchange, deposit, withdrawal, payment
            $table->string('status'); // pending, completed, failed, cancelled
            $table->decimal('fee', 20, 8)->default(0);
            $table->string('reference')->nullable();
            $table->text('description')->nullable();
            $table->foreignId('qr_code_id')->nullable()->constrained('qr_codes')->nullOnDelete();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('withdrawals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('wallet_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 20, 8);
            $table->string('currency', 3);
            $table->string('method'); // bank_transfer, crypto, etc.
            $table->text('details'); // JSON details
            $table->string('status'); // pending, approved, rejected
            $table->foreignId('processed_by')->nullable()->constrained('admins')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('deposits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('wallet_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 20, 8);
            $table->string('currency', 3);
            $table->string('method'); // card, transfer, crypto
            $table->string('status'); // pending, completed, failed
            $table->string('external_reference')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('deposits');
        Schema::dropIfExists('withdrawals');
        Schema::dropIfExists('transactions');
        Schema::dropIfExists('qr_codes');
    }
};
