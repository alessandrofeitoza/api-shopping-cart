<?php

declare(strict_types=1);

namespace App\Domain\Payment;

use App\Domain\Payment\Trait\PaymentTrait;

class CreditCardPaymentStrategy implements PaymentStrategyInterface
{
    use PaymentTrait;

    public function calculateFinalPrice(float $totalPrice, float $discount, array $options = []): float
    {
        $this->validateCardData($options);

        $discountAmount = $totalPrice * 0.10;

        return $totalPrice - $discountAmount - $discount;
    }
}
