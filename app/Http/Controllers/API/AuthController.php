<?php

namespace App\Http\Controllers\API;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash; 

class AuthController extends Controller
{

    public function signup(Request $request)//
    {
        $request->validate(
            [
                'name' => 'required',
                'email' => ['required', 'email', 'unique:users'],
                'password' => 'required',
            ],
            [
                'email.unique' => 'The email address is already taken.',
            ],
            $request->all()
        );

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::attempt([
            'email' => $request->email,
            'password' => $request->password,
        ]);
        
        $token = $request->user()->createToken('auth');

        return response()->json([
            'token' => $token->plainTextToken,
            'message' => "Your account has been registered"
        ], Response::HTTP_CREATED);
    }


    public function login(Request $request)//
    {
    $request->validate(
        [
            'email' => ['required', 'email'],
            'password' => 'required',
        ],
        $request->all()
    );

    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {
        $token = $request->user()->createToken('auth');

        return response()->json([
            'token' => $token->plainTextToken,
            'message' => "You are logged in"
        ], Response::HTTP_OK);
    }
    
    return response()->json([
        'message' => "Sorry your email is invalid"
    ], Response::HTTP_UNAUTHORIZED);
    }

    public function logout()//
    {
    
    Auth::user()->currentAccessToken()->delete();
    return response()->json([
        'message' => "You are logged out"
    ], Response::HTTP_OK);
    }
}
