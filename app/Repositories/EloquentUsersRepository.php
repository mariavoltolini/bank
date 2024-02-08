<?php

namespace App\Repositories;

use App\Contracts\UsersRepository;
use App\Models\User;

class EloquentUsersRepository implements UsersRepository
{
    public function create(Array $arrUser) : User
    {
        return User::create($arrUser);
    }
}
