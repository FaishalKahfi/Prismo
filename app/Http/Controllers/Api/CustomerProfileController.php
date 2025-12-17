<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CustomerProfileController extends Controller
{
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'customer') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'province' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'avatar' => 'nullable|image|max:2048'
        ]);

        // Update name and phone in users table
        $user->name = $validated['name'];
        if (isset($validated['phone'])) {
            $user->phone = $validated['phone'];
        }

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;
        }

        $user->save();

        // Update or create customer profile
        $profileData = [];
        if (isset($validated['phone'])) {
            $profileData['phone'] = $validated['phone'];
        }
        if (isset($validated['address'])) {
            $profileData['address'] = $validated['address'];
        }
        if (isset($validated['province'])) {
            $profileData['province'] = $validated['province'];
        }
        if (isset($validated['city'])) {
            $profileData['city'] = $validated['city'];
        }

        if (!empty($profileData)) {
            $user->customerProfile()->updateOrCreate(
                ['user_id' => $user->id],
                $profileData
            );
        }

        return response()->json([
            'success' => true,
            'message' => 'Profil berhasil diperbarui',
            'data' => [
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'avatar' => $user->avatar ? asset('storage/' . $user->avatar) : null
            ]
        ]);
    }

    public function checkRefundMethod(Request $request)
    {
        $user = $request->user();
        
        return response()->json([
            'has_refund_method' => !empty($user->refund_method) && !empty($user->refund_account_number),
            'refund_method' => $user->refund_method,
        ]);
    }

    public function getRefundMethod(Request $request)
    {
        $user = $request->user();
        
        return response()->json([
            'refund_method' => $user->refund_method,
            'account_number' => $user->refund_account_number,
        ]);
    }

    public function setRefundMethod(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'refund_method' => 'required|string|in:Dana,Gopay,OVO,ShopeePay',
            'account_number' => 'required|string|regex:/^[0-9]+$/|min:8|max:15',
        ]);

        $user->refund_method = $validated['refund_method'];
        $user->refund_account_number = $validated['account_number'];
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Metode refund berhasil disimpan',
        ]);
    }
}
