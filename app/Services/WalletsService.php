<?php

namespace App\Services;

use App\Contracts\WalletsRepository;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

class WalletsService
{
    public function __construct(
        private WalletsRepository $walletsRepository
    ) {
    }

    public function update(string $userId, float $value, string $type)
    {
        $wallet = $this->walletsRepository->findByUserId($userId);

        if (!$wallet) {
            $response = new JsonResponse([
                'message' => "The customer's wallet was not found",
            ], 400);

            throw new HttpResponseException($response);
        }

        $newBalance = $this->calculateNewBalance($value, $wallet->balance, $type);

        $this->walletsRepository->update($userId, ['balance' => $newBalance]);
    }

    public function calculateNewBalance(float $transactionValue, float $walletValue, string $type) : ?int
    {
        switch ($type) {
            case 'debit':
                return $walletValue - $transactionValue;
            case 'credit':
                return $walletValue + $transactionValue;
        }
    }
}