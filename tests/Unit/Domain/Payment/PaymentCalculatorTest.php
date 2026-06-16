<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Payment;

use App\Domain\Payment\CreditCardInstallmentPaymentStrategy;
use App\Domain\Payment\CreditCardPaymentStrategy;
use App\Domain\Payment\PaymentCalculator;
use App\Domain\Payment\PaymentStrategyInterface;
use App\Domain\Payment\PixPaymentStrategy;
use App\Infrastructure\Supports\Enums\PaymentMethodEnum;
use PHPUnit\Framework\TestCase;

class PaymentCalculatorTest extends TestCase
{
    public function testGetStrategyForPix(): void
    {
        $calculator = new PaymentCalculator();
        $strategy = $calculator->getStrategy(PaymentMethodEnum::PIX);

        $this->assertInstanceOf(PixPaymentStrategy::class, $strategy);
        $this->assertInstanceOf(PaymentStrategyInterface::class, $strategy);
    }

    public function testGetStrategyForCreditCard(): void
    {
        $calculator = new PaymentCalculator();
        $strategy = $calculator->getStrategy(PaymentMethodEnum::CREDIT_CARD);

        $this->assertInstanceOf(CreditCardPaymentStrategy::class, $strategy);
        $this->assertInstanceOf(PaymentStrategyInterface::class, $strategy);
    }

    public function testGetStrategyForCreditCardInstallments(): void
    {
        $calculator = new PaymentCalculator();
        $strategy = $calculator->getStrategy(PaymentMethodEnum::CREDIT_CARD_INSTALLMENTS);

        $this->assertInstanceOf(CreditCardInstallmentPaymentStrategy::class, $strategy);
        $this->assertInstanceOf(PaymentStrategyInterface::class, $strategy);
    }
}
