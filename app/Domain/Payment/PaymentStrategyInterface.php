<?php

declare(strict_types=1);

namespace App\Domain\Payment;

interface PaymentStrategyInterface
{
    public function calculateFinalPrice(float $totalPrice, float $discount, array $options = []): float;
}
