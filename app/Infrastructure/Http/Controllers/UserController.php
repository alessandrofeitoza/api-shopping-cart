<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Controllers;

use App\Domain\Service\UserService;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    public function __construct(
        private UserService $service
    ) {
    }

    public function getList(): JsonResponse
    {
        return new JsonResponse(
            $this->service->findAll()
        );
    }

    public function get(string $id): JsonResponse
    {
        return new JsonResponse(
            $this->service->find(Uuid::fromString($id))
        );
    }

    public function create(Request $request): JsonResponse
    {
        $user = $this->service->create($request->toArray());

        return new JsonResponse(
            $user,
            status: Response::HTTP_CREATED
        );
    }

    public function update(string $id, Request $request): JsonResponse
    {
        $user = $this->service->update(
            Uuid::fromString($id),
            $request->toArray()
        );

        return new JsonResponse($user);
    }

    public function remove(string $id): JsonResponse
    {
        $this->service->remove(Uuid::fromString($id));

        return new JsonResponse(status: Response::HTTP_NO_CONTENT);
    }
}
