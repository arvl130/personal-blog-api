<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $fields = $request->validate([
            "name" => "required|string",
            "email" => "required|string|unique:users,email",
            "password" => "required|string"
        ]);

        User::create([
            "name" => $fields["name"],
            "email" => $fields["email"],
            "password" => bcrypt($fields["password"])
        ]);

        return [
            "message" => "User created."
        ];
    }

    public function login(Request $request)
    {
        $fields = $request->validate([
            "email" => "required|string",
            "password" => "required|string"
        ]);

        $user = User::where("email", $fields["email"])->first();

        if (!$user || !Hash::check($fields["password"], $user->password)) {
            return response([
                "message" => "Unauthorized",

            ], 401);
        }

        $token = $user->createToken("myapitoken")->plainTextToken;

        return response([
            "message" => "Logged in.",
            "token" => $token
        ], 201);
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();

        return [
            "message" => "Logged out."
        ];
    }
}
