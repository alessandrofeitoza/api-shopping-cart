<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\Models\ShoppingCart;
use App\Domain\Repository\ShoppingCartItemRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class ShoppingCartItemItemRepository implements ShoppingCartItemRepositoryInterface
{
    public function findBy(array $criteria = []): Collection
    {
        return ShoppingCart::query()
            ->select([
                'shopping_carts.*',
                'products.price as unitPrice',
                'products.name',
                DB::raw('products.price * shopping_carts.quantity as totalPrice'),
            ])
            ->where($criteria)
            ->join('products', 'products.id', '=', 'shopping_carts.product_id')
            ->get();
    }

    public function findOneBy(array $criteria): ?ShoppingCart
    {
        return $this->findBy($criteria)->first();
    }

    public function save(ShoppingCart $cart): ShoppingCart
    {
        $cart->save();

        return $cart;
    }

    public function remove(ShoppingCart $cart): void
    {
        $cart->delete();
    }
}
