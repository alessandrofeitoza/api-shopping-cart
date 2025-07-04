<?php

declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\Models\Product;
use App\Domain\Repository\ProductRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Ramsey\Uuid\UuidInterface;

class ProductService
{
    public function __construct(
        private ProductRepositoryInterface $repository
    ) {
    }

    public function findAll(): Collection
    {
        return $this->repository->findAll();
    }

    public function find(UuidInterface $id): Product
    {
        return $this->repository->find($id->toString());
    }

    public function create(array $data): Product
    {
        $product = new Product();
        $product->fill($data);

        return $this->repository->save($product);
    }

    public function update(UuidInterface $id, array $data): Product
    {
        $product = $this->find($id);
        $product->setName($data['name'] ?? $product->name);
        $product->setDescription($data['description'] ?? $product->description);

        return $this->repository->save($product);
    }

    public function remove(UuidInterface $id): void
    {
        $product = $this->repository->find($id->toString());

        $this->repository->remove($product);
    }
}
