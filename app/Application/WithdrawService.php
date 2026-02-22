<?php

namespace App\Application;

use App\Application\DTO\TransactionDTO;
use App\Domain\Transaction\TransactionRepositoryInterface;
use App\Domain\Wallet\WalletRepositoryInterface;
use Illuminate\Support\Facades\DB;
use App\Domain\ValueObjects\Money;

readonly class WithdrawService
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
    public function withdraw(TransactionDTO $dto): void
    {
        DB::transaction(function () use ($dto) {

            $wallet = $this->walletRepository->lock($dto->wallet_id);

            $wallet->freeze(new Money($dto->amount));

            $this->walletRepository->save($wallet);

            $this->transactionRepository->create($dto);
        });
    }

    /**
     * @param string $ext_tx_id
     * @return void
     */
    public function confirm(string $ext_tx_id): void
    {
        DB::transaction(function () use ($ext_tx_id) {
            $transaction = $this->transactionRepository->findByExtId($ext_tx_id);

            if ($transaction->isPending()) {
                $wallet = $this->walletRepository->lock($transaction->wallet_id);

                $wallet->release(new Money($transaction->amount));

                $this->walletRepository->save($wallet);

                $this->transactionRepository->setConfirmed($transaction->id);
            }
        });
    }

    /**
     * @param string $ext_tx_id
     * @return void
     */
    public function fail(string $ext_tx_id): void
    {
        DB::transaction(function () use ($ext_tx_id) {

            $transaction = $this->transactionRepository->findByExtId($ext_tx_id);
            if ($transaction->isPending()) {
                $wallet = $this->walletRepository->lock($transaction->wallet_id);

                $wallet->rollbackFreeze(new Money($transaction->amount));

                $this->walletRepository->save($wallet);

                $this->transactionRepository->setFailed($transaction->id);
            }
        });
    }
}
