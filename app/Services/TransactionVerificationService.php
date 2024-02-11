<?php

namespace App\Services;

use App\Exceptions\AuthorizationException;

class TransactionVerificationService
{
    public function __construct(private ApiService $apiServ)
    {
    }

    public function verify(array $transaction): void
    {
        try
        {
            $transactionAuthUrl = env('TRANSACTION_AUTH_URL');

            $return = $this->apiServ->post($transactionAuthUrl, $transaction);

            if ($return->successful()) {
                $jsonData = $return->json();

                if ($jsonData['message'] === 'Autorizado') {
                    return;
                }
            }

            throw new AuthorizationException('Unauthorized transaction!');
        } catch (\Exception $e) {
            throw new AuthorizationException('Unauthorized transaction!');
        }
    }
}
