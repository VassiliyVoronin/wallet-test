<?php
namespace Tests\Unit;

use App\Domain\ValueObjects\Money;
use PHPUnit\Framework\TestCase;

class MoneyTest extends TestCase
{
    public function test_money_add()
    {
        $m1 = new Money('100.00000000');
        $m2 = new Money('250.00000000');

        $result = $m1->add($m2);

        $this->assertEquals('350.00000000', $result->getAmount());
    }
}
