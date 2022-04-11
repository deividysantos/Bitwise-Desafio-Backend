<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
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

    public function existsByUserName(string $username): Model|bool
    {
        $user = $this->user
            ->query()
            ->where('username', $username)
            ->first();

        if ($user !== null)
        {
            return $user;
        }

        return false;
    }

    public function existsByEmail(string $email): Model|bool
    {
        $user = $this->user
            ->query()
            ->where('email', $email)
            ->first();

        if ($user !== null)
        {
            return $user;
        }

        return false;
    }

    public function getAll(): LengthAwarePaginator
    {
        return $this->user->query()->paginate(15);
    }
}
