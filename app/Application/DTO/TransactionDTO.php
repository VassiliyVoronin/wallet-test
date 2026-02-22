<?php
namespace App\Application\DTO;

class TransactionDTO
{
    public function __construct(
        public int $user_id,
        public int $wallet_id,
        public string $type,
        public string $status,
        public string $amount,
        public string $tx_date,
        public ?string $ext_tx_id = null,
    ) {}
}
