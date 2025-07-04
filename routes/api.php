<?php

declare(strict_types=1);

use App\Infrastructure\Http\Controllers\ProductCategoryController;
use App\Infrastructure\Http\Controllers\ProductController;
use App\Infrastructure\Http\Controllers\ShoppingCartController;
use App\Infrastructure\Http\Controllers\ShoppingCartItemController;
use App\Infrastructure\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::controller(ProductCategoryController::class)
    ->prefix('/product-categories')
    ->group(function (): void {
        Route::get('/', 'getList');
        Route::get('/{id}', 'get');
        Route::post('/', 'create');
        Route::patch('/{id}', 'update');
        Route::delete('/{id}', 'remove');
    });

Route::controller(UserController::class)
    ->prefix('/users')
    ->group(function (): void {
        Route::get('/', 'getList');
        Route::get('/{id}', 'get');
        Route::post('/', 'create');
        Route::patch('/{id}', 'update');
        Route::delete('/{id}', 'remove');
    });

Route::controller(ShoppingCartController::class)
    ->prefix('/users/{user}/shopping-cart')
    ->group(function (): void {
        Route::get('/', 'get');
        Route::delete('/', 'remove');
    });
Route::controller(ShoppingCartItemController::class)
    ->prefix('/users/{user}/cart-items')
    ->group(function (): void {
        Route::get('/', 'getList');
        Route::get('/{id}', 'get');
        Route::post('/', 'create');
        Route::patch('/{id}', 'update');
        Route::delete('/{id}', 'remove');
    });

Route::controller(ProductController::class)
    ->prefix('/products')
    ->group(function (): void {
        Route::get('/', 'getList');
        Route::get('/{id}', 'get');
        Route::post('/', 'create');
        Route::patch('/{id}', 'update');
        Route::delete('/{id}', 'remove');
    });
