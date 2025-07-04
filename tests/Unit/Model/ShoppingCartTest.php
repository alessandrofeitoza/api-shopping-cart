<?php

declare(strict_types=1);

namespace Tests\Unit\Model;

use App\Domain\Models\ShoppingCart;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Tests\TestCase;

class ShoppingCartTest extends TestCase
{
    public function testBasicPropertiesFromShoppingCartModel(): void
    {
        $cart = new ShoppingCart();
        $cart->setId(Uuid::uuid4());
        $cart->setProductId((string) Uuid::uuid4());
        $cart->setUserId((string) Uuid::uuid4());
        $cart->setQuantity(3);

        $this->assertInstanceOf(UuidInterface::class, $cart->getId());
        $this->assertInstanceOf(UuidInterface::class, Uuid::fromString($cart->product_id));
        $this->assertInstanceOf(UuidInterface::class, Uuid::fromString($cart->user_id));
        $this->assertEquals(3, $cart->quantity);
        $this->assertInstanceOf(BelongsTo::class, $cart->product());
        $this->assertInstanceOf(BelongsTo::class, $cart->user());
    }
}
