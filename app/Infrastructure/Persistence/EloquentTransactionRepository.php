<?php

namespace App\Infrastructure\Persistence;

use App\Application\DTO\TransactionDTO;
use App\Domain\Transaction\TransactionEntity;
use App\Domain\Transaction\TransactionRepositoryInterface;
use App\Models\Transaction;

class EloquentTransactionRepository implements TransactionRepositoryInterface
{

    /**
     * @param string $ext_tx_id
     * @return bool
     */
    public function existExtTxId(string $ext_tx_id): bool
    {
        $transaction = Transaction::withTrashed()->where('ext_tx_id', $ext_tx_id)->first();
        return (bool)$transaction;
    }

    /**
     * @param TransactionDTO $dto
     * @return TransactionEntity
     */
    public function create(TransactionDTO $dto): TransactionEntity
    {
        $transaction = Transaction::query()->create((array)$dto);
        return TransactionEntity::fromModel($transaction);
    }

    /**
     * @param string $ext_tx_id
     * @return TransactionEntity
     */
    public function findByExtId(string $ext_tx_id): TransactionEntity
    {
        $transaction = Transaction::query()->where('ext_tx_id', $ext_tx_id)->first();
        return TransactionEntity::fromModel($transaction);
    }

    /**
     * @param int $transaction_id
     * @return void
     */
    public function setConfirmed(int $transaction_id): void
    {
        $transaction = Transaction::query()->find($transaction_id);
        $transaction->status = 'confirmed';
        $transaction->save();
    }

    /**
     * @param int $transaction_id
     * @return void
     */
    public function setFailed(int $transaction_id): void
    {
        $transaction = Transaction::query()->find($transaction_id);
        $transaction->status = 'failed';
        $transaction->save();
    }
}
