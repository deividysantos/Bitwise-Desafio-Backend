<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('/user', [UserController::class, 'create'])
    ->name('user.create');

Route::post('/user/github/', [UserController::class, 'createByGithub'])
    ->name('user.createByGithub');

Route::get('/user/username/{userName}', [UserController::class, 'getByUserName'])
    ->name('user.getByUserName');

Route::get('/user/email/{email}', [UserController::class, 'getByEmail'])
    ->name('user.getByEmail');

Route::get('/users', [UserController::class, 'getAll'])
    ->name('user.getAll');

Route::patch('/user', [UserController::class, 'update'])
    ->name('user.update');
