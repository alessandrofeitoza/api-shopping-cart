<?php

declare(strict_types=1);

namespace App\Domain\Payment;

use App\Domain\Payment\Exception\InvalidInstallmentsNumberException;
use App\Domain\Payment\Trait\PaymentTrait;

class CreditCardInstallmentPaymentStrategy implements PaymentStrategyInterface
{
    use PaymentTrait;

    public function calculateFinalPrice(float $totalPrice, float $discount, array $options = []): float
    {
        $this->validateCardData($options);

        $installments = $options['installments'] ?? 2;

        if ($installments < 2 || $installments > 12) {
            throw new InvalidInstallmentsNumberException();
        }

        $interestRate = 0.01;
        $finalAmount = $totalPrice * pow(1 + $interestRate, $installments);

        return $finalAmount - $discount;
    }
}
