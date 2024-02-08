<?php

namespace App\Repositories;

use App\Contracts\EmailsTransactionsRepository;
use App\Models\EmailTransactionLog;

class EloquentEmailsTransactionsRepository implements EmailsTransactionsRepository
{
    public function __construct(private EmailTransactionLog $emailTransactionLog)
    {
    }

    public function create(array $arrLog): EmailTransactionLog
    {
        return $this->emailTransactionLog->create($arrLog);
    }
}
