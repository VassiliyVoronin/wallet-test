<?php
namespace  App\Domain\Transaction;
use App\Application\DTO\TransactionDTO;

interface TransactionRepositoryInterface {
    public function existExtTxId(string $ext_tx_id): bool;
    public function create(TransactionDTO $dto): TransactionEntity;

    public function findByExtId(string $ext_tx_id): TransactionEntity;

    public function setConfirmed(int $transaction_id): void;

    public function setFailed(int $transaction_id): void;
}
