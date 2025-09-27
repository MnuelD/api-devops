<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HelloWorldController extends Controller
{
    //
    public function index(): JsonResponse
    {
        return response()->json([
            'message' => 'Olá Mundo!'
        ]);

    }
}
