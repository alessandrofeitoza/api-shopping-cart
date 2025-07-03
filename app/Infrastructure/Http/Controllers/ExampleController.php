<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Controllers;

use App\Domain\Service\ExampleService;
use Symfony\Component\HttpFoundation\JsonResponse;

class ExampleController extends Controller
{
    public function __construct(
        private ExampleService $exampleService
    ) {
    }

    public function test(): JsonResponse
    {
        return new JsonResponse(
            $this->exampleService->findAll()
        );
    }
}
