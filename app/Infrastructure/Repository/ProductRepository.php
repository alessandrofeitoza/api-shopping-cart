<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\Models\Product;
use App\Domain\Repository\ProductRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ProductRepository implements ProductRepositoryInterface
{
    public function findAll(): Collection
    {
        return Product::all();
    }

    public function find(string $id): Product
    {
        return Product::findOrFail($id);
    }

    public function save(Product $product): Product
    {
        $product->save();

        return $product;
    }

    public function remove(Product $product): void
    {
        $product->delete();
    }
}
