<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('wallet_transactions', function (Blueprint $table) {
            $table->id('transaction_id');
            $table->unsignedBigInteger('wallet_id');
            $table->decimal('amount', 12, 2);
            $table->enum('type', ['topup', 'payment', 'adjustment']);
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('approved_by')->nullable();
            $table->timestamps();

            $table->foreign('wallet_id')->references('wallet_id')->on('wallets')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('created_by')->references('user_id')->on('users')->nullOnDelete();
            $table->foreign('approved_by')->references('user_id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wallet_transactions');
    }
};
