<?php

declare(strict_types=1);

use App\Infrastructure\Http\Controllers\ProductCategoryController;
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
