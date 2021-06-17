<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ControllerHelper extends Controller
{
    //
    public static function generateResponse($status, $message, $code)
    {
        return response()->json([
            'status' => $status,
            'message' => $message,
        ], $code);
    }

    public static function generateResponsedata($status, $message, $data)
    {
        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data
        ]);
    }
}
