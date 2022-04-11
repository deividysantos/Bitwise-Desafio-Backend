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
        if($this->userRepository->existsByUserName($request['userName']))
            return response()->json([
                'message' => 'User already created.'
            ], 404);

        if(! $this->githubService->existsByUserName($request['userName']))
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

    public function getByUserName(string $userName): JsonResponse
    {
        if(!$this->userRepository->existsByUserName($userName))
            return response()->json([
                'message' => 'User not exists!'
            ]);

        return response()->json([
           'message' => 'User found successfully.',
           'data' => $this->formatResponseDataToSearchByUserName($userName)
        ]);
    }

    public function getByEmail(string $email): JsonResponse
    {
        $exists = $this->userRepository->existsByEmail($email);

        if(!$exists)
            return response()->json([
                'message' => 'User not exists!'
            ]);

        return response()->json([
           'message' => 'User found successfully.',
           'data' => $this->formatResponseDataToSearchByUserName($exists->userName)
        ]);
    }

    private function formatResponseDataToSearchByUserName(string $userName): array
    {
        $infosDataBase = $this->userRepository->existsByUserName($userName);

        if($this->githubService->existsByUserName($userName))
        {
            $additionalInfosByGitHub = $this->githubService->getAdditionalInformationsByUserName($userName);
            return array_merge((array) $infosDataBase, $additionalInfosByGitHub);
        }

        return (array) $infosDataBase;
    }

    public function getAll(): JsonResponse
    {
        return response()->json([
            'message' => 'Successfully.',
            $this->userRepository->getAll()
        ]);
    }
}
