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
            'name' => $user -> name, 
            'profile_photo_url' => $user -> profile_photo_url
        ]);
    }
}
