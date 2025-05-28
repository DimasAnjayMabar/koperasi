<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class RegisterController extends Controller
{
    public function registerStaff(Request $request)
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

    public function verifyStaff(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:users,id',
            'email_verified_at' => 'nullable|date'
        ]);

        try {
            $user = User::findOrFail($request->id);
            $user->update($request->only('email_verified_at'));
            
            return response()->json([
                'success' => true,
                'message' => 'User updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update user'
            ], 500);
        }
    }
    
    public function registerMember(Request $request)
    {
        try {
            // 1. Configure Supabase
            $supabaseUrl = rtrim(env('SUPABASE_URL'), '/');
            $anonKey = env('SUPABASE_ANON_KEY');
            $serviceRoleKey = env('SUPABASE_SERVICE_ROLE_KEY');
            
            // 2. Create user WITHOUT immediate confirmation
            $response = Http::withHeaders([
                'apikey' => $anonKey,
                'Authorization' => 'Bearer ' . $serviceRoleKey,
                'Content-Type' => 'application/json',
            ])->post("{$supabaseUrl}/auth/v1/admin/users", [
                'email' => $request->email,
                'password' => $request->password,
                'user_metadata' => [
                    'username' => $request->username,
                    'name' => $request->name,
                    'phone' => $request->phone,
                    'role' => 'member'
                ]
                // Removed 'email_confirm' => true to enable confirmation flow
            ]);

            // Add detailed error logging
            if (!$response->successful()) {
                // This will appear in console if running as artisan command
                echo "\nSupabase Error:\n";
                print_r($response->json());
                echo "\nRequest Data:\n";
                print_r($request->except('password'));
                
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create user in Supabase'
                ], 500);
            }

            $user = $response->json();
            $userId = $user['id'] ?? $user['user']['id'] ?? null;

            if (!$userId) {
                return response()->json(['success' => false, 'message' => 'Invalid Supabase user response'], 500);
            }

            // Begin your existing logic
            DB::beginTransaction();

            // 1. Create user in MySQL
            $userData = [
                'id' => $userId,
                'email' => $request->email,
                'name' => $request->name,
                'username' => $request->username,
                'phone' => $request->phone,
                'role' => 'member',
                'email_verified_at' => null,
            ];

            if ($request->hasFile('profile')) {
                $path = $request->file('profile')->store('profiles', 'public');
                $userData['profile_image_url'] = $path;
            }

            DB::table('users')->insert($userData);

            // 2. Create member account
            $accountId = DB::table('member_accounts')->insertGetId([
                'member_id' => $userId,
                'simpanan_pokok' => 0,
                'simpanan_wajib' => 0,
                'simpanan_sukarela' => 0,
                'sibuhar' => 0,
                'debt' => 0,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // 3. Calculate divided amounts
            $deposit = $request->deposit;
            $simpananPokok = $deposit * 0.5;
            $simpananWajib = $deposit * 0.25;
            $simpananSukarela = $deposit * 0.25;

            // 4. Create history records
            $histories = [
                [
                    'account_id' => $accountId,
                    'member_id' => $userId,
                    'staff_id' => $request->staff_id,
                    'amount' => $simpananPokok,
                    'description' => 'Initial deposit - Simpanan Pokok',
                    'direction' => 'simpanan_pokok',
                    'type' => 'deposit',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'account_id' => $accountId,
                    'member_id' => $userId,
                    'staff_id' => $request->staff_id,
                    'amount' => $simpananWajib,
                    'description' => 'Initial deposit - Simpanan Wajib',
                    'direction' => 'simpanan_wajib',
                    'type' => 'deposit',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'account_id' => $accountId,
                    'member_id' => $userId,
                    'staff_id' => $request->staff_id,
                    'amount' => $simpananSukarela,
                    'description' => 'Initial deposit - Simpanan Sukarela',
                    'direction' => 'simpanan_sukarela',
                    'type' => 'deposit',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            ];

            DB::table('histories')->insert($histories);

            DB::table('member_accounts')->where('id', $accountId)->update([
                'simpanan_pokok' => $simpananPokok,
                'simpanan_wajib' => $simpananWajib,
                'simpanan_sukarela' => $simpananSukarela,
                'updated_at' => now(),
            ]);

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Member registered successfully']);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
