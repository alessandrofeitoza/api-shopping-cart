<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Payment;

use App\Domain\Payment\CreditCardInstallmentPaymentStrategy;
use App\Domain\Payment\Exception\InvalidCardDataException;
use App\Domain\Payment\Exception\InvalidInstallmentsNumberException;
use PHPUnit\Framework\TestCase;

class CreditCardInstallmentPaymentStrategyTest extends TestCase
{
    public function testCalculateFinalPriceWithInstallmentsAndCardData(): void
    {
        $strategy = new CreditCardInstallmentPaymentStrategy();

        $finalPrice = $strategy->calculateFinalPrice(1000.00, 50.00, [
            'installments' => 6,
            'card_name' => 'Maria Souza',
            'card_number' => '4111111111111111',
            'card_expiry' => '12/27',
            'card_cvv' => '321',
        ]);

        $expectedAmount = 1000.00 * pow(1.01, 6) - 50.00;

        $this->assertEqualsWithDelta($expectedAmount, $finalPrice, 0.01);
    }

    public function testInvalidInstallmentsThrowsException(): void
    {
        $this->expectException(InvalidInstallmentsNumberException::class);

        $strategy = new CreditCardInstallmentPaymentStrategy();
        $strategy->calculateFinalPrice(500.00, 0.00, [
            'installments' => 20,
            'card_name' => 'Carlos',
            'card_number' => '4111111111111111',
            'card_expiry' => '11/25',
            'card_cvv' => '456',
        ]);
    }

    public function testMissingCardDataThrowsException(): void
    {
        $this->expectException(InvalidCardDataException::class);

        $strategy = new CreditCardInstallmentPaymentStrategy();
        $strategy->calculateFinalPrice(500.00, 0.00, ['installments' => 3]);
    }
}
