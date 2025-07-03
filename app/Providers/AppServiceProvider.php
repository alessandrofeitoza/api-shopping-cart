<?php

declare(strict_types=1);

namespace App\Providers;

use App\Domain\Repository\ExampleRepository;
use App\Domain\Repository\RepositoryInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(RepositoryInterface::class, ExampleRepository::class);
    }

    public function boot(): void
    {
    }
}
