<?php

declare(strict_types=1);

namespace App\Infrastructure\Supports\Enums;

enum PaymentMethodEnum: string
{
    case PIX = 'pix';
    case DEBIT = 'debit';
    case CREDIT_CARD = 'credit_card';
}
