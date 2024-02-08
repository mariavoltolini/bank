<?php

namespace App\Services;

use App\Contracts\UsersRepository;
use InvalidArgumentException;
use Illuminate\Http\Response;
use Ramsey\Uuid\Uuid;

class UsersService
{
    public function __construct(private UsersRepository $repository)
    {
    }
    public function create(Array $user) : void
    {
        $type = $this->verifyTypeUser($user['type']);
        $id = Uuid::uuid4()->toString();

        $arrayUser = [
            'id' => $id,
            'name' => $user['name'],
            'email' => $user['email'],
            'password' => $user['password'],
            'type' => $type,
            'document' => $user['document']
        ];

        $this->repository->create($arrayUser);
    }

    private function verifyTypeUser(String $type) : int
    {
        switch ($type) {
            case 'user':
                return 1;
            case 'merchant':
                return 2;
            default:
                throw new InvalidArgumentException("invalid user type", Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
