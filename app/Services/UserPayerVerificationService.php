<?php

namespace App\Services;

use App\Exceptions\AuthorizationException;

class UserPayerVerificationService
{
    public function verify(int $type): void
    {
        if ($type == 2) {
            throw new AuthorizationException('This type of user is not authorized to carry out transactions');
        }
    }
}
