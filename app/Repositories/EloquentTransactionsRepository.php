<?php

namespace App\Repositories;

use App\Contracts\TransactionsRepository;
use App\Models\Transaction;

class EloquentTransactionsRepository implements TransactionsRepository
{
    public function create(Array $arrTransaction) : Transaction
    {
        return Transaction::create($arrTransaction);
    }
}
