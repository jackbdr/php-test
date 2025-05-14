<?php

use App\Http\Controllers\API\TaskController;
use Illuminate\Support\Facades\Route;

Route::apiResource('tasks', TaskController::class)->only([
    'index', 'show', 'store'
]);

Route::put('/tasks/{task}/edit', [TaskController::class, 'signedUpdate'])
    ->middleware('signed')
    ->name('tasks.edit.signed');

Route::delete('/tasks/{task}/delete', [TaskController::class, 'signedDestroy'])
    ->middleware('signed')
    ->name('tasks.delete.signed');
