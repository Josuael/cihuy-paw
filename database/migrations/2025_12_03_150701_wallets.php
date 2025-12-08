<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    // public function up(): void
    // {
    //     Schema::create('wallets', function (Blueprint $table) {
    //         $table->id('wallet_id');
    //         $table->unsignedInteger('member_id');
    //         $table->decimal('balance', 12, 2)->default(0);
    //         $table->timestamps();

    //         $table->foreign('member_id')->references('member_id')->on('members')->onDelete('cascade')->onUpdate('cascade');
    //     });
    // }

    // public function down(): void
    // {
    //     Schema::dropIfExists('wallets');
    // }
};
