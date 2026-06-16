<?php

declare(strict_types=1);

namespace App\Domain\Payment\Trait;

use App\Domain\Payment\Exception\InvalidCardDataException;

trait PaymentTrait
{
    private function validateCardData(array $options): void
    {
        if (empty($options['card_name']) || empty($options['card_number']) || empty($options['card_expiry']) || empty($options['card_cvv'])) {
            throw new InvalidCardDataException();
        }
    }
}
