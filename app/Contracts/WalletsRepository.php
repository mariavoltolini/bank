<?php

namespace App\Contracts;

use App\Models\Wallet;

interface WalletsRepository
{
    public function create(Array $request): Wallet;
}
