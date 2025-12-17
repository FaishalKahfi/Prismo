<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserVoucher;
use App\Models\Voucher;
use Illuminate\Http\Request;
use App\Services\NotificationService;

class VoucherController extends Controller
{
    public function index()
    {
        $vouchers = Voucher::orderBy('created_at', 'desc')->get();
        return response()->json($vouchers);
    }

    public function available(Request $request)
    {
        $user = $request->user();

        // Auto-delete expired vouchers from user_vouchers table
        UserVoucher::whereHas('voucher', function($query) {
            $query->where('end_date', '<', now());
        })->delete();

        // Also delete expired vouchers from vouchers table
        Voucher::where('end_date', '<', now())->delete();

        $vouchers = Voucher::where('is_active', true)
            ->where('end_date', '>=', now())
            ->where(function ($query) {
                $query->whereNull('start_date')
                    ->orWhere('start_date', '<=', now());
            })
            ->where(function ($query) {
                $query->whereNull('max_usage')
                    ->orWhereRaw('current_usage < max_usage');
            })
            ->where(function ($query) {
                $query->whereNull('max_claims')
                    ->orWhereRaw('claimed_count < max_claims');
            })
            ->get()
            ->filter(function ($voucher) use ($user) {
                // Check user claim limit
                $userClaimCount = UserVoucher::where('user_id', $user->id)
                    ->where('voucher_id', $voucher->id)
                    ->count();

                if ($userClaimCount >= $voucher->max_usage_per_user) {
                    return false;
                }

                // Check registration condition
                if ($voucher->registration_condition && $voucher->registration_condition !== 'none' && $voucher->registration_days) {
                    $userRegistrationDays = now()->diffInDays($user->created_at);

                    if ($voucher->registration_condition === 'less_than' && $userRegistrationDays >= $voucher->registration_days) {
                        return false;
                    }

                    if ($voucher->registration_condition === 'greater_than' && $userRegistrationDays <= $voucher->registration_days) {
                        return false;
                    }
                }

                return true;
            })
            ->values();

        return response()->json($vouchers);
    }

    public function myVouchers(Request $request)
    {
        $user = $request->user();

        $vouchers = $user->vouchers()
            ->withPivot('claimed_at', 'used_at', 'booking_id')
            ->orderBy('user_vouchers.claimed_at', 'desc')
            ->get();

        return response()->json($vouchers);
    }

    public function claim(Request $request, $id)
    {
        $user = $request->user();
        $voucher = Voucher::findOrFail($id);

        // Check if voucher is active and valid
        if (!$voucher->is_active) {
            return response()->json(['message' => 'Voucher tidak aktif'], 400);
        }

        if ($voucher->end_date < now()) {
            return response()->json(['message' => 'Voucher sudah kadaluarsa'], 400);
        }

        if ($voucher->start_date && $voucher->start_date > now()) {
            return response()->json(['message' => 'Voucher belum dapat diklaim'], 400);
        }

        // Check max claims - jika sudah mencapai limit, voucher akan dihapus
        if ($voucher->max_claims && $voucher->claimed_count >= $voucher->max_claims) {
            return response()->json(['message' => 'Voucher sudah habis'], 400);
        }

        // Check max usage
        if ($voucher->max_usage && $voucher->current_usage >= $voucher->max_usage) {
            return response()->json(['message' => 'Voucher sudah habis'], 400);
        }

        // Check user claim limit
        $userClaimCount = UserVoucher::where('user_id', $user->id)
            ->where('voucher_id', $voucher->id)
            ->count();

        if ($userClaimCount >= $voucher->max_usage_per_user) {
            return response()->json(['message' => 'Anda sudah mencapai batas klaim voucher ini'], 400);
        }

        // Check if already claimed
        $existingClaim = UserVoucher::where('user_id', $user->id)
            ->where('voucher_id', $voucher->id)
            ->whereNull('used_at')
            ->first();

        if ($existingClaim) {
            return response()->json(['message' => 'Anda sudah memiliki voucher ini'], 400);
        }

        // Check registration condition
        if ($voucher->registration_condition && $voucher->registration_condition !== 'none' && $voucher->registration_days) {
            $userRegistrationDays = now()->diffInDays($user->created_at);

            if ($voucher->registration_condition === 'less_than' && $userRegistrationDays >= $voucher->registration_days) {
                return response()->json(['message' => 'Voucher ini hanya untuk pengguna yang terdaftar kurang dari ' . $voucher->registration_days . ' hari'], 400);
            }

            if ($voucher->registration_condition === 'greater_than' && $userRegistrationDays <= $voucher->registration_days) {
                return response()->json(['message' => 'Voucher ini hanya untuk pengguna yang terdaftar lebih dari ' . $voucher->registration_days . ' hari'], 400);
            }
        }

        // Create user voucher
        UserVoucher::create([
            'user_id' => $user->id,
            'voucher_id' => $voucher->id,
            'claimed_at' => now(),
        ]);

        // Increment usage count
        $voucher->increment('current_usage');

        // Increment claimed count
        $voucher->increment('claimed_count');

        // Auto-delete voucher jika sudah mencapai max_claims
        if ($voucher->max_claims && $voucher->claimed_count >= $voucher->max_claims) {
            $voucher->delete();
        }

        return response()->json(['message' => 'Voucher berhasil diklaim']);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|unique:vouchers,code',
            'title' => 'required|string',
            'description' => 'nullable|string',
            'type' => 'required|in:discount,cashback,free_service',
            'discount_percent' => 'nullable|numeric|min:0|max:100',
            'discount_fixed' => 'nullable|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'min_transaction' => 'nullable|numeric|min:0',
            'start_date' => 'nullable|date',
            'end_date' => 'required|date',
            'max_usage' => 'nullable|integer|min:1',
            'max_usage_per_user' => 'nullable|integer|min:1',
            'max_claims' => 'nullable|integer|min:1',
            'registration_condition' => 'nullable|in:none,less_than,greater_than',
            'registration_days' => 'nullable|integer|min:1',
            'color' => 'nullable|string',
            'terms' => 'nullable|array',
            'is_active' => 'boolean',
        ]);

        $validated['current_usage'] = 0;
        $validated['claimed_count'] = 0;
        $validated['max_usage_per_user'] = $validated['max_usage_per_user'] ?? 1;
        $validated['registration_condition'] = $validated['registration_condition'] ?? 'none';

        $voucher = Voucher::create($validated);

        // Send notification to all customers about new voucher
        $customers = User::where('role', 'customer')->get();
        foreach ($customers as $customer) {
            NotificationService::create(
                $customer->id,
                'voucher_new',
                'Voucher Baru Tersedia!',
                "Voucher baru '{$voucher->title}' telah tersedia. Gunakan kode {$voucher->code} untuk mendapatkan diskon!",
                $voucher->id
            );
        }

        return response()->json($voucher, 201);
    }

    public function update(Request $request, $id)
    {
        $voucher = Voucher::findOrFail($id);

        $validated = $request->validate([
            'title' => 'sometimes|string',
            'description' => 'nullable|string',
            'type' => 'sometimes|in:discount,cashback,free_service',
            'discount_percent' => 'nullable|numeric|min:0|max:100',
            'discount_fixed' => 'nullable|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'min_transaction' => 'nullable|numeric|min:0',
            'start_date' => 'nullable|date',
            'end_date' => 'sometimes|date',
            'max_usage' => 'nullable|integer|min:1',
            'max_usage_per_user' => 'nullable|integer|min:1',
            'max_claims' => 'nullable|integer|min:1',
            'registration_condition' => 'nullable|in:none,less_than,greater_than',
            'registration_days' => 'nullable|integer|min:1',
            'color' => 'nullable|string',
            'terms' => 'nullable|array',
            'is_active' => 'boolean',
        ]);

        $voucher->update($validated);

        return response()->json($voucher);
    }

    public function destroy($id)
    {
        $voucher = Voucher::findOrFail($id);

        // Check if voucher is used
        $usedCount = UserVoucher::where('voucher_id', $voucher->id)
            ->whereNotNull('used_at')
            ->count();

        if ($usedCount > 0) {
            return response()->json(['message' => 'Tidak dapat menghapus voucher yang sudah digunakan'], 400);
        }

        $voucher->delete();

        return response()->json(['message' => 'Voucher berhasil dihapus']);
    }

    /**
     * Get user's claimed vouchers
     * Returns both unused and used vouchers
     */
    public function myClaimed(Request $request)
    {
        $user = $request->user();

        // Auto-delete expired vouchers
        UserVoucher::whereHas('voucher', function($query) {
            $query->where('end_date', '<', now());
        })->delete();

        // Get unused vouchers (available to use)
        $unusedVouchers = UserVoucher::where('user_id', $user->id)
            ->whereNull('used_at')
            ->with('voucher')
            ->get()
            ->filter(function($userVoucher) {
                return $userVoucher->voucher &&
                       $userVoucher->voucher->is_active &&
                       $userVoucher->voucher->end_date >= now();
            })
            ->map(function($userVoucher) {
                $voucher = $userVoucher->voucher;
                return [
                    'code' => $voucher->code,
                    'title' => $voucher->title,
                    'description' => $voucher->description,
                    'discount_percent' => $voucher->discount_percent,
                    'discount_fixed' => $voucher->discount_fixed,
                    'type' => $voucher->discount_percent ? 'percentage' : 'fixed',
                    'min_transaction' => $voucher->min_transaction,
                    'max_discount' => $voucher->max_discount,
                    'end_date' => $voucher->end_date->format('Y-m-d'),
                    'claimed_at' => $userVoucher->created_at->format('Y-m-d H:i:s'),
                    'status' => 'unused'
                ];
            })
            ->values();

        // Get used vouchers
        $usedVouchers = UserVoucher::where('user_id', $user->id)
            ->whereNotNull('used_at')
            ->with('voucher')
            ->orderBy('used_at', 'desc')
            ->get()
            ->map(function($userVoucher) {
                $voucher = $userVoucher->voucher;
                if (!$voucher) return null; // Skip if voucher deleted

                return [
                    'code' => $voucher->code,
                    'title' => $voucher->title,
                    'description' => $voucher->description,
                    'discount_percent' => $voucher->discount_percent,
                    'discount_fixed' => $voucher->discount_fixed,
                    'type' => $voucher->discount_percent ? 'percentage' : 'fixed',
                    'used_at' => $userVoucher->used_at->format('Y-m-d H:i:s'),
                    'status' => 'used'
                ];
            })
            ->filter()
            ->values();

        return response()->json([
            'unused' => $unusedVouchers,
            'used' => $usedVouchers
        ]);
    }
}
