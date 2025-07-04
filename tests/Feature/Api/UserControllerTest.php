<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use App\Infrastructure\Supports\Enums\UserRoleEnum;
use App\Infrastructure\Supports\Enums\UserStatusEnum;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    private const string BASE_URL = '/api/users';

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(UserSeeder::class);
    }

    public function testTheGetAllCategoriesReturnsASuccessfulResponse(): void
    {
        $response = $this->get(self::BASE_URL);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(3);
        $this->assertEquals('ad099cfa-7ac9-4193-ac84-8e5cab1e969b', $response->json()[2]['id']);
        $this->assertEquals('chiquim@email.com', $response->json()[2]['email']);
    }

    public function testTheGetOneUserReturnsASuccessfulResponse(): void
    {
        $response = $this->get(self::BASE_URL.'/ad099cfa-7ac9-4193-ac84-8e5cab1e969b');

        $response->assertStatus(Response::HTTP_OK);
        $this->assertEquals('Chiquim das Tapiocas', $response->json()['name']);
        $this->assertEquals('chiquim@email.com', $response->json()['email']);
        $this->assertEquals(UserStatusEnum::BLOCKED->value, $response->json()['status']);
    }

    public function testTheGetOneUserThatDontExistReturnsANotFoundResponse(): void
    {
        $response = $this->get(self::BASE_URL.'/'.Uuid::uuid4()->toString());

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function testTheRegisterOneUserReturnsASuccessfulResponse(): void
    {
        $id = Uuid::uuid4()->toString();

        $response = $this->post(self::BASE_URL, [
            'id' => $id,
            'name' => 'Teste da Silva',
            'email' => 'testedasilva@email.com',
            'password' => '123456',
        ]);

        $response->assertStatus(Response::HTTP_CREATED);
        $this->assertEquals($id, $response->json()['id']);
        $this->assertEquals(UserStatusEnum::AWAITING_CONFIRMATION->value, $response->json()['status']);
        $this->assertEquals(UserRoleEnum::ROLE_USER->value, $response->json()['roles'][0]);
    }

    public function testTheUpdateOneUserReturnsASuccessfulResponse(): void
    {
        $response = $this->patch(self::BASE_URL.'/ad099cfa-7ac9-4193-ac84-8e5cab1e969b', [
            'name' => 'Novo nome',
        ]);

        $response->assertStatus(Response::HTTP_OK);
        $this->assertEquals('Novo nome', $response->json()['name']);
    }

    public function testTheRemoveOneUserReturnsASuccessfulResponse(): void
    {
        $response = $this->delete(self::BASE_URL.'/ad099cfa-7ac9-4193-ac84-8e5cab1e969b');
        $response->assertStatus(Response::HTTP_NO_CONTENT);

        $newResponse = $this->get(self::BASE_URL.'/ad099cfa-7ac9-4193-ac84-8e5cab1e969b');
        $newResponse->assertStatus(Response::HTTP_NOT_FOUND);
    }
}
