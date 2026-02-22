<?php

namespace App\Providers;

use App\Domain\Transaction\TransactionRepositoryInterface;
use App\Domain\Wallet\WalletRepositoryInterface;
use App\Infrastructure\Persistence\EloquentTransactionRepository;
use App\Infrastructure\Persistence\EloquentWalletRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            WalletRepositoryInterface::class,
            EloquentWalletRepository::class
        );

        $this->app->bind(
            TransactionRepositoryInterface::class,
            EloquentTransactionRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
