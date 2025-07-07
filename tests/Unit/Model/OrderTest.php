<?php

declare(strict_types=1);

namespace Tests\Unit\Model;

use App\Domain\Models\Order;
use App\Infrastructure\Supports\Enums\OrderStatusEnum;
use App\Infrastructure\Supports\Enums\PaymentMethodEnum;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Tests\TestCase;

class OrderTest extends TestCase
{
    public function testBasicPropertiesFromOrderModel(): void
    {
        $order = new Order();
        $order->id = (string) Uuid::uuid4();
        $order->setUserId((string) Uuid::uuid4());
        $order->setTotalPrice(299.99);
        $order->setStatus(OrderStatusEnum::PENDING);
        $order->setPaymentMethod(PaymentMethodEnum::DEBIT);
        $order->setDiscount(10.00);
        $order->setCreatedAt(now());

        $this->assertInstanceOf(UuidInterface::class, Uuid::fromString($order->id));
        $this->assertInstanceOf(UuidInterface::class, Uuid::fromString($order->user_id));
        $this->assertEquals(299.99, $order->total_price);
        $this->assertEquals(OrderStatusEnum::PENDING->value, $order->status);
        $this->assertEquals(PaymentMethodEnum::DEBIT->value, $order->payment_method);
        $this->assertEquals(10.00, $order->discount);
        $this->assertNotNull($order->created_at);
        $this->assertInstanceOf(BelongsTo::class, $order->user());
        $this->assertInstanceOf(BelongsToMany::class, $order->items());
    }
}
