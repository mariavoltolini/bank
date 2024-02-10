<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\TransactionsRequest;
use App\Services\TransactionsService;
use Illuminate\Http\JsonResponse;

class TransactionsController extends Controller
{
    public function __construct(private TransactionsService $transactionServ)
    {
    }

    public function store(TransactionsRequest $request): JsonResponse
    {
        $transactionData = $request->validated();
        $this->transactionServ->createTransaction($transactionData);

        return response()->json([
            'message' => 'Transaction created successfully!',
        ], 201);
    }
}
