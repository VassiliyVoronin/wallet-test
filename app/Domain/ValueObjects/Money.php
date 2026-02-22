<?php
namespace App\Domain\ValueObjects;

readonly class Money
{
    public function __construct(
        private string $amount
    ) {}

    public function getAmount(): string
    {
        return $this->amount;
    }

    public function add(Money $money): Money
    {
        return new Money(bcadd($this->amount, $money->amount, 8));
    }

    public function subtract(Money $money): Money
    {
        return new Money(bcsub($this->amount, $money->amount, 8));
    }
}
