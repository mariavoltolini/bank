<?php

namespace App\Contracts;

use App\Models\Movement;

interface MovementsRepository
{
    public function create(array $arrMovement): Movement;

    public function update(int $movementId, array $arrUpdate) : void;

    public function findMovementById(int $movementId): Movement;
}
