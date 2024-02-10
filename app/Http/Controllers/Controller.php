<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *      title="Bank Pay API",
 *      version="1.0.0",
 *      description="This API is structured to carry out transactions between users.",
 *      @OA\Contact(
 *          email="malu.voltolini@outlook.com",
 *          name="Maria Luiza"
 *      )
 * )
 */

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
