<?php

declare(strict_types=1);

namespace App\Domain\Payment;

use App\Infrastructure\Supports\Enums\PaymentMethodEnum;

class PaymentCalculator
{
    public function getStrategy(PaymentMethodEnum $paymentMethod): PaymentStrategyInterface
    {
        return match ($paymentMethod) {
            PaymentMethodEnum::PIX => new PixPaymentStrategy(),
            PaymentMethodEnum::CREDIT_CARD => new CreditCardPaymentStrategy(),
            PaymentMethodEnum::DEBIT => new DebitCardPaymentStrategy(),
            PaymentMethodEnum::CREDIT_CARD_INSTALLMENTS => new CreditCardInstallmentPaymentStrategy(),
        };
    }
}
