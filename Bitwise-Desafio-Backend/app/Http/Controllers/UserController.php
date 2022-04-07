<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\CreateRequest;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    public function __construct(
        private UserRepository $repository,
    ) {}

    public function create(CreateRequest $request): JsonResponse
    {
        $this->repository->create($request->validated());

        return response()->json([
            'message' => 'User created successfully!'
        ], 201);
    }
}
