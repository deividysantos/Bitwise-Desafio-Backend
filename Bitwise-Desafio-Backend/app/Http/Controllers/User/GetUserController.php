<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use Illuminate\Http\JsonResponse;
use App\Services\GithubService;

class GetUserController extends Controller
{
    public function __construct(
        private UserRepository $userRepository,
        private GithubService $githubService,
    ) {}

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

    public function getAll(): JsonResponse
    {
        return response()->json([
            'message' => 'Successfully.',
            $this->userRepository->getAll()
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
}
