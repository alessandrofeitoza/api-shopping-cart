<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Domain\Models\ShoppingCart;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class ShoppingCartSeeder extends Seeder
{
    public function run(): void
    {
        $products = DB::table('products')->pluck('id');
        $users = DB::table('users')->pluck('id');

        $values = [
            [
                'id' => '22222222-aaaa-bbbb-cccc-000000000001',
                'product_id' => $products[0],
                'user_id' => $users[0],
                'quantity' => 2,
            ],
            [
                'id' => '22222222-aaaa-bbbb-cccc-000000000002',
                'product_id' => $products[1],
                'user_id' => $users[1],
                'quantity' => 1,
            ],
            [
                'id' => '33333333-aaaa-bbbb-cccc-000000000002',
                'product_id' => $products[2],
                'user_id' => $users[2],
                'quantity' => 5,
            ],
            [
                'id' => '44443333-aaaa-bbbb-cccc-000000000002',
                'product_id' => $products[2],
                'user_id' => $users[0],
                'quantity' => 3,
            ],
        ];

        foreach ($values as $value) {
            $cart = new ShoppingCart();
            $cart->setId(Uuid::fromString($value['id']));
            $cart->setProductId($value['product_id']);
            $cart->setUserId($value['user_id']);
            $cart->setQuantity($value['quantity']);
            $cart->saveOrFail();
        }
    }
}
