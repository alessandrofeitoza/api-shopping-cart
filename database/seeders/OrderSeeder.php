<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Domain\Models\Order;
use App\Infrastructure\Supports\Enums\OrderStatusEnum;
use App\Infrastructure\Supports\Enums\PaymentMethodEnum;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $users = DB::table('users')->pluck('id');
        $carts = DB::table('shopping_carts')->pluck('id');

        $orders = [
            [
                'id' => '55555555-aaaa-bbbb-cccc-000000000001',
                'user_id' => $users[0],
                'total_price' => 160.75,
                'original_price' => 150.75,
                'status' => OrderStatusEnum::FINISHED,
                'payment_method' => PaymentMethodEnum::PIX,
                'discount' => 10.00,
                'created_at' => now(),
                'items' => [$carts[0], $carts[1]],
            ],
            [
                'id' => '55555555-aaaa-bbbb-cccc-000000000002',
                'user_id' => $users[1],
                'total_price' => 299.99,
                'original_price' => 299.99,
                'status' => OrderStatusEnum::PENDING,
                'payment_method' => PaymentMethodEnum::DEBIT,
                'discount' => 0.00,
                'created_at' => now(),
                'items' => [$carts[2]],
            ],
        ];

        foreach ($orders as $value) {
            $order = new Order();
            $order->setId(Uuid::fromString($value['id']));
            $order->setUserId($value['user_id']);
            $order->setTotalPrice($value['total_price']);
            $order->setOriginalPrice($value['original_price']);
            $order->setStatus($value['status']);
            $order->setPaymentMethod($value['payment_method']);
            $order->setDiscount($value['discount']);
            $order->setCreatedAt($value['created_at']);
            $order->saveOrFail();

            foreach ($value['items'] as $cartId) {
                DB::table('order_items')->insert([
                    'order_id' => $order->id,
                    'shopping_cart_id' => $cartId,
                ]);
            }
        }
    }
}
