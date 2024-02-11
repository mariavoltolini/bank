<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\UsersRequest;
use App\Services\UsersService;
use Illuminate\Http\JsonResponse;

class UsersController extends Controller
{
    public function __construct(private UsersService $usersServ)
    {
    }

    /**
     * @OA\Post(
     *     path="/api/v1/users",
     *     summary="Create new user",
     *     operationId="createUser",
     *     tags={"Users"},
     *     security={ {"bearerAuth": {} } },
     *     @OA\RequestBody(
     *     required=true,
     *     description="Body",
     *          @OA\JsonContent(
     *              required={"name", "email", "document", "password", "type"},
     *              @OA\Property(property="name", type="string"),
     *              @OA\Property(property="email", type="string", format="email"),
     *              @OA\Property(property="document", type="string", format="regex", pattern="^[0-9]+$", minLength=11, maxLength=14),
     *              @OA\Property(property="password", type="string", minLength=6, maxLength=12),
     *              @OA\Property(property="type", type="string", enum={"user", "merchant"}),
     *              @OA\Property(property="balance", type="number", example=100.50),

     *          ),
     *      ),
     *     @OA\Response(
     *         response=201,
     *         description="User created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="User created successfully!"),
     *             @OA\Property(property="id", type="string", example="9bc22136-5dc2-4436-94ca-c39400af230b"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation errors",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Validation errors"),
     *             @OA\Property(property="errors", type="object", example={"email": {"The email field is required."}}),
     *         ),
     *     ),
     * )
     */
    public function store(UsersRequest $request): JsonResponse
    {
        $userData = $request->validated();
        $id = $this->usersServ->createUser($userData);

        return response()->json([
            'message' => 'User created successfully!',
            'id' => $id
        ], 201);
    }
}
