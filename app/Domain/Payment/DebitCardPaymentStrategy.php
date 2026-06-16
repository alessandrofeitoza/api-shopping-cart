<?php

declare(strict_types=1);

namespace App\Domain\Payment;

use App\Domain\Payment\Trait\PaymentTrait;

class DebitCardPaymentStrategy implements PaymentStrategyInterface
{
    use PaymentTrait;

    public function calculateFinalPrice(float $totalPrice, float $discount, array $options = []): float
    {
        $fee = $totalPrice * 0.01;

        $this->validateCardData($options);

        return $totalPrice + $fee - $discount;
    }
}
