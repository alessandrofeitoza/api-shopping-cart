<?php

declare(strict_types=1);

namespace App\Providers;

use App\Domain\Repository\ProductCategoryRepositoryInterface;
use App\Infrastructure\Repository\ProductCategoryRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ProductCategoryRepositoryInterface::class, ProductCategoryRepository::class);
    }

    public function boot(): void
    {
    }
}
