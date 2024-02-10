<?php

namespace App\Services;

use App\Contracts\MovementsRepository;

class MovementsService
{
    public function __construct(
        private MovementsRepository $movementsRepo
    ) {
    }

    public function create(string $payerId, string $receiverId, int $transactionId, float $value): void
    {
        $this->prepareArrayAndCreateMovement($payerId, $transactionId, 'debit', $value);
        $this->prepareArrayAndCreateMovement($receiverId, $transactionId, 'credit', $value);
    }

    private function prepareArrayAndCreateMovement(string $userId, int $transactionId, string $type, float $value): void
    {
        $typeMovement = $this->verifyMovementType($type);

        $arrMovement = [
            'transaction_id' => $transactionId,
            'type' => $typeMovement,
            'user_id' => $userId
        ];

        $arrMovement['value'] = $typeMovement === 1 ? -$value : $value ;

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
