<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\UsersRequest;
use App\Services\UsersService;
use Illuminate\Http\JsonResponse;

class UsersController extends Controller
{
    public function __construct(private UsersService $service)
    {
    }

    public function store(UsersRequest $request) : JsonResponse
    {
        try {
            $userData = $request->validated();
            $this->service->create($userData);

            return response()->json([
                'message' => 'User created successfully!',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create user!',
                'errors' => $e->getMessage(),
            ], 500);
        }
    }
}
