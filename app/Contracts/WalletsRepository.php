<?php

namespace App\Contracts;

use App\Models\Wallet;

interface WalletsRepository
{
    public function create(array $arrWallet): Wallet;

    public function findByUserId(string $userId): ?Wallet;

    public function update(string $userId, array $arrUpdate) : void;
}
