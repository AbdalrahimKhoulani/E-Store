<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'first_name' => 'required',
            'last_name' => 'required'
        ]);
        if ($validator->fails()) {
            return new JsonResponse([
                'status' => false,
                'message' => $validator->errors(),
            ], 500);
        }


        $user = User::create($request->all());

        return new JsonResponse([
            'status' => true,
            'message' => 'User created successfully',
            'data' => $user
        ], 201);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $newUser = User::find($user->id);
        if ($newUser == null) {
            $this->store($request);
        }

        $user->update($request->all());

        return new JsonResponse([
            'status' => true,
            'message' => 'User updated successfully',
            'data' => $user
        ], 204);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        if ($user) {
            $user->delete();
        }

        return new JsonResponse([
            'status' => true,
            'message' => 'User deleted successfully'
        ], 204);
    }
}
