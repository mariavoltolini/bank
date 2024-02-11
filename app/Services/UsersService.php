<?php

namespace App\Services;

use App\Contracts\UsersRepository;
use App\Contracts\WalletsRepository;
use Illuminate\Database\DatabaseManager;
use Ramsey\Uuid\Uuid;

class UsersService
{
    public function __construct(
        private UsersRepository $usersRepo,
        private WalletsRepository $walletsRepo,
        private UserTypeVerificationService $userTypeVerifyServ,
        private DatabaseManager $database
        )
    {
    }

    public function createUser(array $user): ?string
    {
        $this->database->beginTransaction();

        try {
            $type = $this->userTypeVerifyServ->verify($user['type']);

            $uuid = Uuid::uuid4()->toString();

            $arrayUser = [
                'id' => $uuid,
                'name' => $user['name'],
                'email' => $user['email'],
                'password' => $user['password'],
                'type' => $type,
                'document' => $user['document']
            ];

            $this->usersRepo->create($arrayUser);

            $arrayWallet = [
                'user_id' => $uuid,
                'balance' => 0
            ];

            $this->walletsRepo->create($arrayWallet);

            $this->database->commit();

            return $uuid;
        } catch (\Exception $e) {
            $this->database->rollBack();
            throw $e;
        }
    }
}
