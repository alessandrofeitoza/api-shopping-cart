<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use Database\Seeders\ProductCategorySeeder;
use Database\Seeders\ProductSeeder;
use Database\Seeders\ShoppingCartSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class ShoppingCartItemControllerTest extends TestCase
{
    use RefreshDatabase;

    private const string BASE_URL = '/api/users/%s/cart-items';

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(ProductCategorySeeder::class);
        $this->seed(ProductSeeder::class);
        $this->seed(UserSeeder::class);
        $this->seed(ShoppingCartSeeder::class);
    }

    public function testTheGetAllShoppingCartItemsReturnsASuccessfulResponse(): void
    {
        $url = sprintf(self::BASE_URL, UserSeeder::VALUES[0]['id']);

        $response = $this->get($url);
        $json = $response->json();

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(1);
        $this->assertEquals('22222222-aaaa-bbbb-cccc-000000000002', $json[0]['id']);
        $this->assertEquals('Mouse Gamer', $json[0]['name']);
        $this->assertEquals(1, $json[0]['quantity']);
        $this->assertEquals(150, $json[0]['unitPrice']);
    }

    public function testTheGetOneShoppingCartItemReturnsASuccessfulResponse(): void
    {
        $url = sprintf(self::BASE_URL, UserSeeder::VALUES[2]['id']);

        $response = $this->get($url.'/33333333-aaaa-bbbb-cccc-000000000002');
        $json = $response->json();

        $response->assertStatus(Response::HTTP_OK);
        $this->assertEquals('Livro PHP Moderno', $json['name']);
        $this->assertEquals(89.9, $json['unitPrice']);
        $this->assertEquals(5, $json['quantity']);
        $this->assertEquals(449.5, $json['totalPrice']);
        $this->assertEquals($json['unitPrice'] * $json['quantity'], $json['totalPrice']);
    }

    public function testTheGetOneShoppingCartItemThatDontExistReturnsANotFoundResponse(): void
    {
        $url = sprintf(self::BASE_URL, UserSeeder::VALUES[0]['id']);

        $response = $this->get($url.'/'.Uuid::uuid4()->toString());

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function testTheRegisterANewShoppingCartItemReturnsASuccessfulResponse(): void
    {
        $url = sprintf(self::BASE_URL, UserSeeder::VALUES[0]['id']);

        $response = $this->post($url, [
            'product_id' => '11111111-aaaa-bbbb-cccc-000000000001',
            'quantity' => 3,
        ]);
        $json = $response->json();

        $response->assertStatus(Response::HTTP_CREATED);
        $this->assertEquals('11111111-aaaa-bbbb-cccc-000000000001', $json['product_id']);
        $this->assertEquals('1789995d-dceb-4df1-a1e0-853d88edab74', $json['user_id']);
        $this->assertEquals(3, $json['quantity']);
        $this->assertEquals(4500, $json['unitPrice']);
        $this->assertEquals('Notebook Dell', $json['name']);
        $this->assertEquals(13500, $json['totalPrice']);
    }

    public function testTheRegisterAddOneCartItemReturnsASuccessfulResponse(): void
    {
        $url = sprintf(self::BASE_URL, UserSeeder::VALUES[0]['id']);

        $this->post($url, [
            'product_id' => '11111111-aaaa-bbbb-cccc-000000000001',
            'quantity' => 3,
        ]);

        $response = $this->post($url, [
            'product_id' => '11111111-aaaa-bbbb-cccc-000000000001',
            'quantity' => 1,
        ]);
        $json = $response->json();

        $response->assertStatus(Response::HTTP_CREATED);
        $this->assertEquals('11111111-aaaa-bbbb-cccc-000000000001', $json['product_id']);
        $this->assertEquals('1789995d-dceb-4df1-a1e0-853d88edab74', $json['user_id']);
        $this->assertEquals(4, $json['quantity']);
        $this->assertEquals(4500, $json['unitPrice']);
        $this->assertEquals('Notebook Dell', $json['name']);
        $this->assertEquals(18000, $json['totalPrice']);
    }

    public function testTheUpdateTheQuantityFromCartItemReturnsASuccessfulResponse(): void
    {
        $url = sprintf(self::BASE_URL, UserSeeder::VALUES[2]['id']).'/33333333-aaaa-bbbb-cccc-000000000002';

        $response = $this->patch($url, [
            'quantity' => 10,
        ]);
        $json = $response->json();

        $response->assertStatus(Response::HTTP_OK);
        $this->assertEquals('11111111-aaaa-bbbb-cccc-000000000003', $json['product_id']);
        $this->assertEquals('ad099cfa-7ac9-4193-ac84-8e5cab1e969b', $json['user_id']);
        $this->assertEquals(10, $json['quantity']);
        $this->assertEquals(89.90, $json['unitPrice']);
        $this->assertEquals('Livro PHP Moderno', $json['name']);
        $this->assertEquals(899, $json['totalPrice']);
    }

    public function testTheUpdateTheQuantityFromCartItemWithInvalidValueReturnsABadRequestResponse(): void
    {
        $url = sprintf(self::BASE_URL, UserSeeder::VALUES[2]['id']).'/33333333-aaaa-bbbb-cccc-000000000002';

        $response = $this->patch($url, [
            'quantity' => -4,
        ]);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
    }

    public function testTheUpdateTheQuantityFromCartItemThatDoNotExistReturnsANotFoundResponse(): void
    {
        $url = sprintf(self::BASE_URL, UserSeeder::VALUES[2]['id']).'/33333333-aaaa-bbbb-cccc-000000000001';

        $response = $this->patch($url, [
            'quantity' => -4,
        ]);

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function testTheRemoveOneShoppingCartItemReturnsASuccessfulResponse(): void
    {
        $url = sprintf(self::BASE_URL, UserSeeder::VALUES[2]['id']).'/33333333-aaaa-bbbb-cccc-000000000002';

        $response = $this->delete($url);
        $response->assertStatus(Response::HTTP_NO_CONTENT);

        $newResponse = $this->get($url);
        $newResponse->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function testTheRemoveOneShoppingCartItemThatDoNotExistsReturnsANotFoundResponse(): void
    {
        $url = sprintf(self::BASE_URL, UserSeeder::VALUES[2]['id']).'/33333333-aaaa-bbbb-cccc-000000000005';

        $newResponse = $this->delete($url);
        $newResponse->assertStatus(Response::HTTP_NOT_FOUND);
    }
}
