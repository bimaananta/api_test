<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $category = Category::latest()->paginate(5);

        if(is_null($category)){
            return response()->json([
                "success" => false,
                "message" => "Category not found",
            ], 404);
        }

        return response()->json([
            "success" => true,
            "message" => "Category data successfully retrieved",
            "data" => [
                "data" => $category->items(),
                "current_page" => $category->currentPage(),
                "last_page" => $category->lastPage(),
                "total" => $category->perPage(),
            ]
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            "name" => "required|string|min:3",
        ]);

        if($validation->fails()){
            return $this->createResponseValidate($validation->errors());
        }

        $category = Category::create($request->all());

        return response()->json([
            "success" => true,
            "message" => "Category successfully created",
            "data" => $category
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $category = Category::find($id);

        if(is_null($category)){
            return response()->json([
                "success" => false,
                "message" => "Category not found",
            ], 404);
        }

        return response()->json([
            "success" => true,
            "message" => "Category data successfully retrieved",
            "data" => $category
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validation = Validator::make($request->all(), [
            "name" => "required|string|min:3",
        ]);

        if($validation->fails()){
            return $this->createResponseValidate($validation->errors());
        }

        $category = Category::find($id);

        $category->update($request->all());

        return response()->json([
            "success" => true,
            "message" => "Category successfully updated",
            "data" => Category::find($id)
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $category = Category::find($id);
        $category->delete();

        return response()->json([
            "success" => true,
            "message" => "Category successfully deleted",
        ], 200);
    }

    public function search($query)
    {
        $categories = Category::where('name', 'like', '%'.$query.'%')->latest()->paginate(5);

        return response()->json([
            "success" => true,
            "message" => "Category data successfully retrieved",
            "data" => [
                "data" => $categories->items(),
                "current_page" => $categories->currentPage(),
                "last_page" => $categories->lastPage(),
                "total" => $categories->perPage(),
            ]
        ], 200);
    }
}
