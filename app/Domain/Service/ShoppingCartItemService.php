<?php

declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\Models\ShoppingCart;
use App\Domain\Repository\ShoppingCartItemRepositoryInterface;
use App\Infrastructure\Supports\Enums\ShoppingCartStatusEnum;
use Illuminate\Database\Eloquent\Collection;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ShoppingCartItemService
{
    public function __construct(
        private ShoppingCartItemRepositoryInterface $repository
    ) {
    }

    public function findAllByUser(UuidInterface $userId): Collection
    {
        return $this->repository->findBy([
            'user_id' => $userId,
            'status' => ShoppingCartStatusEnum::AWAITING->value,
        ]);
    }

    private function findOneBy(UuidInterface $userId, UuidInterface $id): ?ShoppingCart
    {
        return $this->repository->findOneBy([
            'shopping_carts.id' => $id->toString(),
            'shopping_carts.user_id' => $userId->toString(),
            'status' => ShoppingCartStatusEnum::AWAITING->value,
        ]);
    }

    public function find(UuidInterface $userId, UuidInterface $id): ShoppingCart
    {
        $item = $this->findOneBy($userId, $id);

        if (null === $item) {
            throw new NotFoundHttpException();
        }

        return $item;
    }

    public function create(UuidInterface $userId, array $data): ShoppingCart
    {
        $item = $this->repository->findOneBy([
            'shopping_carts.product_id' => $data['product_id'],
            'shopping_carts.user_id' => $userId->toString(),
            'status' => ShoppingCartStatusEnum::AWAITING->value,
        ]);

        if (null === $item) {
            $item = new ShoppingCart();
            $item->fill($data);
            $item->setUserId($userId->toString());
            $item->setId(Uuid::uuid4());
        } else {
            $item->addQuantity($data['quantity']);
        }

        $this->repository->save($item);

        return $this->find($userId, $item->getId());
    }

    public function update(UuidInterface $userId, UuidInterface $id, array $data): ShoppingCart
    {
        $cart = $this->findOneBy($userId, $id);

        if (null === $cart) {
            throw new NotFoundHttpException();
        }

        if ($data['quantity'] < 1) {
            throw new BadRequestHttpException();
        }

        $cart->setQuantity($data['quantity']);

        $this->repository->save($cart);

        return $this->find($userId, $id);
    }

    public function remove(UuidInterface $userId, UuidInterface $id): void
    {
        $cart = $this->findOneBy($userId, $id);

        if (null === $cart) {
            throw new NotFoundHttpException();
        }

        $this->repository->remove($cart);
    }
}
