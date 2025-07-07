<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Models\ShoppingCart;
use App\Infrastructure\Supports\Enums\ShoppingCartStatusEnum;
use Illuminate\Database\Eloquent\Collection;

interface ShoppingCartItemRepositoryInterface
{
    public function findBy(array $criteria = []): Collection;

    public function findOneBy(array $criteria): ?ShoppingCart;

    public function save(ShoppingCart $cart): ShoppingCart;

    public function remove(ShoppingCart $cart): void;

    public function removeMany(Collection $items): void;

    public function updateManyStatus(array $ids, ShoppingCartStatusEnum $status): void;
}
