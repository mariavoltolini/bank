<?php

namespace App\Contracts;

use App\Models\User;

interface UsersRepository
{
    public function create(array $arrUser): User;

    public function findById(string $user_id): ?User;
}
