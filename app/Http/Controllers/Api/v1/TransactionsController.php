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

   /**
     * @OA\Post(
     *     path="/api/v1/transactions",
     *     summary="Create new transaction",
     *     operationId="createTransaction",
     *     tags={"Transactions"},
     *     security={ {"bearerAuth": {} } },
     *     @OA\RequestBody(
     *         required=true,
     *         description="Body",
     *         @OA\JsonContent(
     *             required={"value", "payer_id", "receiver_id"},
     *             @OA\Property(property="value", type="number", example=100.50),
     *             @OA\Property(property="payer_id", type="string", format="uuid", example="4c410523-8b73-43ad-9840-23c2d5d91f31"),
     *             @OA\Property(property="receiver_id", type="string", format="uuid", example="f7a7a4c5-23e3-4762-bd02-04fa6d06152b"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Transaction created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Transaction created successfully!"),
     *         ),
     *     ),
     *      @OA\Response(
     *         response=400,
     *         description="Wallet not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The customer's wallet was not found"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Unauthorized transaction",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Insufficient balance!"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation errors",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Validation errors"),
     *             @OA\Property(property="errors", type="object", example={"value": {"The value field is required."}}),
     *         ),
     *     ),
     * )
     */
    public function store(TransactionsRequest $request): JsonResponse
    {
        $transactionData = $request->validated();
        $this->transactionServ->createTransaction($transactionData);

        return response()->json([
            'message' => 'Transaction created successfully!',
        ], 201);
    }
}
