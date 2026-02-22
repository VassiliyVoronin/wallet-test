<?php


namespace Tests\Feature;

use App\Application\DepositService;
use App\Application\DTO\TransactionDTO;
use App\Infrastructure\Persistence\EloquentTransactionRepository;
use App\Infrastructure\Persistence\EloquentWalletRepository;
use App\Models\Wallet;
use Exception;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class WalletDepositTest extends TestCase
{
    protected DepositService $service;

    public function setUp(): void
    {
        parent::setUp();

        $this->service = new DepositService(new EloquentWalletRepository(), new EloquentTransactionRepository());
    }

    /**
     * @return void
     */
    public function test_it_deposit_wallet_successfully()
    {
        $this->initWallet();
        $ext_tx_id = 'tx1';
        $amount = '100.50000000';

        $this->service->handle(new TransactionDTO(
                user_id: $this->user->id,
                wallet_id: $this->wallet->id,
                type: 'deposit',
                status: 'confirmed',
                amount: $amount,
                tx_date: $txDate ?? now()->toDateString(),
                ext_tx_id: $ext_tx_id
            )
        );
        $this->assertDatabaseHas('transactions', [
            'wallet_id' => $this->wallet->id,
            'ext_tx_id' => $ext_tx_id,
            'type' => 'deposit'
        ]);

        $this->wallet->refresh();

        $this->assertEquals($amount, $this->wallet->available_sum);
    }

    /**
     * @return void
     */
    public function test_duplicate_ext_tx_is_ignored()
    {
        $this->initWallet();

        $ext_tx_id = 'tx2';
        $dto = new TransactionDTO(
            user_id: $this->user->id,
            wallet_id: $this->wallet->id,
            type: 'deposit',
            status: 'confirmed',
            amount: '200.50000000',
            tx_date: $txDate ?? now()->toDateString(),
            ext_tx_id: $ext_tx_id
        );

        $this->service->handle($dto);
        $this->service->handle($dto);

        $this->wallet->refresh();

        $this->assertEquals('200.50000000', $this->wallet->available_sum);
        $this->assertEquals(1, DB::table('transactions')->where('ext_tx_id', $ext_tx_id)->count());
    }
}
