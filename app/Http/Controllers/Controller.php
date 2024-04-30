<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function uploadImage($type, $image, $path, $old_path)
    {
        if($type == "update")
        {
            Storage::disk('public')->delete($old_path);
        }

        $file_name = time() . $image->getClientOriginalName();
        $file_path = $path.$file_name;

        Storage::disk('public')->put($file_path, File::get($image));

        return [
            "path" => $file_path,
            "file_name" => $file_name
        ];
    }

    public function createResponseValidate($errors)
    {
        return response()->json([
            "success" => false,
            "message" => "Invalid field",
            "errors" => $errors,
        ], 422);
    }
}
