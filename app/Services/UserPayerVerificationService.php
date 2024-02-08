<?php

namespace App\Services;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

class UserPayerVerificationService
{
    public function verify(int $type): void
    {
        if ($type == 2) {
            $response = new JsonResponse([
                'message' => 'This type of user is not authorized to carry out transactions',
            ], 403);

            throw new HttpResponseException($response);
        }
    }
}
