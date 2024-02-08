<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\TransactionsRequest;
use App\Services\TransactionsService;
use Illuminate\Http\Request;

class TransactionsController extends Controller
{
    public function __construct(private TransactionsService $service)
    {
    }

    public function store(TransactionsRequest $request)
    {
        $userData = $request->validated();
        $this->service->create($userData);
    }
}
