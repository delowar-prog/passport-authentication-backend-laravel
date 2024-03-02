<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
class ApiController extends Controller
{
    // Register API (POST, formdata)
    public function register(Request $request){

        $validatedData = $request->validate([
            'name' => 'required|max:55',
            'email' => 'email|required|unique:users',
            'password' => 'required'
        ]);

        $validatedData['password'] = bcrypt($request->password);

        $user = User::create($validatedData);

        $accessToken = $user->createToken('authToken')->accessToken;

        return response(['user' => $user, 'access_token' => $accessToken]);
    }

// Login API (POST, formdata)
public function login(Request $request){

    // Data validation
    $request->validate([
        "email" => "required|email",
        "password" => "required"
    ]);

    // Auth Facade
    if(Auth::attempt([
        "email" => $request->email,
        "password" => $request->password
    ])){
        $user = Auth::user();
        $token = $user->createToken("myToken")->accessToken;
        return response(['user' => $user, 'access_token' => $token]);
    }

    return response([
        "status" => false,
        "message" => "Invalid credentials"
    ]);
}
 // Profile API (GET)
 public function profile(){

    $userdata = Auth::user();

    return response()->json([
        "status" => true,
        "message" => "Profile data",
        "data" => $userdata
    ]);
}
public function logout(Request $request)
{
    // get token value
    $token = $request->user()->token();

    // revoke this token value
    $token->revoke();

    return response()->json([
        "status" => true,
        "message" => "User logged out successfully"
    ]);
}
}
