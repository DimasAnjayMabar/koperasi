<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Models\StaffHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function updateStaff(Request $request)
    {
        DB::beginTransaction();

        $route = $request->has('email') ? route('change-email') : route('staff-profile');

        try {
            $staff = Staff::where('supabase_id', $request->supabase_id)
                    ->where('is_active', true)
                    ->where('is_deleted', false)
                    ->whereNull('deleted_at')
                    ->firstOrFail();

            $originalData = $staff->getOriginal();

            $updateData = [];
            $changes = json_decode($request->changes, true) ?? [];
            $descriptionParts = [];

            // Handle name change
            if (in_array('name', $changes)) {
                $newName = $request->name ?? $originalData['name'];
                if ($newName !== $originalData['name']) {
                    $updateData['name'] = $newName;
                    $descriptionParts[] = "changed name from '{$originalData['name']}' to '{$newName}'";
                }
            }

            // Handle email change
            if (in_array('email', $changes)) {
                $newEmail = $request->email ?? $originalData['email'];
                if ($newEmail !== $originalData['email']) {
                    $updateData['email'] = $newEmail;
                    $updateData['email_verified_at'] = null;
                    $descriptionParts[] = "changed email from '{$originalData['email']}' to '{$newEmail}'";
                }
            }

            // Handle phone change
            if (in_array('phone', $changes)) {
                $newPhone = $request->phone ?? $originalData['phone'];
                if ($newPhone !== $originalData['phone']) {
                    $updateData['phone'] = $newPhone;
                    $originalPhone = $originalData['phone'] ?? 'empty';
                    $descriptionParts[] = "changed phone from '{$originalPhone}' to '{$newPhone}'";
                }
            }

            // Handle profile picture change
            if (in_array('profile', $changes) && $request->hasFile('profile')) {
                if ($staff->profile) {
                    Storage::delete('profiles/' . basename($staff->profile));
                }

                $path = $request->file('profile')->store('profiles');
                $updateData['profile'] = Storage::url($path);
                $descriptionParts[] = "updated profile picture";
            }

            // Only update if something actually changed
            if (!empty($updateData)) {
                $staff->update($updateData);
            }

            // Create history record
            if (!empty($descriptionParts)) {
                $description = "Staff member " . implode(', ', $descriptionParts);

                StaffHistory::create([
                    'staff_id' => $staff->supabase_id,
                    'description' => $description,
                    'updated_at' => now()
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Staff updated successfully',
                'data' => $staff,
                'redirect' => $route
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to update staff',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
