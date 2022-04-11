<?php

namespace App\Listeners;

use App\Events\NeedsUpdateGithubInfos;
use App\Services\GithubService;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateDataFromGithub implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(
        private GithubService $githubService,
    ) {}

    /**
     * Handle the event.
     *
     * @param  NeedsUpdateGithubInfos  $event
     * @return void
     */
    public function handle(NeedsUpdateGithubInfos $event)
    {
        $newUser = $this->githubService->getByUserName($event->user->userName);

        $data = $this->githubService->formatDataToDataBase($newUser, [
            'email' => $event->user->email,
            'gender' => $event->user->gender
        ]);

        $event->user->update($data);
    }
}
