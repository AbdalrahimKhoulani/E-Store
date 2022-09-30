<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;


class ProductController extends Controller
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
            'name' => 'required',
            'price' => 'required',
            'quantity' => 'required',
            'image' => 'required',
            'category_name' => 'required',
        ]);

        if ($validator->fails()) {
            return new JsonResponse([
                'status' => false,
                'message' => $validator->errors(),
            ], 500);
        }

        $product = Product::create($request->post()/* + ['image' => "storage/products/image/${imageName}"]*/);

        return new JsonResponse([
            'status' => true,
            'message' => 'Product created successfully',
            'data' => $product
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
        $product = Product::find($id);

        if ($product == null) {
            return new JsonResponse([
                'status' => false,
                'message' => 'No Product with id ' . $id
            ], 404);
        }

        if ($request->has('name')) {
            $product->name = $request['name'];
        }

        if ($request->has('price')) {
            $product->price = $request['price'];
        }

        if ($request->has('quantity')) {
            $product->quantity = $request['quantity'];
        }

        if ($request->has('category_name')) {
            $product->category_name = $request['category_name'];
        }

        if ($request->has('image')) {
            $product->image = $request['image'];
        }

        $product->save();

        return new JsonResponse([
            'status' => true,
            'message' => 'Product updated successfully',
            'data' => $product
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
        $product = Product::find($id);

        if($product==null){
            return new JsonResponse([
                'status' => false,
                'message' => 'No Product with id ' . $id
            ], 404);
        }

        $product->delete();

        return new JsonResponse([
            'status' => true,
            'message' => 'Product deleted successfully'],
            200);
    }
}
