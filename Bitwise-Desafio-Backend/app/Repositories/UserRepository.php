<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    public function create($payload)
    {
        User::query()->create($payload);
    }
}
