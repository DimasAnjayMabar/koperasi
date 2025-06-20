<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Models\StaffHistory;
use Carbon\Carbon;
use Illuminate\Http\Request;
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
}
