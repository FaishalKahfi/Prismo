<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Mail\AdminAccountCreated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class KelolaAdminController extends Controller
{
    public function index()
    {
        $admins = User::where('role', 'admin')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($admin) {
                return [
                    'id' => $admin->id,
                    'name' => $admin->name,
                    'email' => $admin->email,
                    'created_at' => $admin->created_at->format('Y-m-d H:i:s')
                ];
            });

        return view('admin.kelolaadmin.kelolaadmin', compact('admins'));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:6',
            ]);

            // Simpan plain password untuk dikirim via email
            $plainPassword = $validated['password'];

            $admin = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($plainPassword),
                'role' => 'admin',
                'email_verified_at' => now(), // Admin auto-verified
                'avatar' => '/images/profile.png',
                'approval_status' => 'approved', // Admin langsung approved
            ]);

            // Kirim email notifikasi ke admin baru
            try {
                Mail::to($admin->email)->send(new AdminAccountCreated($admin, $plainPassword));
            } catch (\Exception $e) {
                Log::error('Failed to send admin account email: ' . $e->getMessage());
                // Tidak perlu throw error, akun tetap dibuat
            }

            return response()->json([
                'success' => true,
                'message' => 'Admin berhasil ditambahkan dan email notifikasi telah dikirim',
                'data' => [
                    'id' => $admin->id,
                    'name' => $admin->name,
                    'email' => $admin->email,
                    'created_at' => $admin->created_at->format('Y-m-d H:i:s')
                ]
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $admin = User::where('role', 'admin')->findOrFail($id);

            // Prevent deleting self
            if ($admin->id === Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak dapat menghapus akun admin Anda sendiri'
                ], 403);
            }

            // Check if this is the last admin
            $adminCount = User::where('role', 'admin')->count();
            if ($adminCount <= 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak dapat menghapus admin terakhir'
                ], 403);
            }

            $admin->delete();

            return response()->json([
                'success' => true,
                'message' => 'Admin berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
