<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $fields = $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email|unique:users',
            'phone_number' => 'required|unique:users',
            'password' => 'required|string|confirmed'
        ]);

        $user = User::create([
            'first_name' => $fields['first_name'],
            'last_name' => $fields['last_name'],
            'phone_number' => $fields['phone_number'],
            'email' => $fields['email'],
            'password' => bcrypt($fields['password'])
        ]);

        $token = $user->createToken('apptoken')->plainTextToken;

        $response = ['user' => $user, 'token' => $token];
        return response($response, Response::HTTP_CREATED);
    }

    public function login(Request $request)
    {
        $fields = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

//        Check email
        $user = User::where('email', $fields['email'])->first();

//        Check password
        if (!$user || !Hash::check($fields['password'], $user->password)) {
            return \response(['message' => 'invalid credentials'], Response::HTTP_UNAUTHORIZED);
        }

        $token = $user->createToken('apptoken')->plainTextToken;

        $response = ['user' => $user, 'token' => $token];
        return response($response, 200);
    }

    public function logout(Request $request): Response
    {
        auth()->user()->tokens()->delete();
        return response(['message' => 'Logged out!']);
    }
}
