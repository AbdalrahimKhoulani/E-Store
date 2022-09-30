<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Http;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Storage;
use App\Traits\HostNames;
use Log;

class ProductController extends Controller
{

    use HostNames;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::select('id', 'name', 'description', 'price', 'quantity', 'image', 'category_id')
            ->get();

        return new JsonResponse([
            'status' => true,
            'message' => 'Products retrieved successfully',
            'data' => $products
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
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'price' => 'required',
            'quantity' => 'required',
            'image' => 'required|image',
            'category_id' => 'required'
        ]);


        $imageName = Str::random() . '.' . $request->image->getClientOriginalExtension();
        Storage::disk('public')->putFileAs('products/image', $request->image, $imageName);

        $product = Product::create($request->post() + ['image' => "products/image/${imageName}"]);

        Http::
            // attach('image', file_get_contents($request['image']))->
            post(
                $this->getOrders_MS(),
                [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price,
                    'quantity' => $product->quantity,
                    'image' => $request->getHttpHost() . '/storage/products/image/' . $imageName,
                    'category_name' => $product->category->name,
                ]
            )->json();

        //TODO: PIPLINE

        return new JsonResponse([
            'status' => true,
            'message' => 'Product created successfully',
            'data' => $product
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
        $product = Product::find($id);

        if ($product == null) {
            return new JsonResponse([
                'status' => false,
                'message' => 'No Product with id ' . $id
            ], 404);
        }
        return new JsonResponse([
            'status' => true,
            'message' => 'Product retrieved successfully',
            'data' => $product
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
        $product = Product::find($id);
        $data = [];

        if ($product == null) {
            return new JsonResponse([
                'status' => false,
                'message' => 'No Product with id ' . $id
            ], 404);
        }


        if ($request->has('name')) {
            $product->name = $request['name'];
            $data['name'] =  $request['name'];
        }

        if ($request->has('description')) {
            $product->description = $request['description'];
            // $data['description'] =  $request['description'];
        }

        if ($request->has('price')) {
            $product->price = $request['price'];
            $data['price'] =  $request['price'];
        }

        if ($request->has('quantity')) {
            $product->quantity = $request['quantity'];
            $data['quantity'] =  $request['quantity'];
        }

        if ($request->has('category_id')) {
            $product->category_id = $request['category_id'];
            $data['category_name'] =  $product->category->name;
        }
        error_log('1');
        if ($request->image) {
            error_log('2');
            $exists =   Storage::disk('public')->exists('products/image/' . ($product->image));
            if ($exists) {
                Storage::disk('public')->delete('products/image/' . ($product->image));
            }
            $imageName = Str::random() . '.' . $request->image->getClientOriginalExtension();
            Storage::disk('public')->putFileAs('products/image', $request->image, $imageName);
            $product->image = "products/image/${imageName}";

            $data['name'] =  "storage/products/image/${imageName}";
        }
        $product->save();


        Http::put(
            $this->getOrders_MS(),
            $data
        )->json();


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
        if ($product == null) {
            return new JsonResponse([
                'status' => false,
                'message' => 'No Product with id ' . $id
            ], 404);
        }

        $product->delete();

        Http::delete($this->getOrders_MS()."/${id}");
        return new JsonResponse([
            'status' => true,
            'message' => 'Product deleted successfully'
        ], 200);
    }
}
