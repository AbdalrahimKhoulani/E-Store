<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Exception;
use Http;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Validator;

class AuthController extends Controller
{

    public function users_Ms()
    {
        return "http://127.0.0.1:8001/api";
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return new JsonResponse([
                'status' => false,
                'message' => $validator->errors()
            ], 400);
        }

        try {
            return  Http::post($this->users_MS() . '/login', [
                'email' => $request['email'],
                'password' => $request['password']
            ])->json();
        } catch (Exception $e) {

            return new JsonResponse([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

}
