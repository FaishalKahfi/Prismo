<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\NotificationService;

class KelolaBookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::with(['customer', 'mitra.mitraProfile'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($booking) {
                $bookingDate = $booking->booking_date;
                if ($bookingDate instanceof \Carbon\Carbon) {
                    $bookingDate = $bookingDate->format('Y-m-d');
                } elseif ($bookingDate instanceof \DateTime) {
                    $bookingDate = $bookingDate->format('Y-m-d');
                }

                $result = [
                    'id' => $booking->id,
                    'booking_code' => $booking->booking_code ?? 'BK-' . str_pad($booking->id, 5, '0', STR_PAD_LEFT),
                    'customer_name' => $booking->customer->name ?? '-',
                    'customer_email' => $booking->customer->email ?? '-',
                    'mitra_name' => $booking->mitra && $booking->mitra->mitraProfile
                        ? $booking->mitra->mitraProfile->business_name
                        : ($booking->mitra->name ?? '-'),
                    'service_name' => $booking->service_type ?? '-',
                    'booking_date' => $bookingDate,
                    'booking_time' => substr($booking->booking_time, 0, 5),
                    'status' => $booking->status,
                    'payment_status' => $booking->payment_status,
                    'total_price' => $booking->base_price,
                    'payment_method' => $booking->payment_method ?? '-',
                    'payment_proof' => $booking->payment_proof ? asset('storage/' . $booking->payment_proof) : null,
                    'vehicle_type' => $booking->vehicle_type,
                    'vehicle_plate' => $booking->vehicle_plate,
                    'wallet_number' => $booking->wallet_number ?? '-',
                    'refund_method' => $booking->customer ? $booking->customer->refund_method : null,
                    'refund_account_number' => $booking->customer ? $booking->customer->refund_account_number : null,
                    'created_at' => $booking->created_at->format('Y-m-d H:i:s')
                ];

                // Debug first booking with refund data
                if ($booking->id === 6 || $booking->id === 7 || $booking->id === 8) {
                    Log::info('Booking #' . $booking->id . ' refund data:', [
                        'customer_id' => $booking->customer_id,
                        'customer_name' => $booking->customer->name ?? null,
                        'refund_method' => $booking->customer->refund_method ?? null,
                        'refund_account' => $booking->customer->refund_account_number ?? null,
                        'result_refund_method' => $result['refund_method'],
                        'result_refund_account' => $result['refund_account_number']
                    ]);
                }

                return $result;
            });

        return view('admin.kelolabooking.kelolabooking', compact('bookings'));
    }

    public function confirmBooking($id)
    {
        try {
            $booking = Booking::findOrFail($id);

            // Update status - payment_status ENUM: pending, confirmed, failed
            // Status ENUM: menunggu, proses, selesai, dibatalkan
            // Admin confirmation sets status to 'menunggu' (waiting for customer arrival at mitra location)
            // Mitra will change to 'proses' when customer arrives
            $booking->status = 'menunggu';
            $booking->payment_status = 'confirmed';
            $booking->confirmed_at = now();
            $booking->save();

            return response()->json([
                'success' => true,
                'message' => 'Booking berhasil dikonfirmasi'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengkonfirmasi booking: ' . $e->getMessage()
            ], 500);
        }
    }

    public function cancelBooking($id)
    {
        try {
            $booking = Booking::findOrFail($id);

            // Update status - ENUM: menunggu, proses, selesai, dibatalkan
            $booking->status = 'dibatalkan';
            $booking->cancelled_at = now();
            $booking->save();

            return response()->json([
                'success' => true,
                'message' => 'Booking berhasil dibatalkan'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal membatalkan booking: ' . $e->getMessage()
            ], 500);
        }
    }

    public function completeRefund($id)
    {
        try {
            $booking = Booking::with('customer')->findOrFail($id);

            // Update status - Set refund completed
            $booking->refund_completed_at = now();
            $booking->payment_status = 'failed';
            $booking->save();

            // Send notification to customer
            $customer = $booking->customer;
            if ($customer) {
                $notificationService = app(NotificationService::class);
                $notificationService->create(
                    $customer->id,
                    'refund_completed',
                    'Pengembalian Dana Berhasil',
                    "Pengembalian dana untuk booking {$booking->booking_code} telah berhasil diproses. Dana akan segera masuk ke akun Anda.",
                    $booking->id
                );
            }

            return response()->json([
                'success' => true,
                'message' => 'Refund berhasil diselesaikan'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyelesaikan refund: ' . $e->getMessage()
            ], 500);
        }
    }
}
