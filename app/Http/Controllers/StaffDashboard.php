<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB as DB;

class StaffDashboard extends Controller
{
    public function getMemberAccount()
    {
        try {
            $memberAccounts = DB::table('users as u')
                ->join('member_accounts as ma', 'u.id', '=', 'ma.member_id')
                ->where('ma.is_active', 1)
                ->where('ma.is_deleted', 0)
                ->select(
                    'u.id',
                    'u.name',
                    'u.email',
                    'u.phone',
                    'u.profile',
                    'ma.simpanan_pokok',
                    'ma.simpanan_wajib',
                    'ma.simpanan_sukarela',
                    'ma.sibuhar',
                    'ma.debt'
                )
                ->get();

            // Return view if needed later
            return view('admin_page.dashboard.simpan', ['memberAccounts' => $memberAccounts]);

        } catch (\Exception $e) {
            return view('admin_page.dashboard.simpan', ['memberAccounts' => []]);
        }
    }

    public function previewMemberAccount($id){
        $member = DB::table('users as u')
        ->join('member_accounts as ma', 'u.id', '=', 'ma.member_id')
        ->where('u.id', $id)
        ->where('ma.is_active', 1)
        ->where('ma.is_deleted', 0)
        ->select(
            'u.id',
            'u.name',
            'u.email',
            'u.phone',
            'u.profile',
            'ma.simpanan_pokok',
            'ma.simpanan_wajib',
            'ma.simpanan_sukarela',
            'ma.sibuhar',
            'ma.debt'
        )
        ->first();

        if (!$member) return response()->json(['error' => 'Member not found'], 404);
        return response()->json($member);
    }

    public function getHistoryTransaction(){
        
    }
}
