<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public $path = "images/employees/";
    public function index()
    {
        $employees = Employee::with('category', 'director')->latest()->paginate(5);

        if(is_null($employees)){
            return response()->json([
                "success" => false,
                "message" => "Employee not found",
            ], 404);
        }

        return response()->json([
            "success" => true,
            "message" => "Employee data successfully retrieved",
            "data" => [
                "data" => $employees->items(),
                "current_page" => $employees->currentPage(),
                "last_page" => $employees->lastPage(),
                "total" => $employees->perPage(),
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

        $employee = Employee::create($data);

        return response()->json([
            "success" => true,
            "message" => "Employee successfully created",
            "data" => $employee
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $employee = Employee::find($id);

        if(is_null($employee)){
            return response()->json([
                "success" => false,
                "message" => "Employee not found",
            ], 404);
        }

        return response()->json([
            "success" => true,
            "message" => "Employee data successfully retrieved",
            "data" => $employee
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Employee $employee)
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

        $employee = Employee::find($id);

        if($image = $request->file("photo"))
        {
            $image_data = $this->uploadImage("update", $image, $this->path, $employee->photo);
        }

        $data = $request->except(["photo"]);
        $data["photo"] = $image_data["path"];

        $employee->update($data);

        return response()->json([
            "success" => true,
            "message" => "Employee successfully updated",
            "data" => Employee::find($id)
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $employee = Employee::find($id);
        $employee->delete();

        return response()->json([
            "success" => true,
            "message" => "Employee successfully deleted",
        ], 200);
    }

    public function search($query)
    {
        $employees = Employee::where('name', 'like', '%'.$query.'%')->latest()->paginate(5);

        return response()->json([
            "success" => true,
            "message" => "Employee data successfully retrieved",
            "data" => [
                "data" => $employees->items(),
                "current_page" => $employees->currentPage(),
                "last_page" => $employees->lastPage(),
                "total" => $employees->perPage(),
            ]
        ], 200);
    }
}
