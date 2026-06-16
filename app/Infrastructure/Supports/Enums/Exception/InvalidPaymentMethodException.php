<?php

declare(strict_types=1);

namespace App\Infrastructure\Supports\Enums\Exception;

use LogicException;
use Throwable;

class InvalidPaymentMethodException extends LogicException
{
    public function __construct(string $message = 'Invalid Payment Method', int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
