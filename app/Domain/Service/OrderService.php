<?php

declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\Repository\OrderRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Ramsey\Uuid\UuidInterface;

class OrderService
{
    public function __construct(
        private OrderRepositoryInterface $repository
    ) {
    }

    public function findAllByUser(UuidInterface $userId): Collection
    {
        return $this->repository->findBy([
            'user_id' => $userId->toString(),
        ]);
    }
}
