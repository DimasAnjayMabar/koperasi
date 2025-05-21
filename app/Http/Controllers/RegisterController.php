<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        // ✅ Validate request
        $request->validate([
            'id' => 'required|string|unique:users,id',
            'email' => 'required|email|unique:users,email',
            'name' => 'required|string',
            'username' => 'required|string',
            'phone' => 'string',
            'profile' => 'nullable|image|max:2048',
            'role' => 'string'
        ]);

        // ✅ Handle optional profile upload
        $path = null;
        if ($request->hasFile('profile')) {
            $path = $request->file('profile')->store("profiles/{$request->id}", 'public');
        }

        // ✅ Save user
        $user = User::create([
            'id' => $request->input('id'),
            'email' => $request->input('email'),
            'username' => $request->input('username'),
            'name' => $request -> input('name'),
            'phone' => $request->input('phone'),
            'profile' => $path ? Storage::url($path) : null,
            'role' => $request->input('role')
        ]);

        // ✅ Debug log (for development)
        Log::info('New user registration', $request->except('profile')); // Avoid logging files
        // dd($request->all()); // Only for debugging — remove for production

        return response()->json(['message' => 'User registered in MySQL']);
    }

    public function checkUserExist(Request $request){
        User::where('id', $request->id)->update([
            'email_verified_at' => now()
        ]);

        return response()->json(['status' => 'success']);
    }
}
