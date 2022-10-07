<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class CategoriesController extends Controller
{

    public function products_Ms()
    {
        return "http://127.0.0.1:8002/api";
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
                'Acccept' => 'application/json'
            ])->get($this->products_Ms() . '/categories')->json();
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
            'name' => 'required'
        ]);

        if ($validator->fails()) {
            return new JsonResponse([
                'status' => false,
                'message' => $validator->errors()
            ], 500);
        }

        return Http::withToken($request->bearerToken())
            ->withHeaders(['Accept' => 'application/json'])
            ->post($this->products_Ms() . '/categories', [
                'name' => $request['name']
            ])
            ->json();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        return Http::withToken($request->bearerToken())->withHeaders([
            'Accept' => 'application/json'
        ])->get($this->products_Ms() . '/categories/' . $id)
            ->json();
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
        $validator = Validator::make($request->all(), [
            'name' => 'required'
        ]);

        if ($validator->fails()) {
            return new JsonResponse([
                'status' => false,
                'message' => $validator->errors()
            ], 500);
        }

        return Http::withToken($request->bearerToken())
            ->withHeaders(['Accept' => 'application/json'])
            ->put($this->products_Ms() . '/categories/' . $id, [
                'name' => $request['name']
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
        return Http::withToken($request->bearerToken())
            ->withHeaders([
                'Accept' => 'application/json'
            ])->delete($this->products_Ms() . '/categories/' . $id)
            ->json();
    }
}
