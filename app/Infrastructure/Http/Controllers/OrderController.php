<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Controllers;

use App\Domain\Service\OrderService;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\JsonResponse;

class OrderController extends Controller
{
    public function __construct(
        private OrderService $service
    ) {
    }

    public function getList(string $user): JsonResponse
    {
        return new JsonResponse(
            $this->service->findAllByUser(Uuid::fromString($user))
        );
    }
}
