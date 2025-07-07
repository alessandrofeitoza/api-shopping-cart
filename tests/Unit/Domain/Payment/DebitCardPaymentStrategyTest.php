<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Payment;

use App\Domain\Payment\DebitCardPaymentStrategy;
use App\Domain\Payment\Exception\InvalidCardDataException;
use PHPUnit\Framework\TestCase;

class DebitCardPaymentStrategyTest extends TestCase
{
    public function testCalculateFinalPriceWithDiscountAndCardData(): void
    {
        $strategy = new DebitCardPaymentStrategy();

        $finalPrice = $strategy->calculateFinalPrice(200.00, 10.00, [
            'card_name' => 'João Silva',
            'card_number' => '4111111111111111',
            'card_expiry' => '12/26',
            'card_cvv' => '123',
        ]);

        $this->assertEquals(192.00, $finalPrice);
    }

    public function testMissingCardDataThrowsException(): void
    {
        $this->expectException(InvalidCardDataException::class);

        $strategy = new DebitCardPaymentStrategy();
        $strategy->calculateFinalPrice(200.00, 10.00, []);
    }
}
