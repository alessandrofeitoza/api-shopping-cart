<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Controllers;

use App\Domain\Service\ShoppingCartItemService;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ShoppingCartItemController extends Controller
{
    public function __construct(
        private ShoppingCartItemService $service
    ) {
    }

    public function getList(string $user): JsonResponse
    {
        return new JsonResponse(
            $this->service->findAllByUser(Uuid::fromString($user))
        );
    }

    public function get(string $user, string $id): JsonResponse
    {
        return new JsonResponse(
            $this->service->find(
                Uuid::fromString($user),
                Uuid::fromString($id)
            )
        );
    }

    public function create(string $user, Request $request): JsonResponse
    {
        $cart = $this->service->create(
            Uuid::fromString($user),
            $request->toArray()
        );

        return new JsonResponse(
            $cart,
            status: Response::HTTP_CREATED
        );
    }

    public function update(string $user, string $id, Request $request): JsonResponse
    {
        $cart = $this->service->update(
            Uuid::fromString($user),
            Uuid::fromString($id),
            $request->toArray()
        );

        return new JsonResponse($cart);
    }

    public function remove(string $user, string $id): JsonResponse
    {
        $this->service->remove(
            Uuid::fromString($user),
            Uuid::fromString($id)
        );

        return new JsonResponse(status: Response::HTTP_NO_CONTENT);
    }
}
