<?php

namespace App\Http\Controllers\User;

use App\Http\Requests\User\CreateRequest;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use Illuminate\Http\JsonResponse;

class CreateUserController extends Controller
{
    public function __construct(
        private UserRepository $userRepository,
    ) {}

    public function create(CreateRequest $request): JsonResponse
    {
        if( $this->userRepository->create($request->validated()) )
            return response()->json([
                'message' => 'User created successfully!'
            ], 201);

        return response()->json([
            'message' => 'Creation failure!'
        ], 500);
    }
}
