<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request) {
        $params = $request->validate([
            'name' => ['required', 'string', 'min:4'],
            'role' => ['required'],
            'email' => ['required', 'string', 'unique:users,email'],
            'password' => ['required', 'min:5', 'string', 'confirmed'],
        ]);

        $user = User::create([
            'fullname' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'code' => Response::HTTP_OK,
            'success' => true,
            'message' => "Registration Successful!",
            'data' => [
                'userName' => $user->fullname,
                'userEmail' => $user->email,
                'role' => $user->role,
            ],
        ]);
    }

    public function login(Request $request) {
        $request->validate([
            'email' => ['required'],
            'password' => ['required'],
        ]);

        $user_email = $request->email;
        $password = $request->password;

        $user = User::where('email', $user_email)->first();

        if(!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => "Invalid Credentials",
                'data' => NULL

            ]);
        }

        $user->tokens()->delete();

        $token = $user->createToken('login')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => "Login Successful",
            'data' => [
                'name' => $user->fullname,
                'role' => $user->role,
            ],
            'token' => $token,
        ]);
    }
}
