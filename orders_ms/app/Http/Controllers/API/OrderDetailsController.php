<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\OrderDetails;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderDetailsController extends Controller
{


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required',
            'product_id' => 'required',
            'amount' => 'required',
        ]);

        $orderDetails = OrderDetails::create($request->all());

        return new JsonResponse([
            'status' => true,
            'message' => 'Product added to order successfully',
            'data' => $orderDetails
        ], 201);
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
        $orderDetails = OrderDetails::find($id);

        if ($orderDetails == null) {
            return new JsonResponse([
                'status' => false,
                'message' => 'Not found product in this order'
            ], 404);
        }

        if ($request->has('product_id')) {
            $orderDetails->product_id = $request['product_id'];
        }

        if ($request->has('amount')) {
            $orderDetails->amount = $request['amount'];
        }

        $orderDetails->save();


        return new JsonResponse([
            'status' => true,
            'message' => 'Order updated successfully',
            'data' => $orderDetails
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
        $orderDetails = OrderDetails::find($id);

        if ($orderDetails == null) {
            return new JsonResponse([
                'status' => false,
                'message' => 'Not found product in this order'
            ], 404);
        }

        $orderDetails->delete();

        return new JsonResponse([
            'status'=>true,
            'message'=>'Product deleted from order'
        ],201);
    }
}
