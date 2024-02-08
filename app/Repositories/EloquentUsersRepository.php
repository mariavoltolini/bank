<?php

namespace App\Repositories;

use App\Contracts\UsersRepository;
use App\Models\User;

class EloquentUsersRepository implements UsersRepository
{
    public function __construct(private User $user)
    {
    }

    public function create(array $userData): User
    {
        return $this->user->create($userData);
    }

    public function findById(string $userId): ?User
    {
        return $this->user->find($userId);
    }
}
