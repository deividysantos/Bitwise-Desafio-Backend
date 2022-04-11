<?php

namespace App\Listeners;

use App\Events\NameUserUpdated;
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
     * @param  NameUserUpdated  $event
     * @return void
     */
    public function handle(NameUserUpdated $event)
    {
        $newUser = $this->githubService->getByUserName($event->user->userName);

        $data = $this->githubService->formatDataToDataBase($newUser, [
            'email' => $event->user->email,
            'gender' => $event->user->gender
        ]);

        $event->user->update($data);
    }
}
