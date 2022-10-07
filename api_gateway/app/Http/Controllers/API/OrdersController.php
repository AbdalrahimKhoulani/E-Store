<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Http;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
    public function orders_Ms()
    {
        return "http://127.0.0.1:8003/api";
    }
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
            ])
            ->get($this->orders_Ms() . '/orders')->json();
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
            'user_id' => 'required',
            'date' => 'required',
            'products' => 'required|array'
        ]);

        $user = Http::withToken($request->bearerToken())
            ->withHeaders(['Accept' => 'application/json'])
            ->get($this->users_Ms() . '/users/get-authenticated-user')
            ->json();

        return Http::withToken($request->bearerToken())
            ->withHeaders([
                'Accept' => 'application/json'
            ])
            ->post($this->orders_Ms() . '/orders', [
                'user_id' => $user['id'],
                'date' => $request['date'],
                'products' => $request['products']
            ])->json();
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
            ])
            ->get($this->orders_Ms() . '/orders/' . $id)->json();
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

        $request->validate([
            'date' => 'required'
        ]);

        return Http::withToken($request->bearerToken())
            ->withHeaders(['Accept' => 'application/json'])
            ->put($this->orders_Ms() . '/orders/' . $id, [
                'date' => $request['date']
            ])
            ->json();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        return Http::withToken($request->bearerToken())->withHeaders([
            'Accept' => 'application/json'
        ])
            ->delete($this->orders_Ms() . '/orders/' . $id)->json();
    }
}
