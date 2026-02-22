<?php

use App\Models\Transaction;
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
        Schema::create('transactions', function (Blueprint $table) {
            $types = array_keys(Transaction::TYPES);
            $statuses = array_keys(Transaction::STATUSES);

            $table->id();
            $table->foreignId('user_id')->comment('ID пользователя')->constrained()->cascadeOnDelete();
            $table->foreignId('wallet_id')->comment('ID кошелька')->constrained()->cascadeOnDelete();
            $table->string('ext_tx_id')->nullable()->unique()->comment('Внешний идентификатор транзакции');
            $table->enum('type', $types)->comment('Key типа транзакции');
            $table->enum('status', $statuses)->comment('Key статуса транзакции');
            $table->decimal('amount')->comment('Сумма транзакции');
            $table->dateTime('tx_date')->comment('Дата/время транзакции');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
