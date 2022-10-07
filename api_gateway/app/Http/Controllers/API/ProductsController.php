<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Validator;

class ProductsController extends Controller
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
            ])->get($this->products_Ms() . '/products')->json();
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
            'name' => 'required',
            'description' => 'required',
            'price' => 'required',
            'quantity' => 'required',
            'image' => 'required|image',
            'category_id' => 'required'
        ]);

        if ($validator->fails()) {
            return new JsonResponse([
                'status' => false,
                'message' => $validator->errors()
            ], 500);
        }
        $imageName = Str::random() . '.' . $request->image->getClientOriginalExtension();

        return Http::withToken($request->bearerToken())
            ->attach(
                'image',
                file_get_contents($request['image']),
                $imageName,
                [
                    'Accept' => 'application/json',
                    'Content-Type' => 'multipart/form-data'
                ]
            )
            ->post($this->products_Ms() . '/products', $request->post())
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
        ])->get($this->products_Ms() . '/products/' . $id)
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
        $data = [];

        if ($request->has('name')) {
            $data['name'] =  $request['name'];
        }

        if ($request->has('description')) {
            $data['description'] =  $request['description'];
        }

        if ($request->has('price')) {
            $data['price'] =  $request['price'];
        }

        if ($request->has('quantity')) {
            $data['quantity'] =  $request['quantity'];
        }

        if ($request->has('category_id')) {
            $data['category_id'] =  $request['category_id'];
        }
        if ($request->image) {

            $imageName = Str::random() . '.' . $request->image->getClientOriginalExtension();

            return Http::withToken($request->bearerToken())
                ->attach(
                    'image',
                    file_get_contents($request['image']),
                    $imageName,
                    [
                        'Accept' => 'application/json',
                        'Content-Type' => 'multipart/form-data'
                    ]
                )
                ->put($this->products_Ms() . '/products/' . $id, $data)
                ->json();
        } else {
            error_log('1');

            return Http::withToken($request->bearerToken())->withHeaders([
                'Accept' => 'application/json'
            ])
                ->put(
                    $this->products_Ms() . '/products/' . $id,
                    $data
                )->json();
        }
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
            ])->delete($this->products_Ms() . '/products/' . $id)
            ->json();
    }
}
