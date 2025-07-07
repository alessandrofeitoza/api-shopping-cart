<?php

declare(strict_types=1);

namespace App\Infrastructure\Supports\Enums;

enum ShoppingCartStatusEnum: string
{
    case CANCELLED = 'cancelled';
    case FINISHED = 'finished';
    case AWAITING = 'awaiting';
}
