<?php

namespace App\Repositories;

use App\Contracts\TransactionsRepository;
use App\Models\Transaction;

class EloquentTransactionsRepository implements TransactionsRepository
{
    public function __construct(private Transaction $transaction)
    {
    }

    public function create(array $arrTransaction): Transaction
    {
        return $this->transaction->create($arrTransaction);
    }
}
