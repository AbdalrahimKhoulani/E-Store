<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $categories = Category::select('id', 'name')->get();

        return new JsonResponse([
            'status' => true,
            'message' => 'Categories retrieved successfully',
            'data' => $categories
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
            'name' => 'required'
        ]);
        $category = Category::create($request->all());

        return new JsonResponse([
            'status' => true,
            'message' => 'New category added successfully',
            'data' => $category
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
        $category = Category::find($id);
        if ($category == null) {
            return new JsonResponse([
                'status' => false,
                'message' => 'No category with id ' . $id
            ], 404);
        }

        return new JsonResponse([
            'status' => true,
            'message' => 'Category retrieved successfully',
            'data' => $category
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
        $request->validate([
            'name' => 'required'
        ]);

        $category = Category::find($id);

        if ($category == null) {
            return new JsonResponse([
                'status' => false,
                'message' => 'No category with id ' . $id
            ], 404);
        }

        if ($request->has('name')) {
            $category->name = $request->name;
        }
        $category->save();

        return new JsonResponse([
            'status' => true,
            'message' => 'New category added successfully',
            'data' => $category
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
        $category = Category::find($id);

        if ($category == null) {
            return new JsonResponse([
                'status' => false,
                'message' => 'No category with id ' . $id
            ], 404);
        }
        $category->delete();

          return new JsonResponse([
            'status' => true,
            'message' => 'Category deleted successfully'
        ], 200);
    }
}
