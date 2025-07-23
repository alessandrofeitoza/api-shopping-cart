<?php

declare(strict_types=1);

namespace App\Infrastructure\Supports\Enums;

use App\Infrastructure\Supports\Enums\Exception\InvalidPaymentMethodException;
use ValueError;

enum PaymentMethodEnum: string
{
    case PIX = 'pix';
    case DEBIT = 'debit';
    case CREDIT_CARD = 'credit_card';
    case CREDIT_CARD_INSTALLMENTS = 'credit_card_installments';

    public static function tryFromValue(int|string $value): ?static
    {
        try {
            return self::from($value);
        } catch (ValueError) {
            throw new InvalidPaymentMethodException();
        }
    }
}
