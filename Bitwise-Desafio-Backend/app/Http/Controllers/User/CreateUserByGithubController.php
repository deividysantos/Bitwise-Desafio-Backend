<?php

namespace App\Http\Controllers\User;

use App\Http\Requests\User\CreateByGithubRequest;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;
use App\Services\GithubService;

class CreateUserByGithubController extends Controller
{
    public function __construct(
        private UserRepository $userRepository,
        private GithubService $githubService,
    ) {}

    public function createByGithub(CreateByGithubRequest $request): JsonResponse
    {
        if($this->userRepository->existsByUserName($request['userName']))
            return response()->json([
                'message' => 'User already created.'
            ], 404);

        if(! $this->githubService->existsByUserName($request['userName']))
            return response()->json([
                'message' => 'Username not found in github.'
            ], 404);

        $data = $this->githubService->getByUserName($request['userName']);

        $payload = $this->githubService->formatDataToDataBase($data, $request->validated());

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
