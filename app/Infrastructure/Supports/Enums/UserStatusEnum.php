<?php

declare(strict_types=1);

namespace App\Infrastructure\Supports\Enums;

enum UserStatusEnum: string
{
    case ACTIVE = 'active';
    case BLOCKED = 'blocked';
    case AWAITING_CONFIRMATION = 'awaiting_confirmation';
}
