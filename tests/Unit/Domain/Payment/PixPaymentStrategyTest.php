<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Payment;

use App\Domain\Payment\PixPaymentStrategy;
use PHPUnit\Framework\TestCase;

class PixPaymentStrategyTest extends TestCase
{
    public function testCalculateFinalPriceWithDiscount(): void
    {
        $strategy = new PixPaymentStrategy();
        $finalPrice = $strategy->calculateFinalPrice(100.00, 5.00);

        $this->assertEquals(85.00, $finalPrice);
    }
}
