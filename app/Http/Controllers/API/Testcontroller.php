<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Testcontroller extends Controller
{
    public function index()
    {
        return response()->json(['message' => 'API/Testcontroller is working!']);
    }
}   
