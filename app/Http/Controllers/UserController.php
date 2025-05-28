<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function getInfo(Request $request){
        $email = $request -> input('email');

        $user = User::where('email', $email) -> first();

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        return response() -> json([
            'id' => $user -> id,
            'email' => $user -> email,
            'username' => $user -> username, 
            'name' => $user -> name,
            'profile' => $user -> profile,
            'phone' => $user -> phone,
            'role' => $user -> role
        ]);
    }

    public function getEmail(Request $request)
    {
        $identifier = $request->input('identifier');

        if (!$identifier) {
            return response()->json(['error' => 'No identifier provided'], 400);
        }

        // Determine if input is an email
        $isEmail = filter_var($identifier, FILTER_VALIDATE_EMAIL);

        // Fetch user by email or username
        $user = $isEmail
            ? User::where('email', $identifier)->first()
            : User::where('username', $identifier)->first();

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        return response()->json([
            'email' => $user->email,
            'role' => $user->role
        ]);
    }
}
