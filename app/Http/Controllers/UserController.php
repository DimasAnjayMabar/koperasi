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
            'email' => $user -> email,
            'username' => $user -> username, 
            'profile' => $user -> profile,
            'phone' => $user -> phone
        ]);
    }

    public function getEmail(Request $request)
    {
        $username = $request -> input('username');

        $user = User::where('username', $username) -> first();

        if (!$user){
            return response()->json(['error' => 'User not found'], 404);
        }

        return response() -> json([
            'email' => $user -> email,
            'role' => $user -> role
        ]);
    }
}
