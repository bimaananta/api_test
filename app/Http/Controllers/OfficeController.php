<?php

namespace App\Http\Controllers;

use App\Models\Office;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OfficeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $office = Office::with('Office', 'director')->latest()->paginate(5);

        if(is_null($office)){
            return response()->json([
                "success" => false,
                "message" => "Office not found",
            ], 404);
        }

        return response()->json([
            "success" => true,
            "message" => "Office data successfully retrieved",
            "data" => [
                "data" => $office->items(),
                "current_page" => $office->currentPage(),
                "last_page" => $office->lastPage(),
                "total" => $office->perPage(),
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
            "head_officer" => "required|string|min:3",
            "address" => "required|string|min:3",
        ]);

        if($validation->fails()){
            return $this->createResponseValidate($validation->errors());
        }

        $office = Office::create($request->all());

        return response()->json([
            "success" => true,
            "message" => "Office successfully created",
            "data" => $office
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $office = Office::find($id);

        if(is_null($office)){
            return response()->json([
                "success" => false,
                "message" => "Office not found",
            ], 404);
        }

        return response()->json([
            "success" => true,
            "message" => "Office data successfully retrieved",
            "data" => $office
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Office $office)
    {

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validation = Validator::make($request->all(), [
            "head_officer" => "required|string|min:3",
            "address" => "required|string|min:3",
        ]);

        if($validation->fails()){
            return $this->createResponseValidate($validation->errors());
        }

        $office = Office::find($id);

        $office->update($request->all());

        return response()->json([
            "success" => true,
            "message" => "Office successfully updated",
            "data" => Office::find($id)
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $office = Office::find($id);
        $office->delete();

        return response()->json([
            "success" => true,
            "message" => "Office successfully deleted",
        ], 200);
    }
}
