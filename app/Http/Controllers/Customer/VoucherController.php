<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Voucher;
use App\Models\UserVoucher;

class VoucherController extends Controller
{
    public function index()
    {
        $user = auth()->guard('web')->user();

        // Get all available vouchers (not claimed yet) with registration condition check
        $availableVouchers = Voucher::where('is_active', true)
            ->where('end_date', '>=', now())
            ->whereDoesntHave('userVouchers', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->get()
            ->filter(function($voucher) use ($user) {
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

                // Check max usage
                if ($voucher->max_usage && $voucher->current_usage >= $voucher->max_usage) {
                    return false;
                }

                // Check max claims
                if ($voucher->max_claims && $voucher->claimed_count >= $voucher->max_claims) {
                    return false;
                }

                return true;
            })
            ->map(function($voucher) use ($user) {
                // Determine discount type and value
                if ($voucher->discount_percent) {
                    // Percentage discount
                    $discountValue = (float) $voucher->discount_percent;
                    // Remove .00 from percentage display
                    $discountDisplay = ($discountValue == (int)$discountValue)
                        ? (int)$discountValue . '%'
                        : number_format($discountValue, 2, '.', '') . '%';
                    $discountType = 'percentage';
                } else if ($voucher->max_discount) {
                    // No percentage but has max discount - show max discount as main value
                    $discountValue = (float) $voucher->max_discount;
                    $discountDisplay = 'Rp ' . number_format($discountValue, 0, ',', '.');
                    $discountType = 'fixed';
                } else {
                    // Fixed discount
                    $discountValue = (float) $voucher->discount_fixed;
                    $discountDisplay = 'Rp ' . number_format($discountValue, 0, ',', '.');
                    $discountType = 'fixed';
                }

                return [
                    'id' => $voucher->id,
                    'code' => $voucher->code,
                    'title' => $voucher->title,
                    'type' => $voucher->type,
                    'discount' => $discountDisplay,
                    'discountType' => $discountType,
                    'discountValue' => $discountValue,
                    'minTransaction' => $voucher->min_transaction,
                    'maxDiscount' => $voucher->max_discount,
                    'expiry' => $voucher->end_date ? \Carbon\Carbon::parse($voucher->end_date)->format('Y-m-d') : null,
                    'color' => $voucher->color ?? '#1c98f5',
                    'terms' => is_array($voucher->terms) ? $voucher->terms : json_decode((string)$voucher->terms, true),
                    'status' => 'available',
                    'claimed' => false,
                    'used' => false,
                ];
            })->values();

        // Get claimed vouchers (not used yet) - Tab "Voucher Saya"
        $myVouchers = UserVoucher::where('user_id', $user->id)
            ->whereNull('used_at')
            ->with('voucher')
            ->get()
            ->map(function($userVoucher) {
                $voucher = $userVoucher->voucher;

                // Determine discount type and value
                if ($voucher->discount_percent) {
                    // Percentage discount
                    $discountValue = (float) $voucher->discount_percent;
                    // Remove .00 from percentage display
                    $discountDisplay = ($discountValue == (int)$discountValue)
                        ? (int)$discountValue . '%'
                        : number_format($discountValue, 2, '.', '') . '%';
                    $discountType = 'percentage';
                } else if ($voucher->max_discount) {
                    // No percentage but has max discount - show max discount as main value
                    $discountValue = (float) $voucher->max_discount;
                    $discountDisplay = 'Rp ' . number_format($discountValue, 0, ',', '.');
                    $discountType = 'fixed';
                } else {
                    // Fixed discount
                    $discountValue = (float) $voucher->discount_fixed;
                    $discountDisplay = 'Rp ' . number_format($discountValue, 0, ',', '.');
                    $discountType = 'fixed';
                }

                return [
                    'id' => $voucher->id,
                    'code' => $voucher->code,
                    'title' => $voucher->title,
                    'type' => $voucher->type,
                    'discount' => $discountDisplay,
                    'discountType' => $discountType,
                    'discountValue' => $discountValue,
                    'minTransaction' => $voucher->min_transaction,
                    'maxDiscount' => $voucher->max_discount,
                    'expiry' => $voucher->end_date->format('Y-m-d'),
                    'color' => $voucher->color ?? '#1c98f5',
                    'terms' => is_array($voucher->terms) ? $voucher->terms : json_decode((string)$voucher->terms, true),
                    'status' => 'claimed',
                    'claimed' => true,
                    'used' => false,
                    'claimed_at' => $userVoucher->claimed_at->format('Y-m-d H:i:s'),
                ];
            });

        // Get used vouchers - Tab "Terpakai"
        $usedVouchers = UserVoucher::where('user_id', $user->id)
            ->whereNotNull('used_at')
            ->with('voucher')
            ->get()
            ->map(function($userVoucher) {
                $voucher = $userVoucher->voucher;

                // Determine discount type and value
                if ($voucher->discount_percent) {
                    // Percentage discount
                    $discountValue = (float) $voucher->discount_percent;
                    // Remove .00 from percentage display
                    $discountDisplay = ($discountValue == (int)$discountValue)
                        ? (int)$discountValue . '%'
                        : number_format($discountValue, 2, '.', '') . '%';
                    $discountType = 'percentage';
                } else if ($voucher->max_discount) {
                    // No percentage but has max discount - show max discount as main value
                    $discountValue = (float) $voucher->max_discount;
                    $discountDisplay = 'Rp ' . number_format($discountValue, 0, ',', '.');
                    $discountType = 'fixed';
                } else {
                    // Fixed discount
                    $discountValue = (float) $voucher->discount_fixed;
                    $discountDisplay = 'Rp ' . number_format($discountValue, 0, ',', '.');
                    $discountType = 'fixed';
                }

                return [
                    'id' => $voucher->id,
                    'code' => $voucher->code,
                    'title' => $voucher->title,
                    'type' => $voucher->type,
                    'discount' => $discountDisplay,
                    'discountType' => $discountType,
                    'discountValue' => $discountValue,
                    'minTransaction' => $voucher->min_transaction,
                    'maxDiscount' => $voucher->max_discount,
                    'expiry' => $voucher->end_date->format('Y-m-d'),
                    'color' => $voucher->color ?? '#1c98f5',
                    'terms' => is_array($voucher->terms) ? $voucher->terms : json_decode((string)$voucher->terms, true),
                    'status' => 'used',
                    'claimed' => true,
                    'used' => true,
                    'used_at' => $userVoucher->used_at->format('Y-m-d H:i:s'),
                ];
            });

        return view('customer.voucher.voucher', compact('availableVouchers', 'myVouchers', 'usedVouchers'));
    }

    public function claim(Request $request, $id)
    {
        $user = auth()->guard('web')->user();
        $voucher = Voucher::findOrFail($id);

        // Check if voucher is active and not expired
        if (!$voucher->is_active || $voucher->end_date < now()) {
            return response()->json([
                'success' => false,
                'message' => 'Voucher tidak tersedia atau sudah kadaluarsa'
            ], 400);
        }

        // Check if user already claimed this voucher
        $existingClaim = UserVoucher::where('user_id', $user->id)
            ->where('voucher_id', $voucher->id)
            ->first();

        if ($existingClaim) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah mengklaim voucher ini'
            ], 400);
        }

        // Check max usage
        if ($voucher->max_usage && $voucher->current_usage >= $voucher->max_usage) {
            return response()->json([
                'success' => false,
                'message' => 'Voucher sudah habis'
            ], 400);
        }

        // Check max claims
        if ($voucher->max_claims && $voucher->claimed_count >= $voucher->max_claims) {
            return response()->json([
                'success' => false,
                'message' => 'Voucher sudah mencapai batas maksimal klaim'
            ], 400);
        }

        // Check registration condition
        if ($voucher->registration_condition && $voucher->registration_condition !== 'none' && $voucher->registration_days) {
            $userRegistrationDays = now()->diffInDays($user->created_at);

            if ($voucher->registration_condition === 'less_than' && $userRegistrationDays >= $voucher->registration_days) {
                return response()->json([
                    'success' => false,
                    'message' => 'Voucher hanya untuk pengguna yang terdaftar kurang dari ' . $voucher->registration_days . ' hari'
                ], 400);
            }

            if ($voucher->registration_condition === 'greater_than' && $userRegistrationDays <= $voucher->registration_days) {
                return response()->json([
                    'success' => false,
                    'message' => 'Voucher hanya untuk pengguna yang terdaftar lebih dari ' . $voucher->registration_days . ' hari'
                ], 400);
            }
        }

        // Claim voucher
        UserVoucher::create([
            'user_id' => $user->id,
            'voucher_id' => $voucher->id,
            'claimed_at' => now()
        ]);

        // Increment claimed count
        $voucher->increment('claimed_count');

        return response()->json([
            'success' => true,
            'message' => 'Voucher berhasil diklaim!'
        ]);
    }
}
