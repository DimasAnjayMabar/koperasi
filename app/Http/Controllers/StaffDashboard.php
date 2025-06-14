<?php

namespace App\Http\Controllers;

use App\Models\MemberAccount;
use App\Models\Staff;
use App\Models\TransactionHistory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB as DB;
use Illuminate\Support\Facades\Log;

class StaffDashboard extends Controller
{
    public function getStaff(Request $request){
        $email = $request -> input('email');

        $user = Staff::where('email', $email) -> first();

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        return response() -> json([
            'supabase_id' => $user -> id,
            'name' => $user -> name,
            'email' => $user -> email,
            'phone' => $user -> phone,
            'username' => $user -> username, 
            'profile' => $user -> profile,
            
        ]);
    }

    public function getMemberAccount()
    {
        try {
            $memberAccounts = MemberAccount::select('member_accounts.*', 'members.*')
                ->join('members', 'member_accounts.member_id', '=', 'members.supabase_id')
                ->where('member_accounts.is_active', true)
                ->where('member_accounts.is_deleted', false)
                ->where('members.is_active', true)
                ->where('members.is_deleted', false)
                ->get();
            

            return view('admin_page.dashboard.simpan', compact('memberAccounts'));
            
        } catch (\Exception $e) {
            // Log the error
            Log::error('Error fetching member accounts: ' . $e->getMessage());
            
            // Return to the view with an error message
            return view('admin_page.dashboard.simpan')
                ->with('error', 'Failed to retrieve member accounts. Please try again later.');
        }
    }

    public function previewMemberAccount($id)
    {
        try {
            $member = DB::table('members as m')
                ->join('member_accounts as ma', 'm.supabase_id', '=', 'ma.member_id')
                ->where('m.supabase_id', $id)
                ->where('ma.is_active', true)
                ->where('ma.is_deleted', false)
                ->where('m.is_active', true)
                ->where('m.is_deleted', false)
                ->select(
                    'm.supabase_id as id',
                    'm.name',
                    'm.email',
                    'm.phone',
                    'm.profile',
                    'ma.simpanan_pokok',
                    'ma.simpanan_wajib',
                    'ma.simpanan_sukarela',
                    'ma.sibuhar',
                    'ma.loan'  // Changed from 'debt' to 'loan' to match your schema
                )
                ->first();

            if (!$member) {
                return response()->json(['error' => 'Member not found or account inactive'], 404);
            }

            return response()->json($member);

        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Error in previewMemberAccount: ' . $e->getMessage());
            
            return response()->json([
                'error' => 'An error occurred while fetching member data',
                'details' => env('APP_DEBUG') ? $e->getMessage() : null
            ], 500);
        }
    }

    public function getHistoryTransaction()
    {
        $histories = TransactionHistory::with(['member', 'staff', 'memberAccount'])
        ->orderBy('created_at', 'desc')
        ->get();

        return view('admin_page.dashboard.histories', compact('histories'));
    }

    public function editMemberAccount($id){
        
    }
}
