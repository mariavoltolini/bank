<?php

namespace App\Services;

use App\Exceptions\AuthorizationException;

class BalanceUserVerificationService
{
    public function verify(float $balance, float $transactionValue): void
    {
        if ($transactionValue > $balance) {
            throw new AuthorizationException('Insufficient balance!');
        }
    }
}
