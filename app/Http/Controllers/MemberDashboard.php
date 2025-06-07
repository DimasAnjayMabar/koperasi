<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\MemberAccount;
use Illuminate\Http\Request;

class MemberDashboard extends Controller
{
    public function getMember(Request $request) {
        $email = $request->input('email');

        $user = Member::where('email', $email)->first();

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        // Get the member's account information
        $memberAccount = MemberAccount::where('member_id', $user->supabase_id)->first();

        $response = [
            'supabase_id' => $user->supabase_id,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'username' => $user->username, 
            'profile' => $user->profile,
        ];

        // If member account exists, add the financial data to the response
        if ($memberAccount) {
            $response['account'] = [
                'simpanan_pokok' => $memberAccount->simpanan_pokok,
                'simpanan_wajib' => $memberAccount->simpanan_wajib,
                'simpanan_sukarela' => $memberAccount->simpanan_sukarela,
                'sibuhar' => $memberAccount->sibuhar,
                'loan' => $memberAccount->loan,
                'is_active' => $memberAccount->is_active,
            ];
        }

        return response()->json($response);
    }
}
