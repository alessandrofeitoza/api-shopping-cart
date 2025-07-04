<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Models\Product;
use Illuminate\Database\Eloquent\Collection;

interface ProductRepositoryInterface
{
    public function findAll(): Collection;

    public function find(string $id): Product;

    public function save(Product $product): Product;

    public function remove(Product $product): void;
}
