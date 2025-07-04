<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Models\ProductCategory;
use Illuminate\Database\Eloquent\Collection;

interface ProductCategoryRepositoryInterface
{
    public function findAll(): Collection;

    public function find(int $id): ProductCategory;

    public function save(ProductCategory $category): ProductCategory;

    public function remove(ProductCategory $category): void;
}
