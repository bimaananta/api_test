<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $customers = Customer::latest()->paginate(5);

        if(is_null($customers)){
            return response()->json([
                "success" => false,
                "message" => "Customer not found",
            ], 404);
        }

        return response()->json([
            "success" => true,
            "message" => "Customer data successfully retrieved",
            "data" => [
                "data" => $customers->items(),
                "current_page" => $customers->currentPage(),
                "last_page" => $customers->lastPage(),
                "total" => $customers->perPage(),
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
            "gender" => "required|in:male,female",
            "address" => "required|string|min:3",
        ]);

        if($validation->fails()){
            return $this->createResponseValidate($validation->errors());
        }

        $customer = Customer::create($request->all());

        return response()->json([
            "success" => true,
            "message" => "Customer successfully created",
            "data" => $customer
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $customer = Customer::find($id);

        if(is_null($customer)){
            return response()->json([
                "success" => false,
                "message" => "Customer not found",
            ], 404);
        }

        return response()->json([
            "success" => true,
            "message" => "Customer data successfully retrieved",
            "data" => $customer
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer)
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
            "gender" => "required|in:male,female",
            "address" => "required|string|min:3",
        ]);

        if($validation->fails()){
            return $this->createResponseValidate($validation->errors());
        }

        $customer = Customer::find($id);

        $customer->update($request->all());

        return response()->json([
            "success" => true,
            "message" => "Customer successfully updated",
            "data" => Customer::find($id)
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $customer = Customer::find($id);
        $customer->delete();

        return response()->json([
            "success" => true,
            "message" => "Customer successfully deleted",
        ], 200);
    }

    public function search($query)
    {
        $customers = Customer::where('name', 'like', '%'.$query.'%')->latest()->paginate(5);

        return response()->json([
            "success" => true,
            "message" => "Customer data successfully retrieved",
            "data" => [
                "data" => $customers->items(),
                "current_page" => $customers->currentPage(),
                "last_page" => $customers->lastPage(),
                "total" => $customers->perPage(),
            ]
        ], 200);
    }
}
