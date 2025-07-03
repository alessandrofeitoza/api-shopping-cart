<?php

declare(strict_types=1);

use App\Infrastructure\Http\Controllers\ExampleController;
use Illuminate\Support\Facades\Route;

Route::get('/test', [ExampleController::class, 'test']);
