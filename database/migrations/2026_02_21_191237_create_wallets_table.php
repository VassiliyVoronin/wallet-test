<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('wallets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->comment('ID пользователя')->constrained()->cascadeOnDelete();
            $table->decimal('available_sum', 30, 8)->default(0)->comment('Доступная сумма');
            $table->decimal('frozen_sum', 30, 8)->default(0)->comment('Замороженная сумма');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallets');
    }
};
