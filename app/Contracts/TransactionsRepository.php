<?php

namespace App\Contracts;

use App\Models\Transaction;

interface TransactionsRepository
{
    public function create(Array $request): Transaction;
}
