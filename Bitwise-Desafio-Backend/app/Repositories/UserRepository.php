<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class UserRepository
{
    public function create($payload): Model
    {
        return User::query()->create($payload);
    }
}
