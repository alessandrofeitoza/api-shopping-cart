<?php

declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\Models\ProductCategory;
use App\Domain\Repository\ProductCategoryRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ProductCategoryService
{
    public function __construct(
        private ProductCategoryRepositoryInterface $repository
    ) {
    }

    public function findAll(): Collection
    {
        return $this->repository->findAll();
    }

    public function find(int $id): ProductCategory
    {
        return $this->repository->find($id);
    }

    public function create(array $data): ProductCategory
    {
        $category = new ProductCategory();
        $category->fill($data);

        return $this->repository->save($category);
    }

    public function update(int $id, array $data): ProductCategory
    {
        $category = $this->find($id);
        $category->setName($data['name'] ?? $category->name);
        $category->setDescription($data['description'] ?? $category->description);

        return $this->repository->save($category);
    }

    public function remove(int $id): void
    {
        $category = $this->repository->find($id);

        $this->repository->remove($category);
    }
}
