<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>Edit Profil - Prismo</title>
    <link rel="icon" type="image/png" href="{{ asset('images/Icon-prismo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/Icon-prismo.png') }}">
    <link rel="stylesheet" href="{{ asset('css/eprofil.css') }}?v={{ time() }}">
    <!-- Phosphor Icons -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <header class="header">
            <div class="logo">
                <img src="{{ asset('images/logo.png') }}" alt="Prismo">
            </div>
            <button class="back-btn" id="backBtn" onclick="window.location.href='{{ url('/customer/profil/uprofil') }}'" style="cursor: pointer;">
                <i class="ph ph-arrow-left"></i>
                Kembali
            </button>
        </header>

        <!-- Main Content -->
        <main class="main-content">
            <div class="edit-profile-card">
                <h2 class="page-title">Edit Profil</h2>

                @php
                    $user = auth()->user();
                    $profile = $user->customerProfile;
                @endphp

                <form class="edit-form" id="editForm">
                    <div class="form-group">
                        <label for="fullName">Nama Lengkap</label>
                        <input
                            type="text"
                            id="fullName"
                            name="fullName"
                            value="{{ $user->name }}"
                            placeholder="Nama Lengkap"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="phone">No Telepon/WhatsApp</label>
                        <input
                            type="tel"
                            id="phone"
                            name="phone"
                            value="{{ $user->phone ?? $profile->phone ?? '' }}"
                            placeholder="0123456789"
                        >
                    </div>

                    <div class="form-group">
                        <label for="address">Alamat Lengkap</label>
                        <textarea
                            id="address"
                            name="address"
                            placeholder="Masukkan alamat lengkap Anda"
                            rows="3"
                        >{{ $profile->address ?? '' }}</textarea>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="province">Provinsi</label>
                            <select id="province" name="province">
                                <option value="">Pilih Provinsi</option>
                                <option value="DKI Jakarta" {{ ($profile->province ?? '') == 'DKI Jakarta' ? 'selected' : '' }}>DKI Jakarta</option>
                                <option value="Jawa Barat" {{ ($profile->province ?? '') == 'Jawa Barat' ? 'selected' : '' }}>Jawa Barat</option>
                                <option value="Jawa Tengah" {{ ($profile->province ?? '') == 'Jawa Tengah' ? 'selected' : '' }}>Jawa Tengah</option>
                                <option value="DI Yogyakarta" {{ ($profile->province ?? '') == 'DI Yogyakarta' ? 'selected' : '' }}>DI Yogyakarta</option>
                                <option value="Jawa Timur" {{ ($profile->province ?? '') == 'Jawa Timur' ? 'selected' : '' }}>Jawa Timur</option>
                                <option value="Banten" {{ ($profile->province ?? '') == 'Banten' ? 'selected' : '' }}>Banten</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="city">Kota/Kabupaten</label>
                            <select id="city" name="city" data-selected="{{ $profile->city ?? '' }}" disabled>
                                <option value="">Pilih Provinsi Terlebih Dahulu</option>
                                @if($profile->city ?? '')
                                    <option value="{{ $profile->city }}" selected>{{ $profile->city }}</option>
                                @endif
                            </select>
                        </div>
                    </div>

                    <button type="submit" class="submit-btn" id="submitBtn">
                        <span class="btn-text">Konfirmasi Edit Profil</span>
                        <span class="btn-loader"></span>
                    </button>
                </form>
            </div>
        </main>
    </div>

    <!-- Success Modal -->
    <div class="modal" id="successModal">
        <div class="modal-content" style="max-width: 300px; text-align: center;">
            <div class="success-icon" style="font-size: 80px; color: #4caf50; margin-bottom: 20px;">
                <i class="ph-fill ph-check-circle"></i>
            </div>
            <button class="ok-btn" id="okBtn" style="margin-top: 20px;">OK</button>
        </div>
    </div>

    <script src="{{ asset('js/eprofil.js') }}?v={{ time() }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Data kota di Pulau Jawa saja
            const cityData = {
                'DKI Jakarta': ['Jakarta Barat', 'Jakarta Pusat', 'Jakarta Selatan', 'Jakarta Timur', 'Jakarta Utara', 'Kepulauan Seribu'],
                'Jawa Barat': ['Bandung', 'Banjar', 'Bekasi', 'Bogor', 'Cimahi', 'Cirebon', 'Depok', 'Sukabumi', 'Tasikmalaya', 'Bandung Barat', 'Ciamis', 'Cianjur', 'Garut', 'Indramayu', 'Karawang', 'Kuningan', 'Majalengka', 'Pangandaran', 'Purwakarta', 'Subang', 'Sumedang', 'Kabupaten Bandung', 'Kabupaten Bekasi', 'Kabupaten Bogor', 'Kabupaten Cirebon', 'Kabupaten Sukabumi', 'Kabupaten Tasikmalaya'],
                'Jawa Tengah': ['Magelang', 'Pekalongan', 'Salatiga', 'Semarang', 'Surakarta', 'Tegal', 'Banjarnegara', 'Banyumas', 'Batang', 'Blora', 'Boyolali', 'Brebes', 'Cilacap', 'Demak', 'Grobogan', 'Jepara', 'Karanganyar', 'Kebumen', 'Kendal', 'Klaten', 'Kudus', 'Pati', 'Pemalang', 'Purbalingga', 'Purworejo', 'Rembang', 'Sragen', 'Sukoharjo', 'Temanggung', 'Wonogiri', 'Wonosobo', 'Kabupaten Magelang', 'Kabupaten Pekalongan', 'Kabupaten Semarang', 'Kabupaten Tegal'],
                'DI Yogyakarta': ['Yogyakarta', 'Bantul', 'Gunung Kidul', 'Kulon Progo', 'Sleman'],
                'Jawa Timur': ['Batu', 'Blitar', 'Kediri', 'Madiun', 'Malang', 'Mojokerto', 'Pasuruan', 'Probolinggo', 'Surabaya', 'Bangkalan', 'Banyuwangi', 'Bojonegoro', 'Bondowoso', 'Gresik', 'Jember', 'Jombang', 'Lamongan', 'Lumajang', 'Magetan', 'Nganjuk', 'Ngawi', 'Pacitan', 'Pamekasan', 'Ponorogo', 'Sampang', 'Sidoarjo', 'Situbondo', 'Sumenep', 'Trenggalek', 'Tuban', 'Tulungagung', 'Kabupaten Blitar', 'Kabupaten Kediri', 'Kabupaten Madiun', 'Kabupaten Malang', 'Kabupaten Mojokerto', 'Kabupaten Pasuruan', 'Kabupaten Probolinggo'],
                'Banten': ['Cilegon', 'Serang', 'Tangerang', 'Tangerang Selatan', 'Lebak', 'Pandeglang', 'Kabupaten Serang', 'Kabupaten Tangerang']
            };

            // Event listener untuk provinsi
            document.getElementById('province').addEventListener('change', function() {
                const provinceValue = this.value;
                const citySelect = document.getElementById('city');

                // Reset city dropdown
                citySelect.innerHTML = '<option value="">Pilih Kota/Kabupaten</option>';
                citySelect.disabled = true;

                // Populate city jika provinsi dipilih
                if (provinceValue && cityData[provinceValue]) {
                    const cities = cityData[provinceValue];
                    cities.forEach(city => {
                        const option = document.createElement('option');
                        option.value = city;
                        option.textContent = city;
                        citySelect.appendChild(option);
                    });
                    citySelect.disabled = false;
                }
            });

            // Initialize: Jika ada nilai existing, populate dropdowns
            const provinceSelect = document.getElementById('province');
            const citySelect = document.getElementById('city');

            if (provinceSelect.value) {
                // Trigger province change to populate cities
                const event = new Event('change');
                provinceSelect.dispatchEvent(event);

                // Wait for cities to populate, then set city value
                setTimeout(() => {
                    const savedCity = citySelect.getAttribute('data-selected');
                    if (savedCity) {
                        citySelect.value = savedCity;
                    }
                }, 100);
            }
        });
    </script>
    <script>
        // Broadcast avatar update untuk sinkronisasi cross-tab
        const form = document.querySelector('form');
        if (form) {
            form.addEventListener('submit', function() {
                setTimeout(() => {
                    if (typeof BroadcastChannel !== 'undefined') {
                        const channel = new BroadcastChannel('profile_update');
                        const avatarInput = document.querySelector('input[name="avatar"]');
                        if (avatarInput && avatarInput.files.length > 0) {
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                channel.postMessage({
                                    type: 'avatar_updated',
                                    avatar: e.target.result
                                });
                            };
                            reader.readAsDataURL(avatarInput.files[0]);
                        }
                    }
                }, 500);
            });
        }
    </script>
</body>
</html>
