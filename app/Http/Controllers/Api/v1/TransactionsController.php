<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\TransactionsRequest;
use App\Services\TransactionsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TransactionsController extends Controller
{
    public function __construct(private TransactionsService $service)
    {
    }

    public function store(TransactionsRequest $request) : JsonResponse
    {
        try {
            $transactionData = $request->validated();
            $this->service->create($transactionData);

            return response()->json([
                'message' => 'Transaction created successfully!',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create transaction!',
                'errors' => $e->getMessage(),
            ], 500);
        }
    }
}
