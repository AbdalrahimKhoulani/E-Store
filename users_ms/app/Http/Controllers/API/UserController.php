<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use App\Traits\HostNames;
use Auth;
use Storage;
use Validator;

class UserController extends Controller
{
    public function order_MS()
    {
        return 'http://127.0.0.1:8003/api';
    }

    // use HostNames;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::select([
            'first_name',
            'email',
            'last_name',
            'phone_number',
            'photo',
            'role'
        ])->get();

        return new JsonResponse([
            'status' => true,
            'message' => 'Users retrieved successfully',
            'data' => $users
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'email' => 'required',
            'password' => 'required',
            'last_name' => 'required',
            'phone_number' => 'required',
            'photo' => 'required',
            'role' => 'required'
        ]);
        if ($validator->fails()) {
            return new JsonResponse([
                'status' => false,
                'message' => $validator->errors(),
            ], 500);
        }

        $photoName = Str::random() . '.' . $request->photo->getClientOriginalExtension();
        Storage::disk('public')->putFileAs('users/photo', $request->photo, $photoName);

        $request['password'] = Hash::make($request['password']);

        $user = User::create([
            'first_name' => $request['first_name'],
            'last_name' => $request['last_name'],
            'email' => $request['email'],
            'password' => $request['password'],
            'phone_number' => $request['phone_number'],
            'role' => $request['role'],
            'photo' => "users/photo/${photoName}"
        ]);

        Http::post( $this->order_MS()."/users/store",
            [
                'id' => $user->id,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name
            ]
        );

        return new JsonResponse([
            'status' => true,
            'message' => 'User created successfully',
            'data' => $user
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);

        if ($user == null) {
            return new JsonResponse([
                'status' => true,
                'message' => 'No user with id ' . $id,
            ], 404);
        }

        return new JsonResponse([
            'status' => true,
            'message' => 'User retrieved successfully',
            'data' => $user
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::find($id);

        $data = [];

        if ($user == null) {
            return new JsonResponse([
                'status' => true,
                'message' => 'No users with id ' . $id,
            ], 404);
        }

        if ($request->has('first_name')) {
            $user->first_name = $request['first_name'];
            $data['first_name'] = $request['first_name'];
        }
        if ($request->has('last_name')) {
            $user->last_name = $request['last_name'];
            $data['last_name'] = $request['last_name'];
        }
        if ($request->has('email')) {
            $user->email = $request['email'];
        }
        if ($request->has('password')) {
            $user->password = Hash::make($request['password']);
        }
        if ($request->has('phone_number')) {
            $user->phone_number = $request['phone_number'];
        }
        if ($request->has('photo')) {

            $exists = Storage::disk('public')->exists('users/photos/' . $user->photo);
            if ($exists) {
                $exists = Storage::disk('public')->delete('users/photos/' . $user->photo);
            }

            $photoName = Str::random() . '.' . $request->photo->getClientOriginalExtension();
            Storage::disk('public')->putFileAs('users/photo', $request->photo, $photoName);


            $user->photo = 'users/photo/$photoName';
        }
        if ($request->has('role')) {
            $user->role = $request['role'];
        }

        $user->save();

        $data['id'] = $user->id;
        Http::put($this->order_MS() .'/users/'. $user->id, $data);


        return new JsonResponse([
            'status' => true,
            'message' => 'User updated successfully',
            'data' => $user
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);

        if ($user == null) {
            return new JsonResponse([
                'status' => true,
                'message' => 'No users with id ' . $id,
            ], 404);
        }

        $user->delete();

        Http::delete($this->order_MS().'/users/' . $user->id);

        return new JsonResponse([
            'status' => true,
            'message' => 'User deleted successfully'
        ], 200);
    }

    public function getAuthenticatedUser()
    {
        return Auth::user();
    }
}
