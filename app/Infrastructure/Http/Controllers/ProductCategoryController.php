<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Controllers;

use App\Domain\Service\ProductCategoryService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProductCategoryController extends Controller
{
    public function __construct(
        private ProductCategoryService $service
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
            $this->service->find((int) $id)
        );
    }

    public function create(Request $request): JsonResponse
    {
        $category = $this->service->create($request->toArray());

        return new JsonResponse(
            $category,
            status: Response::HTTP_CREATED
        );
    }

    public function update(string $id, Request $request): JsonResponse
    {
        $category = $this->service->update(
            (int) $id,
            $request->toArray()
        );

        return new JsonResponse($category);
    }

    public function remove(string $id): JsonResponse
    {
        $this->service->remove((int) $id);

        return new JsonResponse(status: Response::HTTP_NO_CONTENT);
    }
}
