<?php

declare(strict_types=1);

namespace App\Providers;

use App\Domain\Repository\OrderRepositoryInterface;
use App\Domain\Repository\ProductCategoryRepositoryInterface;
use App\Domain\Repository\ProductRepositoryInterface;
use App\Domain\Repository\ShoppingCartItemRepositoryInterface;
use App\Domain\Repository\UserRepositoryInterface;
use App\Infrastructure\Repository\OrderRepository;
use App\Infrastructure\Repository\ProductCategoryRepository;
use App\Infrastructure\Repository\ProductRepository;
use App\Infrastructure\Repository\ShoppingCartItemRepository;
use App\Infrastructure\Repository\UserRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ProductCategoryRepositoryInterface::class, ProductCategoryRepository::class);
        $this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(ShoppingCartItemRepositoryInterface::class, ShoppingCartItemRepository::class);
        $this->app->bind(OrderRepositoryInterface::class, OrderRepository::class);
    }

    public function boot(): void
    {
    }
}
