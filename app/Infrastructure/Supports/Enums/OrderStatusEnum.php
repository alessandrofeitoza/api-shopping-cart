<?php

declare(strict_types=1);

namespace App\Infrastructure\Supports\Enums;

enum OrderStatusEnum: string
{
    case CANCELLED = 'cancelled';
    case FINISHED = 'finished';
    case PAID = 'paid';
    case PENDING = 'pending';
}
