<?php

namespace App\Repositories;

use App\Contracts\MovementsRepository;
use App\Models\Movement;

class EloquentMovementsRepository implements MovementsRepository
{
    public function __construct(private Movement $movement)
    {
    }

    public function create(array $arrMovement): Movement
    {
        return $this->movement->create($arrMovement);
    }

    public function findMovementById(int $movementId): Movement
    {
        return $this->movement->findOrFail($movementId);
    }
}

