<?php

namespace App\Services;

use App\Contracts\WalletsRepository;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

class WalletsService
{
    public function __construct(
        private WalletsRepository $walletsRepo
    ) {
    }

    public function update(string $userId, float $value, string $type)
    {
        $wallet = $this->walletsRepo->findByUserId($userId);

        if (!$wallet) {
            $response = new JsonResponse([
                'message' => "The customer's wallet was not found",
            ], 400);

            throw new HttpResponseException($response);
        }

        $newBalance = $this->calculateNewBalance($value, $wallet->balance, $type);

        $this->walletsRepo->update($userId, ['balance' => $newBalance]);
    }

    private function calculateNewBalance(float $transactionValue, float $walletValue, string $type) : ?float
    {
        switch ($type) {
            case 'debit':
                return $walletValue - $transactionValue;
            case 'credit':
                return $walletValue + $transactionValue;
        }
    }
}
