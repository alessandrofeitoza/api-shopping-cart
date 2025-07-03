<?php

declare(strict_types=1);

namespace App\Infrastructure\Supports\Enums;

enum UserRoleEnum: string
{
    case ROLE_USER = 'user';
    case ROLE_ADMIN = 'admin';
}
