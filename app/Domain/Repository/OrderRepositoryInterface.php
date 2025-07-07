<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use Illuminate\Database\Eloquent\Collection;

interface OrderRepositoryInterface
{
    public function findBy(array $criteria = []): Collection;
}
