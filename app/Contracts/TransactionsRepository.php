<?php

namespace App\Contracts;

use App\Models\Transaction;

interface TransactionsRepository
{
    public function create(array $arrTransaction): Transaction;
}
