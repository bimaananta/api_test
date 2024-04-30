<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validation = Validator::make($request->all(), [
            "username" => "required|string|min:3",
            "email" => "required|email|min:3",
            "password" => "required|string|min:8",
        ]);

        if($validation->fails()){
            return $this->createResponseValidate($validation->errors());
        }

        $input = $request->except(["password"]);
        $input["password"] = Hash::make($request->password);

        $user = User::create($input);

        $data = $user;
        $data["token"] = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            "success" => true,
            "message" => "Register success",
            "data" => $data,
        ], 200);

    }

    public function login(Request $request)
    {
        $validation = Validator::make($request->all(), [
            "username" => "required|string|min:3",
            "password" => "required|string|min:3",
        ]);

        if($validation->fails()){
            return $this->createResponseValidate($validation->errors());
        }

        $user = User::where("username", $request->username)->first();

        if(!$user || !Hash::check($request->password, $user->password)){
            return response()->json([
                "success" => false,
                "message" => "Invalid Login",
            ], 403);
        }

        $data = $user;
        $data["token"] = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            "success" => true,
            "message" => "Login success",
            "data" => $data,
        ], 200);
    }

    public function logout(Request $request)
    {
        $user = Auth::user();

        $user->tokens()->delete();

        return response()->json([
            "success" => true,
            "message" => "Log out success",
        ], 200);
    }
}
