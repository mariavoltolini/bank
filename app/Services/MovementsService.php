<?php

namespace App\Services;

use App\Contracts\MovementsRepository;

class MovementsService
{
    public function __construct(
        private MovementsRepository $movementsRepo
    ) {
    }

    public function create(string $payerId, string $receiverId, int $transactionId): void
    {
        $this->prepareArrayAndCreateMovement($payerId, $transactionId, 'debit');
        $this->prepareArrayAndCreateMovement($receiverId, $transactionId, 'credit');
    }

    private function prepareArrayAndCreateMovement(string $userId, int $transactionId, string $type): void
    {
        $typeMovement = $this->verifyMovementType($type);

        $arrMovement = [
            'transaction_id' => $transactionId,
            'type' => $typeMovement,
            'user_id' => $userId
        ];

        $this->movementsRepo->create($arrMovement);
    }

    private function verifyMovementType(string $type): ?int
    {
        switch ($type) {
            case 'debit':
                return 1;
            case 'credit':
                return 2;
        }
    }
}
