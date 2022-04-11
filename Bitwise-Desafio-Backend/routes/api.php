<?php

use App\Http\Controllers\User\CreateUserByGithubController;
use App\Http\Controllers\User\CreateUserController;
use App\Http\Controllers\User\UpdateUserController;
use App\Http\Controllers\User\GetUserController;
use Illuminate\Support\Facades\Route;


Route::post('/user', [CreateUserController::class, 'create'])
    ->name('user.create');

Route::post('/user/github/', [CreateUserByGithubController::class, 'createByGithub'])
    ->name('user.createByGithub');

Route::get('/user/username/{userName}', [GetUserController::class, 'getByUserName'])
    ->name('user.getByUserName');

Route::get('/user/email/{email}', [GetUserController::class, 'getByEmail'])
    ->name('user.getByEmail');

Route::get('/users', [GetUserController::class, 'getAll'])
    ->name('user.getAll');

Route::patch('/user', [UpdateUserController::class, 'update'])
    ->name('user.update');

Route::patch('/user/github/{userName}', [UpdateUserController::class, 'updateGithubInfos'])
    ->name('user.updateGithub');
