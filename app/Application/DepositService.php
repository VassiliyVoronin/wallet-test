<?php

namespace App\Application;

use App\Application\DTO\TransactionDTO;
use App\Domain\Transaction\TransactionRepositoryInterface;
use App\Domain\ValueObjects\Money;
use App\Domain\Wallet\WalletRepositoryInterface;
use Illuminate\Support\Facades\DB;

readonly class DepositService
{
    public function __construct(
        private WalletRepositoryInterface      $walletRepository,
        private TransactionRepositoryInterface $transactionRepository
    )
    {
    }

    /**
     * @param TransactionDTO $dto
     * @return void
     */
    public function handle(TransactionDTO $dto ): void
    {
        DB::transaction(function () use ($dto) {
            if ($this->transactionRepository->existExtTxId($dto->ext_tx_id)) {
                return;
            }

            $wallet = $this->walletRepository->lock($dto->wallet_id);
            $wallet->credit(new Money($dto->amount));

            $this->walletRepository->save($wallet);
            $this->transactionRepository->create($dto);
        });
    }
}
