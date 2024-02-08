<?php

namespace App\Services;

use App\Contracts\MovementsRepository;
use InvalidArgumentException;

class MovementsService
{
    public function __construct(
        private MovementsRepository $movementsRepository
        )
    {
    }

    public function create(string $payerId, string $receiverId, int $transactionId) : void
    {

    }
}
