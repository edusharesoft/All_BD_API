<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone');
            $table->string('address')->nullable();
            $table->string('method')->default('shatajPay');
            $table->decimal('amount', 12, 2);
            $table->string('currency', 10)->default('BDT');
            // initialize -> pending -> completed | failed | awaiting_verification
            $table->string('status')->default('initialize')->index();
            $table->string('transaction_id')->unique();
            $table->string('order_id')->unique();
            $table->json('meta')->nullable(); // your own data (event, cart id, ...)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
