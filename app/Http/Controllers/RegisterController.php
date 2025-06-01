<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

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
            'email_verified_at' => 'nullable|date',
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

    public function verifyMember(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:users,id',
            'email_verified_at' => 'nullable|date',
            'is_active' => 'boolean'
        ]);

        try {
            // Start a database transaction
            DB::beginTransaction();

            // Update the user's email verification status
            $user = User::findOrFail($request->id);
            $user->update($request->only('email_verified_at'));
            
            // Update the member_account's is_active status if it exists in the request
            if ($request->has('is_active')) {
                DB::table('member_accounts')
                    ->where('member_id', $request->id) // assuming there's a user_id column
                    ->update(['is_active' => $request->is_active]);
            }

            // Commit the transaction
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'User updated successfully'
            ]);
        } catch (\Exception $e) {
            // Rollback the transaction in case of error
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to update user: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function registerMember(Request $request)
    {
        try {
            // Validate the request
            $validated = $request->validate([
                'id' => 'required|string|unique:users,id',
                'email' => 'required|email|unique:users,email',
                'name' => 'required|string|max:255',
                'username' => 'required|string|unique:users,username',
                'phone' => 'required|string',
                'deposit' => 'required|numeric|min:0',
                'staff_id' => [
                    'required',
                    Rule::exists('users', 'id')->where('role', 'staff'),
                ],
                'profile' => 'nullable|image|max:2048',
                'role' => 'required|in:member,staff,admin' // Add role validation
            ]);

            DB::beginTransaction();

            // 1. Create user in MySQL
            $userData = [
                'id' => $validated['id'],
                'email' => $validated['email'],
                'name' => $validated['name'],
                'username' => $validated['username'],
                'phone' => $validated['phone'],
                'role' => 'member',
                'email_verified_at' => null, // Will be updated when email is confirmed
                'created_at' => now(),
                'updated_at' => now()
            ];

            if ($request->hasFile('profile')) {
                $path = $request->file('profile')->store("profiles/{$request->id}", 'public');
                $userData['profile'] = Storage::url($path);  // Add this line to match staff registration
            }

            DB::table('users')->insert($userData);

            // 2. Create member account with initial zero balances
            $accountId = DB::table('member_accounts')->insertGetId([
                'member_id' => $validated['id'],
                'simpanan_pokok' => 0,
                'simpanan_wajib' => 0,
                'simpanan_sukarela' => 0,
                'sibuhar' => 0,
                'debt' => 0,
                'is_active' => false, // Will activate after email confirmation
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // 3. Calculate and record initial deposits
            $deposit = $validated['deposit'];
            $allocations = [
                'simpanan_pokok' => $deposit * 0.5,
                'simpanan_wajib' => $deposit * 0.25,
                'simpanan_sukarela' => $deposit * 0.25
            ];

            // Create history records
            $histories = [];
            foreach ($allocations as $type => $amount) {
                $histories[] = [
                    'account_id' => $accountId,
                    'member_id' => $validated['id'],
                    'staff_id' => $validated['staff_id'],
                    'amount' => $amount,
                    'description' => "Initial deposit - " . ucfirst(str_replace('_', ' ', $type)),
                    'direction' => $type,
                    'type' => 'deposit',
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }

            DB::table('histories')->insert($histories);

            // Update account balances
            DB::table('member_accounts')->where('id', $accountId)->update($allocations + [
                'updated_at' => now()
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Member records created successfully',
                'user_id' => $validated['id']
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to create member records: ' . $e->getMessage()
            ], 500);
        }
    }
}
