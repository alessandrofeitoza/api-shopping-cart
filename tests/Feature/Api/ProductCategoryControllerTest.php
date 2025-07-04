<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use Database\Seeders\ProductCategorySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class ProductCategoryControllerTest extends TestCase
{
    use RefreshDatabase;

    private const string BASE_URL = '/api/product-categories';

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(ProductCategorySeeder::class);
    }

    public function testTheGetAllCategoriesReturnsASuccessfulResponse(): void
    {
        $response = $this->get(self::BASE_URL);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(3);
        $this->assertEquals(1, $response->json()[0]['id']);
        $this->assertEquals('Informática', $response->json()[0]['name']);
        $this->assertEquals(null, $response->json()[0]['description']);
    }

    public function testTheGetOneCategoryReturnsASuccessfulResponse(): void
    {
        $response = $this->get(self::BASE_URL.'/3');

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson([
            'id' => 3,
            'name' => 'Bebidas',
            'description' => 'Refrigerantes, Bebidas Alcoolicas, Sucos',
        ]);
    }

    public function testTheGetOneCategoryThatDontExistReturnsANotFoundResponse(): void
    {
        $response = $this->get(self::BASE_URL.'/99999');

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function testTheRegisterOneCategoryReturnsASuccessfulResponse(): void
    {
        $response = $this->post(self::BASE_URL, [
            'name' => 'Cozinha',
            'description' => 'Utensilios de Cozinha',
        ]);

        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJson([
            'id' => 4,
            'name' => 'Cozinha',
            'description' => 'Utensilios de Cozinha',
        ]);
    }

    public function testTheUpdateOneCategoryReturnsASuccessfulResponse(): void
    {
        $response = $this->patch(self::BASE_URL.'/1', [
            'name' => 'Novo nome',
            'description' => 'Nova description',
        ]);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson([
            'id' => 1,
            'name' => 'Novo nome',
            'description' => 'Nova description',
        ]);
    }

    public function testTheRemoveOneCategoryReturnsASuccessfulResponse(): void
    {
        $response = $this->delete(self::BASE_URL.'/2');
        $response->assertStatus(Response::HTTP_NO_CONTENT);

        $newResponse = $this->get(self::BASE_URL.'/2');
        $newResponse->assertStatus(Response::HTTP_NOT_FOUND);
    }
}
