<?php

namespace App\Services;

use App\Contracts\TransactionsRepository;
use App\Contracts\UsersRepository;
use App\Contracts\WalletsRepository;
use Illuminate\Database\DatabaseManager;

class TransactionsService
{
    public function __construct(
        private TransactionsRepository $transactionsRepo,
        private UsersRepository $usersRepo,
        private WalletsRepository $walletsRepo,
        private MovementsService $movementsServ,
        private WalletsService $walletsServ,
        private UserPayerVerificationService $payerVerifyServ,
        private BalanceUserVerificationService $balanceVerifyServ,
        private TransactionVerificationService $transactionVerifyServ,
        private SendEmailService $sendEmailServ,
        private DatabaseManager $database
    ) {
    }

    public function createTransaction(array $transaction): void
    {
        $payer = $this->usersRepo->findById($transaction['payer_id']);

        $payerWallet = $this->walletsRepo->findByUserId($transaction['payer_id']);

        $this->payerVerifyServ->verify($payer->type);

        $this->balanceVerifyServ->verify($payerWallet->balance, $transaction['value']);

        try {
            $this->database->beginTransaction();

            $newTransaction = $this->transactionsRepo->create($transaction);

            $this->movementsServ->create($transaction['payer_id'], $transaction['receiver_id'], $newTransaction->id, $transaction['value']);

            $this->walletsServ->update($transaction['payer_id'], $transaction['value'], 'debit');

            $this->walletsServ->update($transaction['receiver_id'], $transaction['value'], 'credit');

            $this->transactionVerifyServ->verify($transaction);

            $this->database->commit();

            $this->sendEmailServ->sendEmail($newTransaction->id, $transaction['receiver_id']);
        } catch (\Exception $e) {
            $this->database->rollback();
            throw $e;
        }
    }
}
