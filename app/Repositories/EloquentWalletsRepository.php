<?php

namespace App\Repositories;

use App\Contracts\TransactionsRepository;
use App\Contracts\WalletsRepository;
use App\Models\Wallet;

class EloquentWalletsRepository implements WalletsRepository
{
    public function create(Array $arrWallet) : Wallet
    {
        return Wallet::create($arrWallet);
    }
}
