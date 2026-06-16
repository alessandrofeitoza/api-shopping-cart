<?php

declare(strict_types=1);

namespace App\Domain\Payment;

class PixPaymentStrategy implements PaymentStrategyInterface
{
    public function calculateFinalPrice(float $totalPrice, float $discount, array $options = []): float
    {
        $discountAmount = $totalPrice * 0.10;

        return $totalPrice - $discountAmount - $discount;
    }
}
