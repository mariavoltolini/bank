<?php

namespace App\Services;

use App\Contracts\EmailsTransactionsRepository;
use App\Contracts\UsersRepository;

class SendEmailService
{
    public function __construct(
        private ApiService $apiService,
        private UsersRepository $usersRepository,
        private EmailsTransactionsRepository $emailsTransactionsRepository
    ) {
    }

    public function sendEmail(int $transactionId, string $userId): bool
    {
        $url = env('TRANSACTION_SEND_EMAIL_URL');

        $user = $this->usersRepository->findById($userId);

        $emailData = $this->prepareEmailData($user);

        $response = $this->apiService->post($url, $emailData);

        if ($response->successful()) {
            $this->createEmailTransactionLog($transactionId, true);
            return true;
        }

        $this->createEmailTransactionLog($transactionId, false);
        return false;
    }

    private function prepareEmailData($user): array
    {
        return [
            'email' => $user->email,
            'message' => 'You have received a new transaction'
        ];
    }

    private function createEmailTransactionLog(int $transactionId, bool $status): void
    {
        $logData = [
            'transaction_id' => $transactionId,
            'status' => $status
        ];

        $this->emailsTransactionsRepository->create($logData);
    }
}
