<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $transactions = Transaction::with('Transaction', 'director')->latest()->paginate(5);

        if(is_null($transactions)){
            return response()->json([
                "success" => false,
                "message" => "Transaction not found",
            ], 404);
        }

        return response()->json([
            "success" => true,
            "message" => "Transaction data successfully retrieved",
            "data" => [
                "data" => $transactions->items(),
                "current_page" => $transactions->currentPage(),
                "last_page" => $transactions->lastPage(),
                "total" => $transactions->perPage(),
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
            "Transaction_id" => "required|exists:Transactions,id",
            "product_id" => "required|exists:products,id",
            "transaction_date" => "required|date",
            "method" => "required|in:cash,credit",
            "note" => "required|text",
            "quantity" => "required|numeric",
            "price_total" => "required|numeric",
        ]);

        if($validation->fails()){
            return $this->createResponseValidate($validation->errors());
        }

        $transaction = Transaction::create($request->all());

        return response()->json([
            "success" => true,
            "message" => "Transaction successfully created",
            "data" => $transaction
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $transaction = Transaction::find($id);

        if(is_null($transaction)){
            return response()->json([
                "success" => false,
                "message" => "Transaction not found",
            ], 404);
        }

        return response()->json([
            "success" => true,
            "message" => "Transaction data successfully retrieved",
            "data" => $transaction
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaction $transaction)
    {

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validation = Validator::make($request->all(), [
            "Transaction_id" => "required|exists:Transactions,id",
            "product_id" => "required|exists:products,id",
            "transaction_date" => "required|date",
            "method" => "required|in:cash,credit",
            "note" => "required|text",
            "quantity" => "required|numeric",
            "price_total" => "required|numeric",
        ]);

        if($validation->fails()){
            return $this->createResponseValidate($validation->errors());
        }

        $transaction = Transaction::find($id);

        $transaction->update($request->all());

        return response()->json([
            "success" => true,
            "message" => "Transaction successfully updated",
            "data" => Transaction::find($id)
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $transaction = Transaction::find($id);
        $transaction->delete();

        return response()->json([
            "success" => true,
            "message" => "Transaction successfully deleted",
        ], 200);
    }

    public function search($query)
    {
        $transactions = Transaction::where('name', 'like', '%'.$query.'%')->latest()->paginate(5);

        return response()->json([
            "success" => true,
            "message" => "Transaction data successfully retrieved",
            "data" => [
                "data" => $transactions->items(),
                "current_page" => $transactions->currentPage(),
                "last_page" => $transactions->lastPage(),
                "total" => $transactions->perPage(),
            ]
        ], 200);
    }
}
