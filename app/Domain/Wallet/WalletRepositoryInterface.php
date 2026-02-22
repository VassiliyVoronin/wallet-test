<?php

namespace App\Domain\Wallet;
use App\Domain\ValueObjects\Money;

interface WalletRepositoryInterface
{
    public function lock(int $id): WalletEntity;
    public function save(WalletEntity $entity): void;
}
