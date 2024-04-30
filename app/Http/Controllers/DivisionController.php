<?php

namespace App\Http\Controllers;

use App\Models\Division;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DivisionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $division = Division::with('Division', 'director')->latest()->paginate(5);

        if(is_null($division)){
            return response()->json([
                "success" => false,
                "message" => "Division not found",
            ], 404);
        }

        return response()->json([
            "success" => true,
            "message" => "Division data successfully retrieved",
            "data" => [
                "data" => $division->items(),
                "current_page" => $division->currentPage(),
                "last_page" => $division->lastPage(),
                "total" => $division->perPage(),
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

        $division = Division::create($request->all());

        return response()->json([
            "success" => true,
            "message" => "Division successfully created",
            "data" => $division
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $division = Division::find($id);

        if(is_null($division)){
            return response()->json([
                "success" => false,
                "message" => "Division not found",
            ], 404);
        }

        return response()->json([
            "success" => true,
            "message" => "Division data successfully retrieved",
            "data" => $division
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Division $division)
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

        $division = Division::find($id);

        $division->update($request->all());

        return response()->json([
            "success" => true,
            "message" => "Division successfully updated",
            "data" => Division::find($id)
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $division = Division::find($id);
        $division->delete();

        return response()->json([
            "success" => true,
            "message" => "Division successfully deleted",
        ], 200);
    }

    public function search($query)
    {
        $divisions = Division::where('name', 'like', '%'.$query.'%')->latest()->paginate(5);

        return response()->json([
            "success" => true,
            "message" => "Division data successfully retrieved",
            "data" => [
                "data" => $divisions->items(),
                "current_page" => $divisions->currentPage(),
                "last_page" => $divisions->lastPage(),
                "total" => $divisions->perPage(),
            ]
        ], 200);
    }
}
