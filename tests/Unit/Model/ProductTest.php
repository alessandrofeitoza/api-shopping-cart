<?php

declare(strict_types=1);

namespace Tests\Unit\Model;

use App\Domain\Models\Product;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Tests\TestCase;

class ProductTest extends TestCase
{
    public function testBasicPropertiesFromProductModel(): void
    {
        $product = new Product();
        $product->setId(Uuid::uuid4());
        $product->setName('Cadeira Gamer');
        $product->setDescription('Cadeira ergonômica com ajuste de altura');
        $product->setCategoryId(2);
        $product->setPrice(1299.90);
        $product->setPhoto('cadeira.jpg');
        $product->setQuantity(5);

        $this->assertEquals('Cadeira Gamer', $product->name);
        $this->assertEquals('Cadeira ergonômica com ajuste de altura', $product->description);
        $this->assertEquals(2, $product->category_id);
        $this->assertEquals(1299.90, $product->price);
        $this->assertEquals('cadeira.jpg', $product->photo);
        $this->assertEquals(5, $product->quantity);
        $this->assertInstanceOf(UuidInterface::class, Uuid::fromString($product->id));
        $this->assertInstanceOf(BelongsTo::class, $product->category());
    }
}
