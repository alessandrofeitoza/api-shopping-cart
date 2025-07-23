<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use Database\Seeders\OrderSeeder;
use Database\Seeders\ProductCategorySeeder;
use Database\Seeders\ProductSeeder;
use Database\Seeders\ShoppingCartSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class ShoppingCartControllerTest extends TestCase
{
    use RefreshDatabase;

    private const string BASE_URL = '/api/users/%s/shopping-cart';

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(ProductCategorySeeder::class);
        $this->seed(ProductSeeder::class);
        $this->seed(UserSeeder::class);
        $this->seed(ShoppingCartSeeder::class);
        $this->seed(OrderSeeder::class);
    }

    public function testTheGetAllShoppingCartDetailsFromUserReturnsASuccessfulResponse(): void
    {
        $url = sprintf(self::BASE_URL, UserSeeder::VALUES[1]['id']);

        $response = $this->get($url);
        $json = $response->json();

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonIsObject();
        $response->assertJsonIsArray('items');

        $this->assertEquals(9269.7, $json['totalPrice']);
        $this->assertEquals(2, count($json['items']));
    }

    public function testTheRemoveAllShoppingCartDetailsFromUserReturnsASuccessfulResponse(): void
    {
        $url = sprintf(self::BASE_URL, UserSeeder::VALUES[1]['id']);

        $response = $this->delete($url);

        $response->assertStatus(Response::HTTP_NO_CONTENT);
    }
}
