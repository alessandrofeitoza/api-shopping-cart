<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\Models\ProductCategory;
use App\Domain\Repository\ProductCategoryRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ProductCategoryRepository implements ProductCategoryRepositoryInterface
{
    public function findAll(): Collection
    {
        return ProductCategory::all();
    }

    public function find(int $id): ProductCategory
    {
        return ProductCategory::findOrFail($id);
    }

    public function save(ProductCategory $category): ProductCategory
    {
        $category->save();

        return $category;
    }

    public function remove(ProductCategory $category): void
    {
        $category->delete();
    }
}
