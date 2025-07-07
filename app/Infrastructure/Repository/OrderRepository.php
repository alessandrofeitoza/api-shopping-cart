<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\Models\Order;
use App\Domain\Repository\OrderRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class OrderRepository implements OrderRepositoryInterface
{
    public function findBy(array $criteria = []): Collection
    {
        return Order::query()->where($criteria)->get();
    }
}
