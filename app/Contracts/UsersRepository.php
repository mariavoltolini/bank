<?php

namespace App\Contracts;

use App\Models\User;

interface UsersRepository
{
    public function create(Array $request): User;
}
