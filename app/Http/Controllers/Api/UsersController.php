<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UsersRequest;
use App\Services\UsersService;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function __construct(private UsersService $service)
    {
    }

    public function store(UsersRequest $request)
    {
        $userData = $request->validated();
        $this->service->create($userData);
    }
}
