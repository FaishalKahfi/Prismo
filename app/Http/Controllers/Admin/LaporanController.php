<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\User;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function index()
    {
        // Statistik keseluruhan
        $totalRevenue = Booking::where('status', 'selesai')->sum('base_price');
        $totalBookings = Booking::where('status', 'selesai')->count();
        $totalMitra = User::where('role', 'mitra')->where('approval_status', 'approved')->count();
        $totalCustomers = User::where('role', 'customer')->count();

        // Top performing mitra
        $topMitras = Booking::select('mitra_id', DB::raw('COUNT(*) as bookings_count'), DB::raw('SUM(base_price) as total_revenue'))
            ->where('status', 'selesai')
            ->groupBy('mitra_id')
            ->orderBy('total_revenue', 'desc')
            ->limit(10)
            ->with(['mitra.mitraProfile'])
            ->get()
            ->map(function($item) {
                // Calculate rating from reviews table
                $reviews = \App\Models\Review::where('mitra_id', $item->mitra_id)->get();
                $reviewCount = $reviews->count();
                $averageRating = $reviewCount > 0 ? round($reviews->avg('rating'), 1) : 0;
                
                return (object)[
                    'business_name' => $item->mitra->mitraProfile->business_name ?? $item->mitra->name ?? '-',
                    'bookings_count' => $item->bookings_count,
                    'total_revenue' => $item->total_revenue,
                    'rating' => $averageRating
                ];
            });

        // Monthly revenue trend
        $monthlyRevenue = Booking::select(
                DB::raw('DATE_FORMAT(booking_date, "%Y-%m") as month'),
                DB::raw('SUM(base_price) as revenue'),
                DB::raw('COUNT(*) as bookings')
            )
            ->where('status', 'selesai')
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->limit(12)
            ->get();

        // Recent withdrawals
        $recentWithdrawals = Withdrawal::with('mitra')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function($w) {
                return [
                    'id' => $w->id,
                    'mitra_name' => $w->mitra->name ?? '-',
                    'amount' => $w->amount,
                    'status' => $w->status,
                    'created_at' => $w->created_at->format('Y-m-d H:i:s')
                ];
            });

        return view('admin.laporan.laporan', compact(
            'totalRevenue',
            'totalBookings',
            'totalMitra',
            'totalCustomers',
            'topMitras',
            'monthlyRevenue',
            'recentWithdrawals'
        ));
    }

    public function export(Request $request)
    {
        $type = $request->get('type', 'bulan-ini');

        // Tentukan rentang tanggal berdasarkan tipe
        $startDate = null;
        $endDate = Carbon::now();

        switch ($type) {
            case 'hari-ini':
                $startDate = Carbon::today();
                $fileName = 'Laporan_Pendapatan_Hari_Ini_' . Carbon::now()->format('d-m-Y') . '.csv';
                break;
            case 'minggu-ini':
                $startDate = Carbon::now()->startOfWeek();
                $fileName = 'Laporan_Pendapatan_Minggu_Ini_' . Carbon::now()->format('d-m-Y') . '.csv';
                break;
            case 'bulan-ini':
                $startDate = Carbon::now()->startOfMonth();
                $fileName = 'Laporan_Pendapatan_Bulan_Ini_' . Carbon::now()->format('m-Y') . '.csv';
                break;
            case 'tahun-ini':
                $startDate = Carbon::now()->startOfYear();
                $fileName = 'Laporan_Pendapatan_Tahun_Ini_' . Carbon::now()->format('Y') . '.csv';
                break;
            default:
                $startDate = Carbon::now()->startOfMonth();
                $fileName = 'Laporan_Pendapatan_' . Carbon::now()->format('d-m-Y') . '.csv';
        }

        // Ambil data booking dalam rentang tanggal
        $bookings = Booking::with(['mitra.mitraProfile', 'customer'])
            ->where('status', 'selesai')
            ->whereBetween('booking_date', [$startDate, $endDate])
            ->orderBy('booking_date', 'desc')
            ->get();

        // Hitung total
        $totalRevenue = $bookings->sum('base_price');
        $totalBookings = $bookings->count();

        // Generate CSV
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $callback = function() use ($bookings, $totalRevenue, $totalBookings, $type, $startDate, $endDate) {
            $file = fopen('php://output', 'w');

            // Add BOM for UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // Header informasi
            fputcsv($file, ['LAPORAN PENDAPATAN PRISMO']);
            fputcsv($file, ['Periode: ' . $startDate->format('d M Y') . ' - ' . $endDate->format('d M Y')]);
            fputcsv($file, ['Tanggal Export: ' . Carbon::now()->format('d M Y H:i:s')]);
            fputcsv($file, []);

            // Summary
            fputcsv($file, ['RINGKASAN']);
            fputcsv($file, ['Total Transaksi', $totalBookings]);
            fputcsv($file, ['Total Pendapatan', 'Rp ' . number_format($totalRevenue, 0, ',', '.')]);
            fputcsv($file, []);

            // Header tabel
            fputcsv($file, [
                'No',
                'Tanggal Booking',
                'Kode Booking',
                'Nama Customer',
                'Nama Mitra',
                'Layanan',
                'Harga Final',
                'Status Pembayaran',
                'Metode Pembayaran'
            ]);

            // Data
            $no = 1;
            foreach ($bookings as $booking) {
                fputcsv($file, [
                    $no++,
                    Carbon::parse($booking->booking_date)->format('d/m/Y'),
                    $booking->booking_code ?? '-',
                    $booking->customer->name ?? '-',
                    $booking->mitra->mitraProfile->business_name ?? $booking->mitra->name ?? '-',
                    $booking->service_type ?? '-',
                    'Rp ' . number_format((float)$booking->base_price, 0, ',', '.'),
                    $booking->payment_status ?? '-',
                    $booking->payment_method ?? '-'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
