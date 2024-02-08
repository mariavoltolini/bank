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
        private MovementsService $movementsService,
        private WalletsService $walletsService,
        private UserPayerVerificationService $userPayerVerificationService,
        private BalanceUserVerificationService $balanceUserVerificationService,
        private TransactionAuthorizationVerificationService $transactionAuthorizationVerificationService
    ) {
    }

    public function create(array $transaction): void
    {
        $payer = $this->usersRepository->findById($transaction['payer_id']);

        $payerWallet = $this->walletsRepository->findByUserId($transaction['payer_id']);

        $this->userPayerVerificationService->verify($payer->type);

        $this->balanceUserVerificationService->verify($payerWallet->balance, $transaction['value']);

        try {
            DB::beginTransaction();

            $newTransaction = $this->transactionsRepository->create($transaction);

            $this->movementsService->create($transaction['payer_id'], $transaction['receiver_id'], $newTransaction->id);

            $this->walletsService->update($transaction['payer_id'], $transaction['value'], 'debit');

            $this->walletsService->update($transaction['receiver_id'], $transaction['value'], 'credit');

            $this->transactionAuthorizationVerificationService->verify($transaction);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
}
