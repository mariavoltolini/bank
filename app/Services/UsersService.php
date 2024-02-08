<?php

namespace App\Services;

use App\Contracts\UsersRepository;
use App\Contracts\WalletsRepository;
use InvalidArgumentException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class UsersService
{
    public function __construct(
        private UsersRepository $usersRepository,
        private WalletsRepository $walletsRepository
        )
    {
    }

    public function create(array $user): void
    {
        DB::beginTransaction();

        try {
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

            $this->usersRepository->create($arrayUser);

            $arrayWallet = [
                'user_id' => $id,
                'balance' => 0
            ];

            $this->walletsRepository->create($arrayWallet);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
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
