<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\MemberHistory;
use App\Models\Staff;
use App\Models\StaffHistory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function updateStaff(Request $request)
    {
        $request->validate([
            'supabase_id' => 'required|string|exists:staffs,supabase_id',
            'name' => 'nullable|string',
            'phone' => 'nullable|string',
            'profile' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
        ]);

        $staff = Staff::findOrFail($request->supabase_id);
        $original = $staff->only(['name', 'phone', 'profile']);

        $name = $request->filled('name') ? $request->name : $staff->name;
        $phone = $request->filled('phone') ? $request->phone : $staff->phone;

        // Tangani upload file ke storage/app/profiles
        if ($request->hasFile('profile')) {
            if ($staff->profile && Storage::exists($staff->profile)) {
                Storage::delete($staff->profile);
            }
            $path = $request->file('profile')->store('profiles'); // default disk = local = storage/app/profiles :contentReference[oaicite:1]{index=1}
        } else {
            $path = $staff->profile;
        }

        $changes = [];
        if ($name !== $original['name']) {
            $changes[] = "Nama: '{$original['name']}' → '$name'";
        }
        if ($phone !== $original['phone']) {
            $changes[] = "Telepon: '{$original['phone']}' → '$phone'";
        }
        if ($path !== $original['profile']) {
            $changes[] = "Foto profil diubah.";
        }

        if ($changes) {
            StaffHistory::create([
                'staff_id' => $staff->supabase_id,
                'description' => implode('; ', $changes),
                'updated_at' => Carbon::now(),
            ]);
        }

        $staff->update([
            'name' => $name,
            'phone' => $phone,
            'profile' => $path,
        ]);

        return response()->json(['message' => 'Staff updated successfully.']);
    }

    public function updateMember(Request $request)
    {
        $request->validate([
            'supabase_id' => 'required|string|exists:members,supabase_id',
            'name' => 'nullable|string',
            'phone' => 'nullable|string',
            'profile' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
        ]);

        $member = Member::findOrFail($request->supabase_id);
        $original = $member->only(['name', 'phone', 'profile']);

        $name = $request->filled('name') ? $request->name : $member->name;
        $phone = $request->filled('phone') ? $request->phone : $member->phone;

        // Tangani upload file ke storage/app/profiles
        if ($request->hasFile('profile')) {
            if ($member->profile && Storage::exists($member->profile)) {
                Storage::delete($member->profile);
            }
            $path = $request->file('profile')->store('profiles'); // default disk = local = storage/app/profiles :contentReference[oaicite:1]{index=1}
        } else {
            $path = $member->profile;
        }

        $changes = [];
        if ($name !== $original['name']) {
            $changes[] = "Nama: '{$original['name']}' → '$name'";
        }
        if ($phone !== $original['phone']) {
            $changes[] = "Telepon: '{$original['phone']}' → '$phone'";
        }
        if ($path !== $original['profile']) {
            $changes[] = "Foto profil diubah.";
        }

        if ($changes) {
            MemberHistory::create([
                'member_id' => $member->supabase_id,
                'description' => implode('; ', $changes),
                'updated_at' => Carbon::now(),
            ]);
        }

        $member->update([
            'name' => $name,
            'phone' => $phone,
            'profile' => $path,
        ]);

        return response()->json(['message' => 'Member updated successfully.']);
    }

    public function updateEmailStaff(Request $request){
        // Validasi langsung
        $validated = $request->validate([
            'staff_id' => 'required|exists:staffs,supabase_id',
            'email' => 'required|email|unique:staffs,email',
        ]);

        try {
            DB::beginTransaction();

            // Update email staff
            DB::table('staffs')
                ->where('supabase_id', $validated['staff_id'])
                ->update([
                    'email' => $validated['email'],
                    'updated_at' => now(),
                ]);

            // Catat perubahan ke tabel history
            DB::table('staffs_history')->insert([
                'staff_id' => $validated['staff_id'],
                'description' => 'Email staff diubah menjadi ' . $validated['email'],
                'updated_at' => now(),
            ]);

            DB::commit();

            return response()->json(['message' => 'Email berhasil diperbarui.'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Gagal mengubah email.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
