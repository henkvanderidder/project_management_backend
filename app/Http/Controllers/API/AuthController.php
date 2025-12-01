<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    // Register a new user
    public function register(Request $request)
    {
        //
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:8'    //confirmed'
        ]); 

        if ($validator->fails()) {
            return response()->json(
                ['message' => 'Validation error',
                 'errors' => $validator->errors()], 400);
        }   

        $user = User::create($request->all());

        $token = $user->createToken('auth_token')->plainTextToken;
        // $token = $user->createToken('auth_token', ['server:update'])->plainTextToken; //scopes example   

        return response()->json(
            ['message' => 'User registered successfully',
             'user' => $user,
             'token' => $token
            ]
            , 201);
    }   

    // Login user
    public function login(Request $request)
    {

       //
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string'    //confirmed'
        ]); 

        if ($validator->fails()) {
            return response()->json(
                ['message' => 'Validation error',
                 'errors' => $validator->errors()], 400);
        }   

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            // use structure of error message of standard Resource output
            return response()->json(
                ['message' => 'Invalid login credentials',
                 'errors' => [
                    'email' => ['The provided credentials are incorrect.']
                  ] 
            ], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(
            ['message' => 'User logged in successfully',
             'user' => $user,
             'token' => $token
            ]
            , 200); 
    }     

    public function logout(Request $request) {
        // delete all tokens
        // $request->user()->tokens()->delete();

        // $user = Auth::user();
        // delete current token
        $request->user()->currentAccessToken()->delete();   
        return response()->json([ 
            'message' => 'Logged out successfully'
        ], 200);

    }
}