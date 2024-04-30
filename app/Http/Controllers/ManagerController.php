<?php

namespace App\Http\Controllers;

use App\Models\Manager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ManagerController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public $path = "images/managers/";
    public function index()
    {
        $managers = Manager::with('category', 'director')->latest()->paginate(5);

        if(is_null($managers)){
            return response()->json([
                "success" => false,
                "message" => "Manager not found",
            ], 404);
        }

        return response()->json([
            "success" => true,
            "message" => "Manager data successfully retrieved",
            "data" => [
                "data" => $managers->items(),
                "current_page" => $managers->currentPage(),
                "last_page" => $managers->lastPage(),
                "total" => $managers->perPage(),
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
            "email" => "required|email|min:3",
            "telp" => "required|numeric",
            "no_ktp" => "required|numeric|min:16",
            "birth_date" => "required|date|date_format:Y-m-d",
            "gender" => "required|in:male,female",
            "address" => "required|string|min:3",
            "photo" => "required|image|mimes:jpg,jpeg,png|max:2048",
            "division_id" => "required|exists:divisions,division_id",
            "office_id" => "required|exists:offices,id",
        ]);

        if($validation->fails()){
            return $this->createResponseValidate($validation->errors());
        }

        if($image = $request->file("photo"))
        {
            $image_data = $this->uploadImage("add", $image, $this->path);
        }

        $data = $request->except(["photo"]);
        $data["photo"] = $image_data["path"];

        $manager = Manager::create($data);

        return response()->json([
            "success" => true,
            "message" => "Manager successfully created",
            "data" => $manager
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $manager = Manager::find($id);

        if(is_null($manager)){
            return response()->json([
                "success" => false,
                "message" => "Manager not found",
            ], 404);
        }

        return response()->json([
            "success" => true,
            "message" => "Manager data successfully retrieved",
            "data" => $manager
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Manager $manager)
    {

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validation = Validator::make($request->all(), [
            "name" => "required|string|min:3",
            "email" => "required|email|min:3",
            "telp" => "required|numeric",
            "no_ktp" => "required|numeric|min:16",
            "birth_date" => "required|date|date_format:Y-m-d",
            "gender" => "required|in:male,female",
            "address" => "required|string|min:3",
            "photo" => "required|image|mimes:jpg,jpeg,png|max:2048",
            "division_id" => "required|exists:divisions,division_id",
            "office_id" => "required|exists:offices,id",
        ]);

        if($validation->fails()){
            return $this->createResponseValidate($validation->errors());
        }

        $manager = Manager::find($id);

        if($image = $request->file("photo"))
        {
            $image_data = $this->uploadImage("update", $image, $this->path, $manager->photo);
        }

        $data = $request->except(["photo"]);
        $data["photo"] = $image_data["path"];

        $manager->update($data);

        return response()->json([
            "success" => true,
            "message" => "Manager successfully updated",
            "data" => Manager::find($id)
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $manager = Manager::find($id);
        $manager->delete();

        return response()->json([
            "success" => true,
            "message" => "Manager successfully deleted",
        ], 200);
    }

    public function search($query)
    {
        $managers = Manager::where('name', 'like', '%'.$query.'%')->latest()->paginate(5);

        return response()->json([
            "success" => true,
            "message" => "Manager data successfully retrieved",
            "data" => [
                "data" => $managers->items(),
                "current_page" => $managers->currentPage(),
                "last_page" => $managers->lastPage(),
                "total" => $managers->perPage(),
            ]
        ], 200);
    }
}
