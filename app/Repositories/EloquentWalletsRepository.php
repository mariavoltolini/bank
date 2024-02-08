<?php

namespace App\Repositories;

use App\Contracts\WalletsRepository;
use App\Models\Wallet;

class EloquentWalletsRepository implements WalletsRepository
{
    public function __construct(private Wallet $wallet)
    {
    }

    public function create(array $walletData): Wallet
    {
        return $this->wallet->create($walletData);
    }

    public function findByUserId(string $userId): ?Wallet
    {
        return $this->wallet->where('user_id', $userId)->first();
    }

    public function update(string $userId, array $arrUpdate): void
    {
        $wallet = $this->findByUserId($userId);
        $wallet->update($arrUpdate);
    }
}
