<?php

namespace App\Domain\Transaction;

use App\Models\Transaction;

class TransactionEntity
{
    public function __construct(
        public int $id,
        public int $user_id,
        public int $wallet_id,
        public string $type,
        public string $status,
        public string $amount,
        public string $tx_date,
        public ?string $ext_tx_id = null,
    )
    {
    }

    /**
     * @return string
     */
    public function isPending(): string
    {
        return $this->status === 'pending';
    }

    /**
     * @param Transaction $model
     * @return self
     */
    public static function fromModel(Transaction $model): self
    {
        return new self(
            id: $model->id,
            user_id: $model->user_id,
            wallet_id: $model->wallet_id,
            type: $model->type,
            status: $model->status,
            amount: $model->amount,
            tx_date: $model->tx_date,
            ext_tx_id: $model->ext_tx_id
        );
    }
}
