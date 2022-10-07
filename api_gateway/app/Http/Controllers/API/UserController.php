<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Exception;
use Http;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class UserController extends Controller
{


    public function users_Ms()
    {
        return "http://127.0.0.1:8001/api";
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return Http::withToken($request->bearerToken())
            ->withHeaders([
                'Accept' => 'application/json'
            ])->get($this->users_Ms() . "/users/index")->json();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required',
            'email' => 'required',
            'password' => 'required',
            'last_name' => 'required',
            'phone_number' => 'required',
            'photo' => 'required',
            'role' => 'required'
        ]);
        try {

            $photoName = Str::random() . '.' . $request->photo->getClientOriginalExtension();

            $response =  Http::attach(
                'photo',
                file_get_contents($request->photo),
                $photoName,
                [
                    'Accept' => 'application/json',
                    'Content-Type' => 'multipart/form-data'
                ]
            )
                ->post($this->users_Ms() . '/register', [
                    'first_name' => $request['first_name'],
                    'last_name' => $request['last_name'],
                    'email' => $request['email'],
                    'password' => $request['password'],
                    'phone_number' => $request['phone_number'],
                    // 'photo' => $request->file('photo','abc'),
                    'role' => $request['role']
                ])->json();
            return $response;
        } catch (Exception $e) {
            return new JsonResponse([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        return Http::withToken($request->bearerToken())
            ->withHeaders([
                'Accept' => 'application/json'
            ])->get($this->users_Ms() . "/users/${id}/show")->json();
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
        $data = [];
        error_log(__FUNCTION__);

        if ($request->has('first_name')) {
            $data['first_name'] = $request['first_name'];
        }
        if ($request->has('last_name')) {
            $data['last_name'] = $request['last_name'];
        }
        if ($request->has('email')) {
            $data['email'] = $request['email'];
        }
        if ($request->has('password')) {
            $data['password'] = $request['password'];
        }
        if ($request->has('phone_number')) {
            $data['phone_number'] = $request['phone_number'];
        }
        if ($request->has('role')) {
            $data['role'] = $request['role'];
        }

        $data['id'] = $id;

        if ($request->has('photo')) {
            $photoName = Str::random() . '.' . $request->photo->getClientOriginalExtension();

            $response = Http::withToken($request->bearerToken())->withHeaders([
                'Accept' => 'application/json'
            ])->attach(
                'photo',
                file_get_contents($request->photo),
                $photoName,
                [
                    'Accept' => 'application/json',
                    'Content-Type' => 'multipart/form-data'
                ]
            )->put($this->users_Ms() . '/users/' . $id . '/update', $data);
            return $response;
        } else {
            $response = Http::withToken($request->bearerToken())
                ->withHeaders([
                    'Accept' => 'application/json'
                ])
                ->put($this->users_Ms() . '/users/' . $id . '/update', $data)
                ->json();
        }
        return $response;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        return Http::withToken($request->bearerToken())
            ->withHeaders([
                'Accept' => 'application/json'
            ])
            ->delete($this->users_Ms() . "/users/${id}/delete")->json();
    }
}
