<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\CreateByGithubRequest;
use App\Http\Requests\User\CreateRequest;
use App\Repositories\UserRepository;
use App\Services\GithubService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function __construct(
        private UserRepository $userRepository,
        private GithubService $githubService,
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

    public function createByGithub(CreateByGithubRequest $request): JsonResponse
    {
        if($this->userRepository->exists($request['userName']))
            return response()->json([
                'message' => 'User already created.'
            ], 404);

        if(! $this->githubService->userNameExists($request['userName']))
            return response()->json([
                'message' => 'Username not found in github.'
            ], 404);

        $payload = $this->githubService->getByUserName($request->validated());

        try{

            $this->userRepository->create($payload);

        }catch(\Exception $e){

            Log::error($e->getMessage());

            return response()->json([
                'message' => 'Creation failure!'
            ], 500);
        }

        return response()->json([
            'message' => 'User created successfully!',
            'data' => $payload
        ], 201);
    }
}
