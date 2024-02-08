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
        private WalletsRepository $walletsRepository,
        private UserTypeVerificationService $userTypeVerificationService
        )
    {
    }

    public function create(array $user): void
    {
        DB::beginTransaction();

        try {
            $type = $this->userTypeVerificationService->verify($user['type']);

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
}
