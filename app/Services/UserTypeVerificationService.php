<?php

namespace App\Services;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

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
                $response = new JsonResponse([
                    'message' => 'invalid user type!',
                ], 422);

                throw new HttpResponseException($response);
        }
    }
}
