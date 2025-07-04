<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use Database\Seeders\ProductCategorySeeder;
use Database\Seeders\ProductSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    private const string BASE_URL = '/api/products';

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(ProductCategorySeeder::class);
        $this->seed(ProductSeeder::class);
    }

    public function testTheGetAllProductsReturnsASuccessfulResponse(): void
    {
        $response = $this->get(self::BASE_URL);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(10);
        $this->assertEquals('11111111-aaaa-bbbb-cccc-000000000001', $response->json()[0]['id']);
        $this->assertEquals('Notebook Dell', $response->json()[0]['name']);
        $this->assertEquals('Notebook com 16GB RAM e SSD', $response->json()[0]['description']);
    }

    public function testTheGetOneProductReturnsASuccessfulResponse(): void
    {
        $response = $this->get(self::BASE_URL.'/11111111-aaaa-bbbb-cccc-000000000006');

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson([
            'id' => '11111111-aaaa-bbbb-cccc-000000000006',
            'name' => 'Monitor 24\\"',
            'description' => 'Full HD, HDMI e VGA',
            'category_id' => 1,
            'price' => 750,
            'photo' => null,
        ]);
    }

    public function testTheGetOneProductThatDontExistReturnsANotFoundResponse(): void
    {
        $response = $this->get(self::BASE_URL.'/'.Uuid::uuid4()->toString());

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function testTheRegisterOneProductReturnsASuccessfulResponse(): void
    {
        $response = $this->post(self::BASE_URL, [
            'id' => '33331234-aaaa-bbbb-cccc-000000000006',
            'name' => 'Monitor 32\\"',
            'description' => 'Full HD, HDMI e VGA',
            'category_id' => 2,
            'price' => 1750,
            'quantity' => 21,
            'photo' => 'photo.png',
        ]);

        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJson([
            'id' => '33331234-aaaa-bbbb-cccc-000000000006',
            'name' => 'Monitor 32\\"',
            'description' => 'Full HD, HDMI e VGA',
            'category_id' => 2,
            'price' => 1750,
            'quantity' => 21,
            'photo' => 'photo.png',
        ]);
    }

    public function testTheUpdateOneProductReturnsASuccessfulResponse(): void
    {
        $response = $this->patch(self::BASE_URL.'/11111111-aaaa-bbbb-cccc-000000000006', [
            'name' => 'Novo nome',
            'description' => 'Nova description',
        ]);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson([
            'id' => '11111111-aaaa-bbbb-cccc-000000000006',
            'name' => 'Novo nome',
            'description' => 'Nova description',
            'category_id' => 1,
        ]);
    }

    public function testTheRemoveOneProductReturnsASuccessfulResponse(): void
    {
        $response = $this->delete(self::BASE_URL.'/11111111-aaaa-bbbb-cccc-000000000006');
        $response->assertStatus(Response::HTTP_NO_CONTENT);

        $newResponse = $this->get(self::BASE_URL.'/11111111-aaaa-bbbb-cccc-000000000006');
        $newResponse->assertStatus(Response::HTTP_NOT_FOUND);
    }
}
