<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Traits\HostNames;
use Exception;
use Http;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Validator;

class AuthController extends Controller
{

    use HostNames;
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'email' => 'required',
            'password' => 'required'
        ]);

        if($validator->fails()){
            return new JsonResponse([
                'status' => false,
                'message'=> $validator->errors()
            ],400);
        }

        try{
          return  Http::post($this->getUsers_MS().'/login',[
                'email' => $request['email'],
                'password' => $request['password']
            ]);
        }catch(Exception $e){

            return new JsonResponse([
                'status' => false,
                'message'=> $e->getMessage()
            ],500);
        }
    }
}
