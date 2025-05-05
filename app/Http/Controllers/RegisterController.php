<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'id' => 'required|string|unique:users,id',
            'email' => 'required|email|unique:users,email',
            'name' => 'required|string',
            'profile' => 'nullable|image|max:2048',
            'verified_at' => 'date'
        ]);

        $path = null;

        if ($request->hasFile('profile')) {
            $path = $request->file('profile')->store("profiles/{$request->id}", 'public');
        }    

        $user = User::create([
            'id' => $request['id'], // Primary key (Supabase UUID)
            'email' => $request['email'],
            'name' => $request['name'],
            'profile_photo_url' => $path ? Storage::url($path) : null, 
            'verified_at' => $request->verified_at
        ]);

        return response()->json(['message' => 'User registered in MySQL']);
    }

    public function checkUserExist(Request $request){
        $userId = $request->query('id');

        $user = User::find($userId);

        if($user){
            return response()->json(['exists' => true, 'user' => $user]);
        }else{
            return response()->json(['exists' => false]);
        }
    }
}
