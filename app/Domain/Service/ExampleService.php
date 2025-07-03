<?php

declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\Repository\RepositoryInterface;

class ExampleService
{
    public function __construct(
        private RepositoryInterface $exampleRepository
    ) {
    }

    public function findAll(): array
    {
        return [
            $this->exampleRepository->get(1),
            $this->exampleRepository->get(2),
        ];
    }
}
