<?php

namespace App\Services;

use App\Contracts\TransactionsRepository;
use App\Contracts\UsersRepository;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

class TransactionsService
{
    public function __construct(
        private TransactionsRepository $transactionsRepository,
        private UsersRepository $usersRepository
        )
    {
    }

    public function create(array $transaction): void
    {
        $this->verifyUserPayer($transaction['payer_id']);
    }

    private function verifyUserPayer(string $id) // : User
    {
        $user = $this->usersRepository->findByIdWithWallet($id);

        if ($user->type == 2) {
            $response = new JsonResponse([
                'message' => 'This type of user is not authorized to carry out transactions',
            ], 403);

            throw new HttpResponseException($response);
        }
    }
}
