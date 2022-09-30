<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CheckAuthorizeController extends Controller
{

    public function isAdmin(){

        return new JsonResponse([
            'status' => true,
            'message' => 'User is admin'
        ], 200);
    }

    public function isCustomer(){

        return new JsonResponse([
            'status' => true,
            'message' => 'User is customer'
        ], 200);
    }
}
