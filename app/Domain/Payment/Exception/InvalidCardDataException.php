<?php

declare(strict_types=1);

namespace App\Domain\Payment\Exception;

use LogicException;
use Throwable;

class InvalidCardDataException extends LogicException
{
    public function __construct(string $message = 'Invalid data from card', int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
