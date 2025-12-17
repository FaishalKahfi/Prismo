<?php

namespace App\Http\Controllers\Mitra;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ProfilController extends Controller
{
    public function show()
    {
        $user = auth()->user();
        $mitraProfile = $user->mitraProfile;
        
        return view('mitra.profil.profil', compact('user', 'mitraProfile'));
    }

    public function edit()
    {
        $user = auth()->user();
        
        // Check if mitra profile exists
        if (!$user->mitraProfile) {
            return redirect('/mitra/form-mitra')->with('error', 'Silakan lengkapi profil Anda terlebih dahulu.');
        }
        
        $profile = $user->mitraProfile;
        
        // Debug: Log data types
        \Log::info('Edit Profile Debug', [
            'facility_photos_type' => gettype($profile->facility_photos),
            'facility_photos_value' => $profile->facility_photos,
            'custom_services_type' => gettype($profile->custom_services),
            'custom_services_value' => $profile->custom_services,
        ]);
        
        return view('mitra.profil.edit-profile', compact('user', 'profile'));
    }

    public function update(Request $request)
    {
        try {
            $user = auth()->user();
            $profile = $user->mitraProfile;

            if (!$profile) {
                return response()->json(['success' => false, 'message' => 'Profil tidak ditemukan'], 404);
            }

            // Validasi
            $validated = $request->validate([
                'business_name' => 'required|string|max:255',
                'establishment_year' => 'required|integer|min:1900|max:' . date('Y'),
                'address' => 'required|string|max:500',
                'province' => 'required|string|max:100',
                'city' => 'required|string|max:100',
                'postal_code' => 'required|string|max:10',
                'map_location' => 'required|url',
                'contact_person' => 'required|string|max:255',
                'phone' => 'required|string|max:20',
                'facility_photos.*' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
                'delete_photos' => 'nullable|array',
            ]);

            // Update basic info
            $profile->business_name = $validated['business_name'];
            $profile->establishment_year = $validated['establishment_year'];
            $profile->address = $validated['address'];
            $profile->province = $validated['province'];
            $profile->city = $validated['city'];
            $profile->postal_code = $validated['postal_code'];
            $profile->map_location = $validated['map_location'];
            $profile->contact_person = $validated['contact_person'];
            $profile->phone = $validated['phone'];

            // Handle facility photos
            $existingPhotos = is_array($profile->facility_photos) 
                ? $profile->facility_photos 
                : [];
            $existingPhotos = is_array($existingPhotos) ? $existingPhotos : [];

            // Delete photos if requested
            if ($request->has('delete_photos')) {
                foreach ($request->delete_photos as $photoPath) {
                    // Remove from storage
                    if (Storage::disk('public')->exists($photoPath)) {
                        Storage::disk('public')->delete($photoPath);
                    }
                    
                    // Remove from array
                    $existingPhotos = array_filter($existingPhotos, function($photo) use ($photoPath) {
                        return $photo !== $photoPath;
                    });
                }
                $existingPhotos = array_values($existingPhotos); // Re-index array
            }

            // Upload new photos
            if ($request->hasFile('facility_photos')) {
                $newPhotosCount = count($request->file('facility_photos'));
                $currentPhotosCount = count($existingPhotos);
                $maxPhotos = 5;
                
                // Check total photos limit
                if ($currentPhotosCount + $newPhotosCount > $maxPhotos) {
                    return response()->json([
                        'success' => false,
                        'message' => "Maksimal {$maxPhotos} foto. Anda sudah memiliki {$currentPhotosCount} foto."
                    ], 422);
                }
                
                foreach ($request->file('facility_photos') as $photo) {
                    $path = $photo->store('mitra/facilities', 'public');
                    $existingPhotos[] = $path;
                }
            }

            // Save photos array
            $profile->facility_photos = $existingPhotos;
            $profile->save();

            return response()->json([
                'success' => true, 
                'message' => 'Profil berhasil diperbarui'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Update profile error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
