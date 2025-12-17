<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerDashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // Total booking user ini
        $totalBooking = Booking::where('customer_id', $user->id)->count();

        // Booking aktif (paid, processing)
        $activeBookings = Booking::where('customer_id', $user->id)
            ->whereIn('status', ['paid', 'processing'])
            ->count();

        // Booking completed
        $completedBookings = Booking::where('customer_id', $user->id)
            ->where('status', 'completed')
            ->count();

        // Points/vouchers (jika ada)
        $totalPoints = $user->points ?? 0;

        // Get all approved mitra for display with filters
        $query = User::where('role', 'mitra')
            ->where('approval_status', 'approved')
            ->with('mitraProfile')
            ->whereHas('mitraProfile');

        // Filter by province if provided
        if ($request->has('provinsi') && $request->provinsi) {
            $query->whereHas('mitraProfile', function($q) use ($request) {
                $q->where('province', $request->provinsi);
            });
        }

        // Filter by city if provided
        if ($request->has('kota') && $request->kota) {
            $query->whereHas('mitraProfile', function($q) use ($request) {
                $q->where('city', $request->kota);
            });
        }

        // Search by business name if provided
        if ($request->has('search') && $request->search) {
            $searchTerm = $request->search;
            $query->whereHas('mitraProfile', function($q) use ($searchTerm) {
                $q->where('business_name', 'like', '%' . $searchTerm . '%');
            });
        }

        $mitras = $query->get()
            ->map(function ($mitra) use ($totalBooking) {
                $profile = $mitra->mitraProfile;

                if (!$profile) {
                    return null;
                }

                // Count completed bookings for this mitra
                $completedBookingsCount = \App\Models\Booking::where('mitra_id', $mitra->id)
                    ->where('status', 'selesai')
                    ->count();

                // Calculate rating and review count from reviews table
                $reviews = \App\Models\Review::where('mitra_id', $mitra->id)->get();
                $reviewCount = $reviews->count();
                $averageRating = $reviewCount > 0 ? round($reviews->avg('rating'), 1) : 0;

                // Get first facility photo (required from registration)
                $facilityPhotos = $profile->facility_photos;
                if (is_string($facilityPhotos)) {
                    $facilityPhotos = json_decode($facilityPhotos, true);
                }
                $facilityPhotos = is_array($facilityPhotos) ? $facilityPhotos : [];

                // Use facility photo with full path
                $image = !empty($facilityPhotos) && isset($facilityPhotos[0])
                    ? (str_starts_with($facilityPhotos[0], 'mitra/')
                        ? '/storage/' . $facilityPhotos[0]
                        : $facilityPhotos[0])
                    : '/images/logo.png';

                // Get custom services from database (already an array from cast)
                $customServices = is_array($profile->custom_services) ? $profile->custom_services : [];

                // Build prices object from custom services
                $prices = [];
                foreach ($customServices as $service) {
                    $prices[$service['name']] = (float) ($service['price'] ?? 0);
                }

                return [
                    'id' => $mitra->id,
                    'name' => $profile->business_name ?? 'Mitra',
                    'location' => $profile->address ?? '',
                    'kota' => $profile->city ?? '',
                    'provinsi' => $profile->province ?? '',
                    'rating' => $averageRating,
                    'reviews' => $reviewCount,
                    'completed_bookings' => $completedBookingsCount,
                    'status' => $profile->is_open ? 'open' : 'closed',
                    'image' => $image,
                    'services' => $customServices, // Full service data
                    'prices' => $prices // Service name => price mapping
                ];
            })
            ->filter(); // Remove null entries

        return view('customer.dashboard.dashU', compact(
            'totalBooking',
            'activeBookings',
            'completedBookings',
            'totalPoints',
            'mitras'
        ));
    }
}
