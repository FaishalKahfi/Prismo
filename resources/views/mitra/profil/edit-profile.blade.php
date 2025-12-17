@php
    $user = Auth::user();
    $profile = $user->mitraProfile ?? null;

    // Redirect jika profile belum ada
    if (!$profile) {
        redirect('/mitra/form-mitra')->send();
        exit;
    }
@endphp

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Edit Profile Bisnis</title>
    <link rel="icon" type="image/png" href="{{ asset('images/Icon-prismo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/Icon-prismo.png') }}">
    <link rel="stylesheet" href="{{ asset('css/form-mitra.css') }}">
</head>

<body>
    <div class="container">
        <header>
            <h1>Edit Profile Bisnis</h1>
        </header>

        <form id="editProfileForm">
            <div class="form-container">
                <!-- Kolom Kiri -->
                <div class="form-column left-column">
                    <!-- Informasi Bisnis -->
                    <section class="form-section">
                        <h2>Informasi Bisnis</h2>

                        <div class="form-group">
                            <label for="businessName" class="required">Nama Bisnis</label>
                            <input type="text" id="businessName" name="businessName" placeholder="Prismo" value="{{ $profile->business_name ?? '' }}" required>
                        </div>

                        <div class="form-group">
                            <label for="establishmentYear" class="required">Tahun Berdiri</label>
                            <input type="number" id="establishmentYear" name="establishmentYear" min="1900" max="2025"
                                placeholder="2025" value="{{ $profile->establishment_year ?? '' }}" required>
                        </div>

                        <div class="form-group">
                            <label for="address" class="required">Alamat Lengkap</label>
                            <input type="text" id="address" name="address"
                                placeholder="Jl. Cikeas No. 123, Bogor Timur" value="{{ $profile->address ?? '' }}" required>
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="province" class="required" id="provinceLabel">Provinsi</label>
                                    <select id="province" name="province" class="form-control" required>
                                        <option value="">Pilih Provinsi</option>
                                        <option value="DKI Jakarta" {{ ($profile->province ?? '') == 'DKI Jakarta' ? 'selected' : '' }}>DKI Jakarta</option>
                                        <option value="Jawa Barat" {{ ($profile->province ?? '') == 'Jawa Barat' ? 'selected' : '' }}>Jawa Barat</option>
                                        <option value="Jawa Tengah" {{ ($profile->province ?? '') == 'Jawa Tengah' ? 'selected' : '' }}>Jawa Tengah</option>
                                        <option value="Jawa Timur" {{ ($profile->province ?? '') == 'Jawa Timur' ? 'selected' : '' }}>Jawa Timur</option>
                                        <option value="Banten" {{ ($profile->province ?? '') == 'Banten' ? 'selected' : '' }}>Banten</option>
                                        <option value="Yogyakarta" {{ ($profile->province ?? '') == 'Yogyakarta' ? 'selected' : '' }}>Yogyakarta</option>
                                        <option value="Bali" {{ ($profile->province ?? '') == 'Bali' ? 'selected' : '' }}>Bali</option>
                                        <option value="Aceh" {{ ($profile->province ?? '') == 'Aceh' ? 'selected' : '' }}>Aceh</option>
                                        <option value="Sumatera Utara" {{ ($profile->province ?? '') == 'Sumatera Utara' ? 'selected' : '' }}>Sumatera Utara</option>
                                        <option value="Sumatera Barat" {{ ($profile->province ?? '') == 'Sumatera Barat' ? 'selected' : '' }}>Sumatera Barat</option>
                                        <option value="Sumatera Selatan" {{ ($profile->province ?? '') == 'Sumatera Selatan' ? 'selected' : '' }}>Sumatera Selatan</option>
                                        <option value="Riau" {{ ($profile->province ?? '') == 'Riau' ? 'selected' : '' }}>Riau</option>
                                        <option value="Kepulauan Riau" {{ ($profile->province ?? '') == 'Kepulauan Riau' ? 'selected' : '' }}>Kepulauan Riau</option>
                                        <option value="Jambi" {{ ($profile->province ?? '') == 'Jambi' ? 'selected' : '' }}>Jambi</option>
                                        <option value="Lampung" {{ ($profile->province ?? '') == 'Lampung' ? 'selected' : '' }}>Lampung</option>
                                        <option value="Bengkulu" {{ ($profile->province ?? '') == 'Bengkulu' ? 'selected' : '' }}>Bengkulu</option>
                                        <option value="Bangka Belitung" {{ ($profile->province ?? '') == 'Bangka Belitung' ? 'selected' : '' }}>Bangka Belitung</option>
                                        <option value="Kalimantan Barat" {{ ($profile->province ?? '') == 'Kalimantan Barat' ? 'selected' : '' }}>Kalimantan Barat</option>
                                        <option value="Kalimantan Tengah" {{ ($profile->province ?? '') == 'Kalimantan Tengah' ? 'selected' : '' }}>Kalimantan Tengah</option>
                                        <option value="Kalimantan Selatan" {{ ($profile->province ?? '') == 'Kalimantan Selatan' ? 'selected' : '' }}>Kalimantan Selatan</option>
                                        <option value="Kalimantan Timur" {{ ($profile->province ?? '') == 'Kalimantan Timur' ? 'selected' : '' }}>Kalimantan Timur</option>
                                        <option value="Kalimantan Utara" {{ ($profile->province ?? '') == 'Kalimantan Utara' ? 'selected' : '' }}>Kalimantan Utara</option>
                                        <option value="Sulawesi Utara" {{ ($profile->province ?? '') == 'Sulawesi Utara' ? 'selected' : '' }}>Sulawesi Utara</option>
                                        <option value="Sulawesi Tengah" {{ ($profile->province ?? '') == 'Sulawesi Tengah' ? 'selected' : '' }}>Sulawesi Tengah</option>
                                        <option value="Sulawesi Selatan" {{ ($profile->province ?? '') == 'Sulawesi Selatan' ? 'selected' : '' }}>Sulawesi Selatan</option>
                                        <option value="Sulawesi Tenggara" {{ ($profile->province ?? '') == 'Sulawesi Tenggara' ? 'selected' : '' }}>Sulawesi Tenggara</option>
                                        <option value="Sulawesi Barat" {{ ($profile->province ?? '') == 'Sulawesi Barat' ? 'selected' : '' }}>Sulawesi Barat</option>
                                        <option value="Gorontalo" {{ ($profile->province ?? '') == 'Gorontalo' ? 'selected' : '' }}>Gorontalo</option>
                                        <option value="Maluku" {{ ($profile->province ?? '') == 'Maluku' ? 'selected' : '' }}>Maluku</option>
                                        <option value="Maluku Utara" {{ ($profile->province ?? '') == 'Maluku Utara' ? 'selected' : '' }}>Maluku Utara</option>
                                        <option value="Papua" {{ ($profile->province ?? '') == 'Papua' ? 'selected' : '' }}>Papua</option>
                                        <option value="Papua Barat" {{ ($profile->province ?? '') == 'Papua Barat' ? 'selected' : '' }}>Papua Barat</option>
                                        <option value="Papua Tengah" {{ ($profile->province ?? '') == 'Papua Tengah' ? 'selected' : '' }}>Papua Tengah</option>
                                        <option value="Papua Pegunungan" {{ ($profile->province ?? '') == 'Papua Pegunungan' ? 'selected' : '' }}>Papua Pegunungan</option>
                                        <option value="Papua Selatan" {{ ($profile->province ?? '') == 'Papua Selatan' ? 'selected' : '' }}>Papua Selatan</option>
                                        <option value="Papua Barat Daya" {{ ($profile->province ?? '') == 'Papua Barat Daya' ? 'selected' : '' }}>Papua Barat Daya</option>
                                        <option value="Nusa Tenggara Barat" {{ ($profile->province ?? '') == 'Nusa Tenggara Barat' ? 'selected' : '' }}>Nusa Tenggara Barat</option>
                                        <option value="Nusa Tenggara Timur" {{ ($profile->province ?? '') == 'Nusa Tenggara Timur' ? 'selected' : '' }}>Nusa Tenggara Timur</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="city" class="required" id="cityLabel">Kota/Kabupaten</label>
                                    <select id="city" name="city" class="form-control" data-selected="{{ $profile->city ?? '' }}" required disabled>
                                        <option value="">Pilih Provinsi Terlebih Dahulu</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="postalCode" class="required" id="postalCodeLabel">Kode Pos</label>
                                    <select id="postalCode" name="postalCode" class="form-control" data-selected="{{ $profile->postal_code ?? '' }}" required disabled>
                                        <option value="">Pilih Kota Terlebih Dahulu</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="mapLocation" class="required">Lokasi di Peta</label>
                                    <input type="url" id="mapLocation" name="mapLocation"
                                        placeholder="https://www.google.com/maps/place/" value="{{ $profile->map_location ?? '' }}" required>
                                </div>
                            </div>
                        </div>
                    </section>
                    <!-- Informasi Kontak -->
                    <section class="form-section">
                        <h2>Informasi Kontak</h2>

                        <div class="form-group">
                            <label for="contactPerson" class="required">Nama Penanggung Jawab</label>
                            <input type="text" id="contactPerson" name="contactPerson" placeholder="Sari Dewi" value="{{ $profile->contact_person ?? '' }}" required>
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="email" class="required">Email</label>
                                    <input type="email" id="email" name="email" placeholder="sari@quickclean.com" value="{{ Auth::user()->email }}" readonly style="background-color: #f5f5f5; cursor: not-allowed;" required>
                                    <small style="color: #666; font-size: 12px; display: block; margin-top: 5px;">Email tidak dapat diubah</small>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="phone" class="required">Nomor WhatsApp/Telepon</label>
                                    <input type="tel" id="phone" name="phone" placeholder="08123456789" value="{{ $profile->phone ?? '' }}" required>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>

                <!-- Kolom Kanan -->
                <div class="form-column right-column">
                    <!-- Fasilitas & Dokumentasi -->
                    <section class="form-section">
                        <h2>Fasilitas & Layanan</h2>

                        <!-- Foto Fasilitas -->
                        <div class="form-group" id="facilityGroup">
                            <label class="required">Foto Fasilitas (Maksimal 5 foto)</label>
                            <div class="upload-card" id="facilityUpload">
                                <div class="upload-content">
                                    <div class="upload-icon">
                                        <img src="{{ asset('images/fasilitas.png') }}" alt="Fasilitas">
                                    </div>
                                    <div class="upload-text">
                                        <p class="upload-title">Klik untuk upload foto fasilitas</p>
                                        <p class="upload-subtitle">JPG atau PNG maksimal 5MB per foto</p>
                                    </div>
                                </div>
                            </div>
                            <div id="facilityPreview" class="upload-preview">
                                <!-- Preview akan muncul di sini setelah upload -->
                            </div>

                            <!-- Foto Existing -->
                            @if($profile && $profile->facility_photos)
                                @php
                                    $photos = is_array($profile->facility_photos)
                                        ? $profile->facility_photos
                                        : [];
                                @endphp
                                @if(count($photos) > 0)
                                    <div class="existing-photos-section" style="margin-top: 20px;">
                                        <label style="display: block; margin-bottom: 10px; font-weight: 500; color: #333;">Foto Fasilitas yang Sudah Ada:</label>
                                        <div class="existing-photos-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(120px, 1fr)); gap: 15px;">
                                            @foreach($photos as $index => $photo)
                                                <div class="existing-photo-item" data-photo="{{ $photo }}" style="position: relative; border: 2px solid #e0e0e0; border-radius: 8px; overflow: hidden; background: #f9f9f9;">
                                                    <img src="{{ asset('storage/' . $photo) }}" alt="Foto {{ $index + 1 }}"
                                                         style="width: 100%; height: 120px; object-fit: cover; cursor: pointer; display: block;"
                                                         onclick="previewExistingPhoto('{{ asset('storage/' . $photo) }}', {{ $index + 1 }})">
                                                    <button type="button" class="remove-existing-photo" onclick="removeExistingFacilityPhoto(this, '{{ $photo }}')"
                                                            style="position: absolute; top: 5px; right: 5px; width: 24px; height: 24px; border-radius: 50%; background: rgba(255, 59, 48, 0.95); color: white; border: none; font-size: 18px; line-height: 1; cursor: pointer; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 4px rgba(0,0,0,0.2);">×</button>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            @endif
                        </div>
                    </section>
                </div>
            </div>

            <!-- Bagian Tombol Batal dan Simpan -->
            <div class="button-section">
                <button type="button" class="btn-logout" onclick="window.history.back()">Batal</button>
                <button type="submit" class="btn-submit">Simpan Perubahan</button>
            </div>
        </form>
    </div>

    <!-- Modal Preview Foto -->
    <div id="photoPreviewModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.9); z-index: 9999; align-items: center; justify-content: center;">
        <div style="position: relative; max-width: 90%; max-height: 90%; background: white; border-radius: 12px; overflow: hidden;">
            <button onclick="closePhotoPreview()" style="position: absolute; top: 15px; right: 15px; width: 40px; height: 40px; border-radius: 50%; background: rgba(255,255,255,0.9); border: none; font-size: 24px; cursor: pointer; z-index: 10; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 8px rgba(0,0,0,0.3);">×</button>
            <img id="previewModalImage" src="" alt="Preview" style="max-width: 100%; max-height: 90vh; display: block;">
            <div style="padding: 15px; background: white; text-align: center;">
                <strong id="previewModalTitle">Foto Fasilitas</strong>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/edit-profile.js') }}?v={{ time() }}"></script>
    <script>
        // Array untuk menyimpan foto yang akan dihapus
        let photosToDelete = [];

        function previewExistingPhoto(photoUrl, index) {
            const modal = document.getElementById('photoPreviewModal');
            const img = document.getElementById('previewModalImage');
            const title = document.getElementById('previewModalTitle');

            img.src = photoUrl;
            title.textContent = 'Foto Fasilitas ' + index;
            modal.style.display = 'flex';
        }

        function closePhotoPreview() {
            const modal = document.getElementById('photoPreviewModal');
            modal.style.display = 'none';
        }

        function removeExistingFacilityPhoto(button, photoPath) {
            if (confirm('Apakah Anda yakin ingin menghapus foto ini?')) {
                const photoItem = button.closest('.existing-photo-item');
                photoItem.style.opacity = '0.5';
                photoItem.style.pointerEvents = 'none';

                // Tambahkan ke array foto yang akan dihapus
                photosToDelete.push(photoPath);

                // Buat input hidden untuk mengirim data foto yang dihapus
                const form = document.querySelector('form');
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'delete_photos[]';
                input.value = photoPath;
                form.appendChild(input);

                // Hapus elemen visual setelah animasi
                setTimeout(() => {
                    photoItem.remove();

                    // Cek apakah masih ada foto
                    const remainingPhotos = document.querySelectorAll('.existing-photo-item');
                    if (remainingPhotos.length === 0) {
                        document.querySelector('.existing-photos-section')?.remove();
                    }

                    // Update visibility upload card
                    if (typeof updateFacilityUploadVisibility === 'function') {
                        updateFacilityUploadVisibility();
                    }
                }, 300);
            }
        }

        // Close modal saat klik di luar gambar
        document.getElementById('photoPreviewModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closePhotoPreview();
            }
        });

        // Close modal dengan tombol Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closePhotoPreview();
            }
        });
    </script>
</body>

</html>
