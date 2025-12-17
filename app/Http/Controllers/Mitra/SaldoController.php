<?php

namespace App\Http\Controllers\Mitra;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Withdrawal;
use App\Models\Booking;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class SaldoController extends Controller
{
    public function index()
    {
        $mitra = auth()->user();
        
        // Total earnings from completed bookings (saldo kotor)
        $totalEarnings = Booking::where('mitra_id', $mitra->id)
            ->where('status', 'selesai')
            ->sum('base_price');
        
        // Total withdrawn (completed withdrawals)
        $totalWithdrawn = Withdrawal::where('mitra_id', $mitra->id)
            ->where('status', 'completed')
            ->sum('amount');
        
        // Saldo tersedia = total earnings - total withdrawn
        $availableBalance = $totalEarnings - $totalWithdrawn;
        
        // Saldo bersih hari ini (dari booking selesai hari ini)
        // Use updated_at as fallback if completed_at is null
        $todayEarnings = Booking::where('mitra_id', $mitra->id)
            ->where('status', 'selesai')
            ->where(function($query) {
                $query->whereDate('completed_at', now()->toDateString())
                      ->orWhere(function($q) {
                          $q->whereNull('completed_at')
                            ->whereDate('updated_at', now()->toDateString());
                      });
            })
            ->sum('base_price');
        
        // Pending withdrawals (status pending atau approved)
        $pendingWithdrawals = Withdrawal::where('mitra_id', $mitra->id)
            ->whereIn('status', ['pending', 'approved'])
            ->sum('amount');
        
        // Cek apakah sudah ada penarikan hari ini
        $hasWithdrawnToday = Withdrawal::where('mitra_id', $mitra->id)
            ->whereDate('created_at', now()->toDateString())
            ->exists();
        
        // Cek apakah ada penarikan yang sedang diproses
        $hasProcessingWithdrawal = Withdrawal::where('mitra_id', $mitra->id)
            ->whereIn('status', ['pending', 'approved'])
            ->exists();
        
        return view('mitra.saldo.saldo', compact(
            'availableBalance',
            'totalEarnings', 
            'totalWithdrawn', 
            'pendingWithdrawals',
            'todayEarnings',
            'hasWithdrawnToday',
            'hasProcessingWithdrawal'
        ));
    }
    
    public function history(Request $request)
    {
        $mitra = auth()->user();
        
        // Get filter date from request, default to today
        $filterDate = $request->input('date') ? \Carbon\Carbon::parse($request->input('date')) : now();
        
        // Get all completed bookings for this mitra
        $bookings = Booking::where('mitra_id', $mitra->id)
            ->where('status', 'selesai')
            ->with('customer')
            ->orderBy('booking_date', 'desc')
            ->get();
        
        // Calculate stats for daily (filtered date)
        $todayBookings = $bookings->filter(function($b) use ($filterDate) {
            $bookingDate = \Carbon\Carbon::parse($b->booking_date);
            return $bookingDate->toDateString() == $filterDate->toDateString();
        });
        $todayRevenue = $todayBookings->sum('base_price');
        $todayCount = $todayBookings->count();
        
        // Calculate stats for monthly (current month)
        $monthStart = now()->startOfMonth();
        $monthEnd = now()->endOfMonth();
        $monthlyBookings = $bookings->filter(function($b) use ($monthStart, $monthEnd) {
            $bookingDate = \Carbon\Carbon::parse($b->booking_date);
            return $bookingDate->between($monthStart, $monthEnd);
        });
        $monthlyRevenue = $monthlyBookings->sum('base_price');
        $monthlyCount = $monthlyBookings->count();
        
        // Calculate stats for yearly (current year)
        $yearStart = now()->startOfYear();
        $yearEnd = now()->endOfYear();
        $yearlyBookings = $bookings->filter(function($b) use ($yearStart, $yearEnd) {
            $bookingDate = \Carbon\Carbon::parse($b->booking_date);
            return $bookingDate->between($yearStart, $yearEnd);
        });
        $yearlyRevenue = $yearlyBookings->sum('base_price');
        $yearlyCount = $yearlyBookings->count();
        
        // Get mitra registration date
        $mitraRegistrationDate = \Carbon\Carbon::parse($mitra->created_at);
        
        // Group by month for monthly view (from registration date to now)
        $monthlyData = Booking::where('mitra_id', $mitra->id)
            ->where('status', 'selesai')
            ->whereDate('booking_date', '>=', $mitraRegistrationDate->startOfMonth())
            ->select(
                DB::raw('DATE_FORMAT(booking_date, "%Y-%m") as month'),
                DB::raw('COUNT(*) as total_transactions'),
                DB::raw('SUM(base_price) as total_revenue')
            )
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->get();
        
        // Group by year for yearly view (from registration year to now)
        $yearlyData = Booking::where('mitra_id', $mitra->id)
            ->where('status', 'selesai')
            ->whereYear('booking_date', '>=', $mitraRegistrationDate->year)
            ->select(
                DB::raw('YEAR(booking_date) as year'),
                DB::raw('COUNT(*) as total_transactions'),
                DB::raw('SUM(base_price) as total_revenue')
            )
            ->groupBy('year')
            ->orderBy('year', 'desc')
            ->get();
        
        return view('mitra.saldo.history', compact(
            'todayBookings',
            'todayRevenue',
            'todayCount',
            'monthlyBookings',
            'monthlyRevenue',
            'monthlyCount',
            'yearlyBookings',
            'yearlyRevenue',
            'yearlyCount',
            'monthlyData',
            'yearlyData',
            'filterDate'
        ));
    }
    
    public function exportReport(Request $request)
    {
        $mitra = auth()->user();
        $type = $request->input('type', 'daily'); // daily, monthly, yearly
        $format = $request->input('format', 'csv'); // csv or pdf
        $filterDate = $request->input('date') ? \Carbon\Carbon::parse($request->input('date')) : now();
        
        // Get data based on type
        $bookings = Booking::where('mitra_id', $mitra->id)
            ->where('status', 'selesai')
            ->with('customer')
            ->orderBy('booking_date', 'desc')
            ->get();
        
        if ($type === 'daily') {
            $data = $bookings->filter(function($b) use ($filterDate) {
                $bookingDate = \Carbon\Carbon::parse($b->booking_date);
                return $bookingDate->toDateString() == $filterDate->toDateString();
            });
            $filename = 'laporan-harian-' . $filterDate->format('Y-m-d') . '.' . $format;
            $title = 'Laporan Keuangan Harian - ' . $filterDate->format('d/m/Y');
        } elseif ($type === 'monthly') {
            $mitraRegistrationDate = \Carbon\Carbon::parse($mitra->created_at);
            $data = Booking::where('mitra_id', $mitra->id)
                ->where('status', 'selesai')
                ->whereBetween('booking_date', [$mitraRegistrationDate, now()])
                ->select(
                    DB::raw('DATE_FORMAT(booking_date, "%Y-%m") as month'),
                    DB::raw('COUNT(*) as total_transactions'),
                    DB::raw('SUM(base_price) as total_revenue')
                )
                ->groupBy('month')
                ->orderBy('month', 'desc')
                ->get();
            $filename = 'laporan-bulanan.' . $format;
            $title = 'Laporan Keuangan Bulanan';
        } else { // yearly
            $mitraRegistrationDate = \Carbon\Carbon::parse($mitra->created_at);
            $data = Booking::where('mitra_id', $mitra->id)
                ->where('status', 'selesai')
                ->whereYear('booking_date', '>=', $mitraRegistrationDate->year)
                ->select(
                    DB::raw('YEAR(booking_date) as year'),
                    DB::raw('COUNT(*) as total_transactions'),
                    DB::raw('SUM(base_price) as total_revenue')
                )
                ->groupBy('year')
                ->orderBy('year', 'desc')
                ->get();
            $filename = 'laporan-tahunan.' . $format;
            $title = 'Laporan Keuangan Tahunan';
        }
        
        if ($format === 'csv') {
            return $this->exportToCSV($data, $type, $filename);
        } else {
            return $this->exportToPDF($data, $type, $title, $filename);
        }
    }
    
    private function exportToCSV($data, $type, $filename)
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($data, $type) {
            $file = fopen('php://output', 'w');
            
            if ($type === 'daily') {
                fputcsv($file, ['Jenis Layanan', 'Jenis Kendaraan', 'Plat Nomor', 'Sub Total']);
                foreach ($data as $booking) {
                    fputcsv($file, [
                        $booking->service_type,
                        $booking->vehicle_type,
                        $booking->vehicle_plate,
                        'Rp ' . number_format($booking->base_price, 0, ',', '.')
                    ]);
                }
            } elseif ($type === 'monthly') {
                fputcsv($file, ['Bulan', 'Total Transaksi', 'Total Pendapatan']);
                foreach ($data as $item) {
                    fputcsv($file, [
                        $item->month,
                        $item->total_transactions,
                        'Rp ' . number_format($item->total_revenue, 0, ',', '.')
                    ]);
                }
            } else { // yearly
                fputcsv($file, ['Tahun', 'Total Transaksi', 'Total Pendapatan']);
                foreach ($data as $item) {
                    fputcsv($file, [
                        $item->year,
                        $item->total_transactions,
                        'Rp ' . number_format($item->total_revenue, 0, ',', '.')
                    ]);
                }
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
    
    private function exportToPDF($data, $type, $title, $filename)
    {
        $pdf = PDF::loadView('mitra.saldo.export-pdf', compact('data', 'type', 'title'));
        return $pdf->download($filename);
    }
}
