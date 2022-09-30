<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);

        if (Auth::attempt([
            'email' => $request['email'],
            'password' => $request['password']
        ])) {

            $user = Auth::user();
            $success = [];
            $success['user'] = $user;
            $success['token'] = $user->createToken('token-name')->plainTextToken;

            return new JsonResponse([
                'status' => true,
                'message' => 'User loged in successfully',
                'data' => $success
            ], 200);
        } else {
            return new JsonResponse([
                'status' => false,
                'message' => 'Unauthorize'
            ], 401);
        }
    }
}
