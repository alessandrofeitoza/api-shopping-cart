<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use App\Infrastructure\Supports\Enums\OrderStatusEnum;
use App\Infrastructure\Supports\Enums\PaymentMethodEnum;
use Database\Seeders\OrderSeeder;
use Database\Seeders\ProductCategorySeeder;
use Database\Seeders\ProductSeeder;
use Database\Seeders\ShoppingCartSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class OrderControllerTest extends TestCase
{
    use RefreshDatabase;

    private const string BASE_URL = '/api/users/%s/orders';

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(ProductCategorySeeder::class);
        $this->seed(ProductSeeder::class);
        $this->seed(UserSeeder::class);
        $this->seed(ShoppingCartSeeder::class);
        $this->seed(OrderSeeder::class);
    }

    public function testTheGetAllOrdersFromUserReturnsASuccessfulResponse(): void
    {
        $url = sprintf(self::BASE_URL, UserSeeder::VALUES[1]['id']);

        $response = $this->get($url);
        $json = $response->json();

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(1);
        $this->assertEquals('55555555-aaaa-bbbb-cccc-000000000001', $json[0]['id']);
        $this->assertEquals(170.75, $json[0]['original_price']);
        $this->assertEquals(160.75, $json[0]['total_price']);
        $this->assertEquals(10, $json[0]['discount']);
        $this->assertEquals(OrderStatusEnum::FINISHED->value, $json[0]['status']);
        $this->assertEquals(PaymentMethodEnum::PIX->value, $json[0]['payment_method']);
    }
}
