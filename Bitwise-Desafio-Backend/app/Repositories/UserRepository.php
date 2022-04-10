<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class UserRepository
{

    public function __construct(
        private User $user
    ) {}

    public function create($payload): Model
    {
        return $this->user->query()->create($payload);
    }

    public function exists(string $username): bool
    {
        $user = $this->user
            ->query()
            ->where('username', $username)
            ->first();

        return $user !== null;
    }
}
