<?php

namespace App\Domain\Wallet;

use App\Exceptions\InsufficientFundsException;
use App\Domain\ValueObjects\Money;
use App\Models\Wallet;

class WalletEntity
{
    public function __construct(
        private readonly int $id,
        private Money        $available_sum,
        private Money        $frozen_sum
    )
    {
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return Money
     */
    public function getAvailableSum(): Money
    {
        return $this->available_sum;
    }

    /**
     * @return Money
     */
    public function getFrozenSum(): Money
    {
        return $this->frozen_sum;
    }

    /**
     * @param Money $amount
     * @return void
     */
    public function credit(Money $amount): void
    {
        $this->available_sum = $this->available_sum->add($amount);
    }

    /**
     * @throws InsufficientFundsException
     */
    public function freeze(Money $amount): void
    {
        if (bccomp($this->available_sum->getAmount(), $amount->getAmount(), 8) < 0) {
            throw new InsufficientFundsException();
        }

        $this->available_sum = $this->available_sum->subtract($amount);
        $this->frozen_sum = $this->frozen_sum->add($amount);
    }

    /**
     * @param Money $amount
     * @return void
     */
    public function release(Money $amount): void
    {
        $this->frozen_sum = $this->frozen_sum->subtract($amount);
    }

    /**
     * @param Money $amount
     * @return void
     */
    public function rollbackFreeze(Money $amount): void
    {
        $this->frozen_sum = $this->frozen_sum->subtract($amount);
        $this->available_sum = $this->available_sum->add($amount);
    }

    /**
     * @param Wallet $model
     * @return self
     */
    public static function fromModel(Wallet $model): self
    {
        return new self(
            id: $model->id,
            available_sum: new Money($model->available_sum),
            frozen_sum: new Money($model->frozen_sum)
        );
    }
}
