<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */

     public $path = "images/products/";
    public function index()
    {
        $products = Product::with('category')->latest()->paginate(5);

        if(is_null($products)){
            return response()->json([
                "success" => false,
                "message" => "Product not found",
            ], 404);
        }

        return response()->json([
            "success" => true,
            "message" => "Product data successfully retrieved",
            "data" => [
                "data" => $products->items(),
                "current_page" => $products->currentPage(),
                "last_page" => $products->lastPage(),
                "total" => $products->perPage(),
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
            "title" => "required|string|min:3",
            "description" => "required|string",
            "photo" => "required|image|mimes:png,jpg,jpeg|max:2048",
            "release_date" => "required|date|date_format:Y-m-d",
            "price" => "nullable|numeric",
            "available" => "required|boolean",
        ]);

        if($validation->fails()){
            return $this->createResponseValidate($validation->errors());
        }

        if($image = $request->file("photo"))
        {
            $image_data = $this->uploadImage("add", $image, $this->path, "images/");
        }

        $data = $request->except(["photo"]);
        $data["photo"] = $image_data["path"];

        $product = Product::create($data);

        return response()->json([
            "success" => true,
            "message" => "Product successfully created",
            "data" => $product
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $product = Product::find($id);

        if(is_null($product)){
            return response()->json([
                "success" => false,
                "message" => "Product not found",
            ], 404);
        }

        return response()->json([
            "success" => true,
            "message" => "Product data successfully retrieved",
            "data" => $product
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validation = Validator::make($request->all(), [
            "title" => "required|string|min:3",
            "description" => "required|string",
            "photo" => "required|image|mimes:png,jpg,jpeg|max:2048",
            "release_date" => "required|date|date_format:Y-m-d",
            "price" => "required|numeric",
            "available" => "required|boolean",
        ]);

        if($validation->fails()){
            return $this->createResponseValidate($validation->errors());
        }

        $product = Product::find($id);

        if($image = $request->file("photo"))
        {
            $image_data = $this->uploadImage("update", $image, $this->path, $product->photo);
        }

        $data = $request->except(["photo"]);
        $data["photo"] = $image_data["path"];

        $product->update($data);

        return response()->json([
            "success" => true,
            "message" => "Product successfully updated",
            "data" => Product::find($id)
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $product = Product::find($id);
        $product->delete();

        return response()->json([
            "success" => true,
            "message" => "Product successfully deleted",
        ], 200);
    }

    public function search($query)
    {
        $products = Product::where('name', 'like', '%'.$query.'%')->latest()->paginate(5);

        return response()->json([
            "success" => true,
            "message" => "Product data successfully retrieved",
            "data" => [
                "data" => $products->items(),
                "current_page" => $products->currentPage(),
                "last_page" => $products->lastPage(),
                "total" => $products->perPage(),
            ]
        ], 200);
    }
}
