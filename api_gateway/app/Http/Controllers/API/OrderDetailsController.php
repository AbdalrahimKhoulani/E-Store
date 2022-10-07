<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Http;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Validator;

class OrderDetailsController extends Controller
{
    public function orders_Ms()
    {
        return "http://127.0.0.1:8003/api";
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
            'order_id' => 'required',
            'product_id' => 'required',
            'amount' => 'required',
        ]);

        if ($validator->fails()) {
            return new JsonResponse([
                'status' => true,
                'message' => $validator->errors()
            ], 500);
        }

        return Http::withToken($request->bearerToken())
            ->withHeaders([
                'Accept' => 'application/json'
            ])
            ->post($this->orders_Ms() . '/orders/add-to-order', [
                'order_id' => $request['order_id'],
                'product_id' =>  $request['product_id'],
                'amount' =>  $request['amount'],
            ])->json();
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
        if ($request->has('product_id')) {
            $data['product_id'] = $request['product_id'];
        }

        if ($request->has('amount')) {
            $data['amount'] = $request['amount'];
        }

        return Http::withToken($request->bearerToken())
        ->withHeaders([
            'Accept' => 'applcation/json'
        ])
        ->put($this->orders_Ms().'/orders/update-in-order/'.$id,$data)->json();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request  $request,$id)
    {
        return Http::withToken($request->bearerToken())
        ->withHeaders([
            'Accept' => 'applcation/json'
        ])
        ->delete($this->orders_Ms().'/orders/delete-from-order/'.$id)->json();
    }
}
