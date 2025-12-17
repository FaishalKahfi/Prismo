<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class KelolaMitraController extends Controller
{
    public function index()
    {
        $mitras = User::where('role', 'mitra')
            ->with('mitraProfile')
            ->whereHas('mitraProfile') // Only show mitras who have submitted the form
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($mitra) {
                // Calculate rating from reviews table
                $reviews = \App\Models\Review::where('mitra_id', $mitra->id)->get();
                $reviewCount = $reviews->count();
                $averageRating = $reviewCount > 0 ? round($reviews->avg('rating'), 1) : 0;

                // Calculate saldo (available balance)
                $totalEarnings = \App\Models\Booking::where('mitra_id', $mitra->id)
                    ->where('status', 'selesai')
                    ->sum('base_price');

                $totalWithdrawn = \App\Models\Withdrawal::where('mitra_id', $mitra->id)
                    ->where('status', 'completed')
                    ->sum('amount');

                $availableBalance = $totalEarnings - $totalWithdrawn;

                return [
                    'id' => $mitra->id,
                    'name' => $mitra->name,
                    'email' => $mitra->email,
                    'phone' => optional($mitra->mitraProfile)->phone ?? '-',  // Ambil dari mitraProfile
                    'business_name' => optional($mitra->mitraProfile)->business_name ?? '-',
                    'address' => optional($mitra->mitraProfile)->address ?? '-',
                    'city' => optional($mitra->mitraProfile)->city ?? '-',
                    'approval_status' => $mitra->approval_status,
                    'rating' => $averageRating,
                    'saldo' => $availableBalance,
                    'status' => $mitra->status ?? 'Aktif',
                    'created_at' => $mitra->created_at->format('Y-m-d')
                ];
            })
        ->values() // Convert to array with sequential keys
        ->toArray(); // Convert Collection to array

        Log::info('Kelola Mitra - Total mitras: ' . count($mitras));
        Log::info('Kelola Mitra - Data: ' . json_encode($mitras));        return view('admin.kelolamitra.kelolamitra', compact('mitras'));
    }

    public function show($id)
    {
        $mitra = User::where('role', 'mitra')
            ->with('mitraProfile')
            ->findOrFail($id);

        return view('admin.kelolamitra.form', compact('mitra'));
    }

    public function approve($id)
    {
        // Verify admin access
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access'
            ], 403);
        }

        $mitra = User::findOrFail($id);
        $mitra->approval_status = 'approved';
        $mitra->save();

        // Send in-app notification to mitra
        NotificationService::mitraApproved($mitra);

        // Send email notification to mitra
        try {
            Mail::to($mitra->email)->send(new \App\Mail\MitraApprovedMail($mitra));
            Log::info('Approval email sent to mitra: ' . $mitra->email);
        } catch (\Exception $e) {
            Log::error('Failed to send approval email: ' . $e->getMessage());
        }

        return response()->json(['success' => true, 'message' => 'Mitra berhasil disetujui dan email notifikasi telah dikirim']);
    }

    public function reject(Request $request, $id)
    {
        $request->validate([
            'reject_reason' => 'required|string|min:10'
        ]);

        $mitra = User::findOrFail($id);
        $mitra->approval_status = 'rejected';
        $mitra->save();

        // Simpan alasan reject ke mitra profile atau tabel terpisah
        if ($mitra->mitraProfile) {
            $mitra->mitraProfile->reject_reason = $request->reject_reason;
            $mitra->mitraProfile->save();
        }

        // Send in-app notification
        NotificationService::mitraRejected($mitra, $request->reject_reason);

        // Kirim email notifikasi
        try {
            Mail::to($mitra->email)->send(new \App\Mail\MitraRejectedMail($mitra, $request->reject_reason));
        } catch (\Exception $e) {
            Log::error('Failed to send rejection email: ' . $e->getMessage());
        }

        return response()->json(['success' => true, 'message' => 'Mitra berhasil ditolak dan email notifikasi telah dikirim']);
    }
}
