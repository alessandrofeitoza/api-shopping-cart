<?php

declare(strict_types=1);

namespace App\Domain\Payment\Exception;

use LogicException;
use Throwable;

class InvalidInstallmentsNumberException extends LogicException
{
    public function __construct(string $message = 'Invalid installments number, available: 2-12', int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
