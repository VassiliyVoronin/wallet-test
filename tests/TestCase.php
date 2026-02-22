<?php

namespace Tests;

use App\Models\User;
use App\Models\Wallet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;

    protected User $user;

    protected Wallet $wallet;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    /**
     * @return void
     */
    protected function initWallet(): void
    {
        $this->wallet = Wallet::factory()->create([
            'user_id' => $this->user->id,
            'available_sum' => '0',
            'frozen_sum' => '0'
        ]);
    }
}
