<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;

Route::apiResource('tasks' , TaskController::class);
