<?php
namespace Tests\Feature;

use App\Application\DTO\TransactionDTO;
use App\Application\WithdrawService;
use App\Exceptions\InsufficientFundsException;
use App\Infrastructure\Persistence\EloquentTransactionRepository;
use App\Infrastructure\Persistence\EloquentWalletRepository;
use App\Models\Transaction;
use Tests\TestCase;

class WalletWithdrawTest extends TestCase
{
    protected WithdrawService $service;

    public function setUp(): void
    {
        parent::setUp();

        $this->service = new WithdrawService(new EloquentWalletRepository(), new EloquentTransactionRepository());
    }

    public function test_withdraw_freeze_sum()
    {
        $this->initWallet();
        $this->wallet->available_sum = '500.00000000';
        $this->wallet->save();

        $this->service->withdraw(new TransactionDTO(
            user_id: $this->user->id,
            wallet_id: $this->wallet->id,
            type: 'withdraw',
            status: 'pending',
            amount: '200.00000000',
            tx_date: $txDate ?? now()->toDateString(),
            ext_tx_id: 'tx3'
        ));

        $this->wallet->refresh();

        $this->assertEquals('300.00000000', $this->wallet->available_sum);
        $this->assertEquals('200.00000000', $this->wallet->frozen_sum);
    }

    public function test_withdraw_fails_if_insufficient_funds()
    {
        $this->initWallet();
        $this->wallet->available_sum = '100.00000000';
        $this->wallet->save();

        $this->expectException(
            InsufficientFundsException::class
        );

        $this->service->withdraw(new TransactionDTO(
            user_id: $this->user->id,
            wallet_id: $this->wallet->id,
            type: 'withdraw',
            status: 'pending',
            amount: '200.00000000',
            tx_date: $txDate ?? now()->toDateString(),
            ext_tx_id: 'tx4'
        ));
    }

    public function test_confirm_withdraw()
    {
        $this->initWallet();
        $this->wallet->available_sum = '300.00000000';
        $this->wallet->frozen_sum = '200.00000000';
        $this->wallet->save();

        /** @var Transaction $transaction */
        $transaction = Transaction::factory()->create([
            'user_id' => $this->user->id,
            'wallet_id' => $this->wallet->id,
            'amount' => '200.00000000',
            'type' => 'withdraw',
            'status' => 'pending',
            'tx_date' => $txDate ?? now()->toDateString(),
            'ext_tx_id' => 'tx5'
        ]);

        $this->service->confirm($transaction->ext_tx_id);

        $this->wallet->refresh();

        $this->assertEquals('0.00000000', $this->wallet->frozen_sum);
    }

    public function test_failed_withdraw()
    {
        $this->initWallet();
        $this->wallet->available_sum = '300.00000000';
        $this->wallet->frozen_sum = '200.00000000';
        $this->wallet->save();

        /** @var Transaction $transaction */
        $transaction = Transaction::factory()->create([
            'user_id' => $this->user->id,
            'wallet_id' => $this->wallet->id,
            'amount' => '200.00000000',
            'type' => 'withdraw',
            'status' => 'pending',
            'tx_date' => $txDate ?? now()->toDateString(),
            'ext_tx_id' => 'tx6'
        ]);

        $this->service->fail($transaction->ext_tx_id);

        $this->wallet->refresh();

        $this->assertEquals('500.00000000', $this->wallet->available_sum);
        $this->assertEquals('0.00000000', $this->wallet->frozen_sum);
    }
}
