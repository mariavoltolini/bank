<?php

namespace App\Contracts;

use App\Models\EmailTransactionLog;

interface EmailsTransactionsRepository
{
    public function create(array $arrLog): EmailTransactionLog;
}
