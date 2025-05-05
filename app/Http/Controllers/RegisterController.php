<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|string|unique:users', // Changed from 'supabase_id' to 'id'
            'email' => 'required|email|unique:users',
            'name' => 'required|string|max:255',
            'provider' => 'nullable|string', // Optional: Add if tracking auth provider
            'metadata' => 'nullable|json' // Optional: For Supabase user_metadata
        ]);

        $user = User::create([
            'id' => $validated['id'], // Primary key (Supabase UUID)
            'email' => $validated['email'],
            'name' => $validated['name'],
            'provider' => $validated['provider'] ?? 'email', // Default to 'email'
            'metadata' => $validated['metadata'] ?? json_encode(['source' => 'supabase']),
            'email_verified_at' => $request->input('email_verified') ? now() : null
        ]);

        return response()->json([
            'status' => 'success',
            'user' => $user->makeHidden(['metadata']) // Optional: Hide sensitive data
        ]);
    }
}
