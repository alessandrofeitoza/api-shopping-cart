<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Controllers;

use App\Domain\Service\ShoppingCartService;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ShoppingCartController extends Controller
{
    public function __construct(
        private ShoppingCartService $service
    ) {
    }

    public function get(string $user): JsonResponse
    {
        return new JsonResponse(
            $this->service->getShoppingCartDetails(Uuid::fromString($user))
        );
    }

    public function finish(string $user, Request $request): JsonResponse
    {
        try {
            return new JsonResponse(
                $this->service->finish(Uuid::fromString($user), $request)
            );
        } catch (BadRequestException $exception) {
            return new JsonResponse([
                'error' => $exception->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    public function remove(string $user): JsonResponse
    {
        $this->service->remove(Uuid::fromString($user));

        return new JsonResponse(status: Response::HTTP_NO_CONTENT);
    }
}
