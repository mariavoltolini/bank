<?php

namespace App\Services;

use App\Contracts\TransactionsRepository;
use App\Contracts\UsersRepository;
use App\Contracts\WalletsRepository;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class TransactionsService
{
    public function __construct(
        private TransactionsRepository $transactionsRepository,
        private UsersRepository $usersRepository,
        private WalletsRepository $walletsRepository,
        private ApiService $apiService,
        private MovementsService $movementsService,
    ) {
    }

    public function create(array $transaction): void
    {
        $payer = $this->usersRepository->findById($transaction['payer_id']);
        $payerWallet = $this->walletsRepository->findByUserId($transaction['payer_id']);

        $this->verifyTypeUserPayer($payer->type);
        $this->verifyBalanceUserPayer($payerWallet->balance, $transaction['value']);

        try {
            DB::beginTransaction();

            $newTransaction = $this->transactionsRepository->create($transaction);

            $this->movementsService->create($transaction['payer_id'], $transaction['receiver_id'], $newTransaction->id);

            $this->verifyTransactionAuthorization($transaction);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }

    }

    private function verifyTypeUserPayer(int $type): void
    {
        if ($type == 2) {
            $response = new JsonResponse([
                'message' => 'This type of user is not authorized to carry out transactions',
            ], 403);

            throw new HttpResponseException($response);
        }
    }

    private function verifyBalanceUserPayer(float $balance, float $transactionValue): void
    {
        if ($transactionValue > $balance) {
            $response = new JsonResponse([
                'message' => 'Insufficient balance',
            ], 400);

            throw new HttpResponseException($response);
        }
    }

    private function verifyTransactionAuthorization(array $transaction): ?bool
    {
        $transactionAuthUrl = env('TRANSACTION_AUTH_URL');

        $return = $this->apiService->post($transactionAuthUrl, $transaction);

        if ($return->successful()) {
            $jsonData = $return->json();

            if ($jsonData['message'] === 'Autorizado') {
                return true;
            }
        }

        $response = new JsonResponse([
            'message' => 'Unauthorized transaction!',
        ], 400);

        throw new HttpResponseException($response);
    }
}
