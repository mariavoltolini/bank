<?php

namespace App\Services;

use App\Exceptions\BusinessException;

class UserTypeVerificationService
{
    public function verify(string $type): int
    {
        switch ($type) {
            case 'user':
                return 1;
            case 'merchant':
                return 2;
            default:
                throw new BusinessException('Invalid user type!');
        }
    }
}
