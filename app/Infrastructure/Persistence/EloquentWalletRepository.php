<?php

namespace App\Infrastructure\Persistence;

use App\Domain\Wallet\WalletEntity;
use App\Domain\Wallet\WalletRepositoryInterface;
use App\Models\Wallet;

class EloquentWalletRepository implements WalletRepositoryInterface
{
    /**
     * @param int $id
     * @return WalletEntity
     */
    public function lock(int $id): WalletEntity
    {
        $wallet = Wallet::query()
            ->where('id', $id)
            ->lockForUpdate()
            ->first();

        return WalletEntity::fromModel($wallet);
    }

    public function save(WalletEntity $entity): void
    {
        $wallet = Wallet::query()->find($entity->getId());

        $wallet->available_sum = $entity->getAvailableSum()->getAmount();
        $wallet->frozen_sum = $entity->getFrozenSum()->getAmount();

        $wallet->save();
    }
}
