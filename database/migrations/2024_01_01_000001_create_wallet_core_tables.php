<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->string('code', 2)->unique(); // ISO 3166-1 alpha-2
            $table->string('name');
            $table->string('currency_code', 3);
            $table->timestamps();
        });

        Schema::create('currencies', function (Blueprint $table) {
            $table->id();
            $table->string('code', 3)->unique(); // USD, COP, EUR, etc.
            $table->string('name');
            $table->string('symbol', 10);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('wallets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('currency', 3);
            $table->decimal('balance', 20, 8)->default(0); // High precision for crypto/finance
            $table->boolean('is_frozen')->default(false);
            $table->timestamps();
            
            $table->unique(['user_id', 'currency']);
            $table->foreign('currency')->references('code')->on('currencies');
        });

        Schema::create('exchange_rates', function (Blueprint $table) {
            $table->id();
            $table->string('from_currency', 3);
            $table->string('to_currency', 3);
            $table->decimal('rate', 20, 8);
            $table->timestamps();
            
            $table->foreign('from_currency')->references('code')->on('currencies');
            $table->foreign('to_currency')->references('code')->on('currencies');
        });

        Schema::create('fees', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // transfer, exchange, withdrawal, deposit
            $table->decimal('percentage', 5, 2)->default(0);
            $table->decimal('fixed_amount', 20, 8)->default(0);
            $table->string('currency', 3)->nullable(); // If fixed amount applies
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fees');
        Schema::dropIfExists('exchange_rates');
        Schema::dropIfExists('wallets');
        Schema::dropIfExists('currencies');
        Schema::dropIfExists('countries');
    }
};
