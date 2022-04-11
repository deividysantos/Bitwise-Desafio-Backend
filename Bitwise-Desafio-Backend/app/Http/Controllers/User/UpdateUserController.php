<?php

namespace App\Http\Controllers\User;

use App\Http\Requests\User\UpdateRequest;
use App\Events\NeedsUpdateGithubInfos;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use Illuminate\Http\JsonResponse;
use App\Services\GithubService;

class UpdateUserController extends Controller
{
    public function __construct(
        private UserRepository $userRepository,
        private GithubService $githubService,
    ) {}

    public function update(UpdateRequest $request)
    {
        $user = $this->userRepository->existsByUserName($request['userName']);

        if(!$user)
            return response()->json([
                'message' => 'User not found'
            ], 404);

        if(
            isset($request['data']['userName']) &&
            !$this->githubService->existsByUserName($request['data']['userName'])
        )
            return response()->json([
                'message' => 'This new user name not exists in github.'
            ], 404);


        $user->update($request->validated()['data']);

        if(!$user->userName->wasChenged())
            NeedsUpdateGithubInfos::dispatch($user);

        return response()->json([
            'message' => 'Data updated successfully.'
        ]);
    }

    public function updateGithubInfos(string $userName): JsonResponse
    {
        $user = $this->userRepository->existsByUserName($userName);

        if(!$user)
            return response()->json([
                'message' => 'User not found'
            ], 404);

        NeedsUpdateGithubInfos::dispatch($user);

        return response()->json();
    }
}
