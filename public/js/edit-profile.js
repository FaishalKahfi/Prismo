document.addEventListener('DOMContentLoaded', function() {
    // Upload foto fasilitas
    let facilityFiles = [];
    const maxFacilityPhotos = 5;
    
    // Create hidden file input for facility photos
    const facilityInput = document.createElement('input');
    facilityInput.type = 'file';
    facilityInput.accept = 'image/jpeg,image/png,image/jpg';
    facilityInput.multiple = true;
    facilityInput.style.display = 'none';
    document.body.appendChild(facilityInput);
    
    // Click upload card to trigger file selection
    const facilityUpload = document.getElementById('facilityUpload');
    if (facilityUpload) {
        facilityUpload.addEventListener('click', function() {
            facilityInput.click();
        });
    }
    
    // Handle file selection
    facilityInput.addEventListener('change', function(e) {
        const files = Array.from(e.target.files);
        const existingCount = document.querySelectorAll('.existing-photo-item').length;
        const currentPreviewCount = document.querySelectorAll('#facilityPreview .preview-item').length;
        const totalCount = existingCount + currentPreviewCount + files.length;
        
        if (totalCount > maxFacilityPhotos) {
            alert(`Maksimal ${maxFacilityPhotos} foto. Anda sudah memiliki ${existingCount + currentPreviewCount} foto.`);
            return;
        }
        
        files.forEach(file => {
            if (file.size > 5 * 1024 * 1024) {
                alert(`File ${file.name} terlalu besar. Maksimal 5MB per foto.`);
                return;
            }
            
            facilityFiles.push(file);
            
            // Create preview
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('facilityPreview');
                const previewItem = document.createElement('div');
                previewItem.className = 'preview-item';
                previewItem.innerHTML = `
                    <img src="${e.target.result}" alt="Preview">
                    <button type="button" class="remove-preview" onclick="removeFacilityPreview(this, '${file.name}')">×</button>
                `;
                preview.appendChild(previewItem);
                
                updateFacilityUploadVisibility();
            };
            reader.readAsDataURL(file);
        });
        
        // Reset input
        facilityInput.value = '';
    });
    
    // Function to update upload card visibility
    window.updateFacilityUploadVisibility = function() {
        const existingCount = document.querySelectorAll('.existing-photo-item').length;
        const previewCount = document.querySelectorAll('#facilityPreview .preview-item').length;
        const totalCount = existingCount + previewCount;
        
        const uploadCard = document.getElementById('facilityUpload');
        if (uploadCard) {
            if (totalCount >= maxFacilityPhotos) {
                uploadCard.style.display = 'none';
            } else {
                uploadCard.style.display = 'flex';
            }
        }
    };
    
    // Function to remove preview
    window.removeFacilityPreview = function(button, fileName) {
        if (confirm('Hapus foto ini?')) {
            // Remove from files array
            const index = facilityFiles.findIndex(f => f.name === fileName);
            if (index > -1) {
                facilityFiles.splice(index, 1);
            }
            
            // Remove preview element
            button.closest('.preview-item').remove();
            
            updateFacilityUploadVisibility();
        }
    };
    
    // Initial check
    updateFacilityUploadVisibility();

    // Data provinsi ke kota (sama dengan form-mitra)
    const cityData = {
        'Aceh': ['Banda Aceh', 'Langsa', 'Lhokseumawe', 'Sabang', 'Subulussalam', 'Aceh Barat', 'Aceh Barat Daya', 'Aceh Besar', 'Aceh Jaya', 'Aceh Selatan', 'Aceh Singkil', 'Aceh Tamiang', 'Aceh Tengah', 'Aceh Tenggara', 'Aceh Timur', 'Aceh Utara', 'Bener Meriah', 'Bireuen', 'Gayo Lues', 'Nagan Raya', 'Pidie', 'Pidie Jaya', 'Simeulue'],
        'Bali': ['Denpasar', 'Badung', 'Bangli', 'Buleleng', 'Gianyar', 'Jembrana', 'Karangasem', 'Klungkung', 'Tabanan'],
        'Banten': ['Cilegon', 'Serang', 'Tangerang', 'Tangerang Selatan', 'Lebak', 'Pandeglang', 'Kabupaten Serang', 'Kabupaten Tangerang'],
        'Bengkulu': ['Bengkulu', 'Bengkulu Selatan', 'Bengkulu Tengah', 'Bengkulu Utara', 'Kaur', 'Kepahiang', 'Lebong', 'Mukomuko', 'Rejang Lebong', 'Seluma'],
        'Yogyakarta': ['Yogyakarta', 'Bantul', 'Gunung Kidul', 'Kulon Progo', 'Sleman'],
        'DKI Jakarta': ['Jakarta Barat', 'Jakarta Pusat', 'Jakarta Selatan', 'Jakarta Timur', 'Jakarta Utara', 'Kepulauan Seribu'],
        'Gorontalo': ['Gorontalo', 'Boalemo', 'Bone Bolango', 'Gorontalo Utara', 'Pohuwato'],
        'Jambi': ['Jambi', 'Sungai Penuh', 'Batang Hari', 'Bungo', 'Kerinci', 'Merangin', 'Muaro Jambi', 'Sarolangun', 'Tanjung Jabung Barat', 'Tanjung Jabung Timur', 'Tebo'],
        'Jawa Barat': ['Bandung', 'Banjar', 'Bekasi', 'Bogor', 'Cimahi', 'Cirebon', 'Depok', 'Sukabumi', 'Tasikmalaya', 'Bandung Barat', 'Ciamis', 'Cianjur', 'Garut', 'Indramayu', 'Karawang', 'Kuningan', 'Majalengka', 'Pangandaran', 'Purwakarta', 'Subang', 'Sumedang', 'Kabupaten Bandung', 'Kabupaten Bekasi', 'Kabupaten Bogor', 'Kabupaten Cirebon', 'Kabupaten Sukabumi', 'Kabupaten Tasikmalaya'],
        'Jawa Tengah': ['Magelang', 'Pekalongan', 'Salatiga', 'Semarang', 'Surakarta', 'Tegal', 'Banjarnegara', 'Banyumas', 'Batang', 'Blora', 'Boyolali', 'Brebes', 'Cilacap', 'Demak', 'Grobogan', 'Jepara', 'Karanganyar', 'Kebumen', 'Kendal', 'Klaten', 'Kudus', 'Pati', 'Pemalang', 'Purbalingga', 'Purworejo', 'Rembang', 'Sragen', 'Sukoharjo', 'Temanggung', 'Wonogiri', 'Wonosobo', 'Kabupaten Magelang', 'Kabupaten Pekalongan', 'Kabupaten Semarang', 'Kabupaten Tegal'],
        'Jawa Timur': ['Batu', 'Blitar', 'Kediri', 'Madiun', 'Malang', 'Mojokerto', 'Pasuruan', 'Probolinggo', 'Surabaya', 'Bangkalan', 'Banyuwangi', 'Bojonegoro', 'Bondowoso', 'Gresik', 'Jember', 'Jombang', 'Lamongan', 'Lumajang', 'Magetan', 'Nganjuk', 'Ngawi', 'Pacitan', 'Pamekasan', 'Ponorogo', 'Sampang', 'Sidoarjo', 'Situbondo', 'Sumenep', 'Trenggalek', 'Tuban', 'Tulungagung', 'Kabupaten Blitar', 'Kabupaten Kediri', 'Kabupaten Madiun', 'Kabupaten Malang', 'Kabupaten Mojokerto', 'Kabupaten Pasuruan', 'Kabupaten Probolinggo'],
        'Kalimantan Barat': ['Pontianak', 'Singkawang', 'Bengkayang', 'Kapuas Hulu', 'Kayong Utara', 'Ketapang', 'Kubu Raya', 'Landak', 'Melawi', 'Mempawah', 'Sambas', 'Sanggau', 'Sekadau', 'Sintang'],
        'Kalimantan Selatan': ['Banjarbaru', 'Banjarmasin', 'Balangan', 'Banjar', 'Barito Kuala', 'Hulu Sungai Selatan', 'Hulu Sungai Tengah', 'Hulu Sungai Utara', 'Kotabaru', 'Tabalong', 'Tanah Bumbu', 'Tanah Laut', 'Tapin'],
        'Kalimantan Tengah': ['Palangka Raya', 'Barito Selatan', 'Barito Timur', 'Barito Utara', 'Gunung Mas', 'Kapuas', 'Katingan', 'Kotawaringin Barat', 'Kotawaringin Timur', 'Lamandau', 'Murung Raya', 'Pulang Pisau', 'Seruyan', 'Sukamara'],
        'Kalimantan Timur': ['Balikpapan', 'Bontang', 'Samarinda', 'Berau', 'Kutai Barat', 'Kutai Kartanegara', 'Kutai Timur', 'Mahakam Ulu', 'Paser', 'Penajam Paser Utara'],
        'Kalimantan Utara': ['Tarakan', 'Bulungan', 'Malinau', 'Nunukan', 'Tana Tidung'],
        'Bangka Belitung': ['Pangkal Pinang', 'Bangka', 'Bangka Barat', 'Bangka Selatan', 'Bangka Tengah', 'Belitung', 'Belitung Timur'],
        'Kepulauan Riau': ['Batam', 'Tanjung Pinang', 'Bintan', 'Karimun', 'Kepulauan Anambas', 'Lingga', 'Natuna'],
        'Lampung': ['Bandar Lampung', 'Metro', 'Lampung Barat', 'Lampung Selatan', 'Lampung Tengah', 'Lampung Timur', 'Lampung Utara', 'Mesuji', 'Pesawaran', 'Pesisir Barat', 'Pringsewu', 'Tanggamus', 'Tulang Bawang', 'Tulang Bawang Barat', 'Way Kanan'],
        'Maluku': ['Ambon', 'Tual', 'Buru', 'Buru Selatan', 'Kepulauan Aru', 'Maluku Barat Daya', 'Maluku Tengah', 'Maluku Tenggara', 'Maluku Tenggara Barat', 'Seram Bagian Barat', 'Seram Bagian Timur'],
        'Maluku Utara': ['Ternate', 'Tidore Kepulauan', 'Halmahera Barat', 'Halmahera Selatan', 'Halmahera Tengah', 'Halmahera Timur', 'Halmahera Utara', 'Kepulauan Sula', 'Pulau Morotai', 'Pulau Taliabu'],
        'Nusa Tenggara Barat': ['Bima', 'Mataram', 'Dompu', 'Lombok Barat', 'Lombok Tengah', 'Lombok Timur', 'Lombok Utara', 'Sumbawa', 'Sumbawa Barat'],
        'Nusa Tenggara Timur': ['Kupang', 'Alor', 'Belu', 'Ende', 'Flores Timur', 'Lembata', 'Manggarai', 'Manggarai Barat', 'Manggarai Timur', 'Nagekeo', 'Ngada', 'Rote Ndao', 'Sabu Raijua', 'Sikka', 'Sumba Barat', 'Sumba Barat Daya', 'Sumba Tengah', 'Sumba Timur', 'Timor Tengah Selatan', 'Timor Tengah Utara'],
        'Papua': ['Jayapura', 'Asmat', 'Biak Numfor', 'Boven Digoel', 'Deiyai', 'Dogiyai', 'Intan Jaya', 'Jayawijaya', 'Keerom', 'Kepulauan Yapen', 'Lanny Jaya', 'Mamberamo Raya', 'Mamberamo Tengah', 'Mappi', 'Merauke', 'Mimika', 'Nabire', 'Nduga', 'Paniai', 'Pegunungan Bintang', 'Puncak', 'Puncak Jaya', 'Sarmi', 'Supiori', 'Tolikara', 'Waropen', 'Yahukimo', 'Yalimo'],
        'Papua Barat': ['Sorong', 'Fakfak', 'Kaimana', 'Manokwari', 'Manokwari Selatan', 'Maybrat', 'Pegunungan Arfak', 'Raja Ampat', 'Sorong Selatan', 'Tambrauw', 'Teluk Bintuni', 'Teluk Wondama'],
        'Riau': ['Dumai', 'Pekanbaru', 'Bengkalis', 'Indragiri Hilir', 'Indragiri Hulu', 'Kampar', 'Kepulauan Meranti', 'Kuantan Singingi', 'Pelalawan', 'Rokan Hilir', 'Rokan Hulu', 'Siak'],
        'Sulawesi Barat': ['Majene', 'Mamasa', 'Mamuju', 'Mamuju Tengah', 'Mamuju Utara', 'Polewali Mandar'],
        'Sulawesi Selatan': ['Makassar', 'Palopo', 'Parepare', 'Bantaeng', 'Barru', 'Bone', 'Bulukumba', 'Enrekang', 'Gowa', 'Jeneponto', 'Kepulauan Selayar', 'Luwu', 'Luwu Timur', 'Luwu Utara', 'Maros', 'Pangkajene dan Kepulauan', 'Pinrang', 'Sidenreng Rappang', 'Sinjai', 'Soppeng', 'Takalar', 'Tana Toraja', 'Toraja Utara', 'Wajo'],
        'Sulawesi Tengah': ['Palu', 'Banggai', 'Banggai Kepulauan', 'Banggai Laut', 'Buol', 'Donggala', 'Morowali', 'Morowali Utara', 'Parigi Moutong', 'Poso', 'Sigi', 'Tojo Una-Una', 'Toli-Toli'],
        'Sulawesi Tenggara': ['Bau-Bau', 'Kendari', 'Bombana', 'Buton', 'Buton Selatan', 'Buton Tengah', 'Buton Utara', 'Kolaka', 'Kolaka Timur', 'Kolaka Utara', 'Konawe', 'Konawe Kepulauan', 'Konawe Selatan', 'Konawe Utara', 'Muna', 'Muna Barat', 'Wakatobi'],
        'Sulawesi Utara': ['Bitung', 'Kotamobagu', 'Manado', 'Tomohon', 'Bolaang Mongondow', 'Bolaang Mongondow Selatan', 'Bolaang Mongondow Timur', 'Bolaang Mongondow Utara', 'Kepulauan Sangihe', 'Kepulauan Siau Tagulandang Biaro', 'Kepulauan Talaud', 'Minahasa', 'Minahasa Selatan', 'Minahasa Tenggara', 'Minahasa Utara'],
        'Sumatera Barat': ['Bukittinggi', 'Padang', 'Padang Panjang', 'Pariaman', 'Payakumbuh', 'Sawahlunto', 'Solok', 'Agam', 'Dharmasraya', 'Kepulauan Mentawai', 'Lima Puluh Kota', 'Padang Pariaman', 'Pasaman', 'Pasaman Barat', 'Pesisir Selatan', 'Sijunjung', 'Solok Selatan', 'Tanah Datar'],
        'Sumatera Selatan': ['Lubuklinggau', 'Pagar Alam', 'Palembang', 'Prabumulih', 'Banyuasin', 'Empat Lawang', 'Lahat', 'Muara Enim', 'Musi Banyuasin', 'Musi Rawas', 'Musi Rawas Utara', 'Ogan Ilir', 'Ogan Komering Ilir', 'Ogan Komering Ulu', 'Ogan Komering Ulu Selatan', 'Ogan Komering Ulu Timur', 'Penukal Abab Lematang Ilir'],
        'Sumatera Utara': ['Binjai', 'Gunungsitoli', 'Medan', 'Padang Sidempuan', 'Pematang Siantar', 'Sibolga', 'Tanjung Balai', 'Tebing Tinggi', 'Asahan', 'Batubara', 'Dairi', 'Deli Serdang', 'Humbang Hasundutan', 'Karo', 'Labuhanbatu', 'Labuhanbatu Selatan', 'Labuhanbatu Utara', 'Langkat', 'Mandailing Natal', 'Nias', 'Nias Barat', 'Nias Selatan', 'Nias Utara', 'Padang Lawas', 'Padang Lawas Utara', 'Pakpak Bharat', 'Samosir', 'Serdang Bedagai', 'Simalungun', 'Tapanuli Selatan', 'Tapanuli Tengah', 'Tapanuli Utara', 'Toba Samosir']
    };

    // Data kota ke kode pos (lebih lengkap dari form-mitra, tapi tidak perlu semua - bisa diperpendek jika terlalu besar)
    const postalCodeData = {
        'Banda Aceh': ['23111', '23115', '23116', '23231'],
        'Denpasar': ['80111', '80112', '80225', '80361'],
        'Yogyakarta': ['55111', '55112', '55113', '55221'],
        'Jakarta Barat': ['11110', '11120', '11130', '11410'],
        'Jakarta Pusat': ['10110', '10120', '10310', '10710'],
        'Jakarta Selatan': ['12110', '12120', '12310', '12810'],
        'Jakarta Timur': ['13110', '13120', '13310', '13810'],
        'Jakarta Utara': ['14110', '14120', '14310', '14510'],
        'Bandung': ['40111', '40112', '40211', '40291'],
        'Semarang': ['50111', '50112', '50211', '50271'],
        'Surabaya': ['60111', '60112', '60211', '60291'],
        'Medan': ['20111', '20112', '20211', '20371'],
        'Makassar': ['90111', '90112', '90211', '90251'],
        'Palembang': ['30111', '30112', '30211', '30961'],
        'Tangerang': ['15111', '15112', '15211', '15610'],
        'Bekasi': ['17111', '17112', '17211', '17910'],
        'Depok': ['16411', '16412', '16511', '16911'],
        'Bogor': ['16111', '16112', '16320', '16810']
        // Bisa ditambahkan kode pos kota lain sesuai kebutuhan
    };

    // Fungsi toggle required class pada label
    function toggleRequiredClass(labelId, selectElement) {
        const label = document.getElementById(labelId);
        if (label) {
            if (selectElement.value) {
                label.classList.remove('required');
            } else {
                label.classList.add('required');
            }
        }
    }

    // Event listener untuk provinsi
    const provinceSelect = document.getElementById('province');
    const citySelect = document.getElementById('city');
    const postalCodeSelect = document.getElementById('postalCode');

    // Log initial values for debugging
    console.log('Initial province:', provinceSelect.value);
    console.log('Initial city data-selected:', citySelect.getAttribute('data-selected'));
    console.log('Initial postal data-selected:', postalCodeSelect.getAttribute('data-selected'));

    // Pre-populate city and postal code on page load if province is set
    const initialProvince = provinceSelect.value;
    const initialCity = citySelect.getAttribute('data-selected');
    const initialPostal = postalCodeSelect.getAttribute('data-selected');

    if (initialProvince && cityData[initialProvince]) {
        console.log('Pre-populating city for province:', initialProvince);
        const cities = cityData[initialProvince];
        
        citySelect.innerHTML = '<option value="">Pilih Kota/Kabupaten</option>';
        cities.forEach(city => {
            const option = document.createElement('option');
            option.value = city;
            option.textContent = city;
            if (initialCity && city === initialCity) {
                option.selected = true;
                console.log('Selected city:', city);
            }
            citySelect.appendChild(option);
        });
        citySelect.disabled = false;
        
        // Pre-populate postal code if city is set
        if (initialCity) {
            if (postalCodeData[initialCity]) {
                console.log('Pre-populating postal code for city:', initialCity);
                const postalCodes = postalCodeData[initialCity];
                
                postalCodeSelect.innerHTML = '<option value="">Pilih Kode Pos</option>';
                postalCodes.forEach(code => {
                    const option = document.createElement('option');
                    option.value = code;
                    option.textContent = code;
                    if (initialPostal && code === initialPostal) {
                        option.selected = true;
                        console.log('Selected postal code:', code);
                    }
                    postalCodeSelect.appendChild(option);
                });
                postalCodeSelect.disabled = false;
                
                // If postal code not in list, add it
                if (initialPostal && !postalCodes.includes(initialPostal)) {
                    const option = document.createElement('option');
                    option.value = initialPostal;
                    option.textContent = initialPostal;
                    option.selected = true;
                    postalCodeSelect.appendChild(option);
                    console.log('Added custom postal code:', initialPostal);
                }
            } else {
                // No data for this city, add custom postal code
                console.log('No postal data for city, adding custom:', initialPostal);
                postalCodeSelect.innerHTML = '<option value="">Pilih Kode Pos</option>';
                if (initialPostal) {
                    const option = document.createElement('option');
                    option.value = initialPostal;
                    option.textContent = initialPostal;
                    option.selected = true;
                    postalCodeSelect.appendChild(option);
                }
                postalCodeSelect.disabled = false;
            }
        }
    }

    provinceSelect.addEventListener('change', function() {
        const provinceValue = this.value;

        // Toggle asterisk pada provinsi
        toggleRequiredClass('provinceLabel', this);

        // Reset dan disable city dan postal code
        citySelect.innerHTML = '<option value="">Pilih Kota/Kabupaten</option>';
        citySelect.disabled = true;
        postalCodeSelect.innerHTML = '<option value="">Pilih Kode Pos</option>';
        postalCodeSelect.disabled = true;

        // Restore asterisk pada city dan postal code
        document.getElementById('cityLabel').classList.add('required');
        document.getElementById('postalCodeLabel').classList.add('required');

        // Populate city jika provinsi dipilih
        if (provinceValue && cityData[provinceValue]) {
            const cities = cityData[provinceValue];
            const selectedCity = citySelect.getAttribute('data-selected');
            
            cities.forEach(city => {
                const option = document.createElement('option');
                option.value = city;
                option.textContent = city;
                if (selectedCity && city === selectedCity) {
                    option.selected = true;
                    toggleRequiredClass('cityLabel', citySelect);
                }
                citySelect.appendChild(option);
            });
            citySelect.disabled = false;

            // Trigger city change jika ada pre-selected
            if (selectedCity) {
                setTimeout(() => {
                    const event = new Event('change');
                    citySelect.dispatchEvent(event);
                }, 100);
            }
        }
    });

    // Event listener untuk city
    citySelect.addEventListener('change', function() {
        const cityValue = this.value;

        // Toggle asterisk pada city
        toggleRequiredClass('cityLabel', this);

        // Reset postal code
        postalCodeSelect.innerHTML = '<option value="">Pilih Kode Pos</option>';
        postalCodeSelect.disabled = true;
        document.getElementById('postalCodeLabel').classList.add('required');

        // Populate postal code jika city dipilih dan data tersedia
        if (cityValue && postalCodeData[cityValue]) {
            const postalCodes = postalCodeData[cityValue];
            const selectedPostal = postalCodeSelect.getAttribute('data-selected');
            
            postalCodes.forEach(code => {
                const option = document.createElement('option');
                option.value = code;
                option.textContent = code;
                if (selectedPostal && code === selectedPostal) {
                    option.selected = true;
                    toggleRequiredClass('postalCodeLabel', postalCodeSelect);
                }
                postalCodeSelect.appendChild(option);
            });
            postalCodeSelect.disabled = false;
            
            // Jika kode pos yang tersimpan tidak ada di list, tambahkan sebagai option
            if (selectedPostal && !postalCodes.includes(selectedPostal)) {
                const option = document.createElement('option');
                option.value = selectedPostal;
                option.textContent = selectedPostal;
                option.selected = true;
                postalCodeSelect.appendChild(option);
                toggleRequiredClass('postalCodeLabel', postalCodeSelect);
            }
        } else if (cityValue) {
            // Jika tidak ada data kode pos, enable untuk manual input dan set existing value
            const selectedPostal = postalCodeSelect.getAttribute('data-selected');
            if (selectedPostal) {
                const option = document.createElement('option');
                option.value = selectedPostal;
                option.textContent = selectedPostal;
                option.selected = true;
                postalCodeSelect.appendChild(option);
                toggleRequiredClass('postalCodeLabel', postalCodeSelect);
            }
            postalCodeSelect.disabled = false;
            postalCodeSelect.disabled = false;
        }
    });

    // Event listener untuk postal code
    postalCodeSelect.addEventListener('change', function() {
        toggleRequiredClass('postalCodeLabel', this);
    });

    // Form submission handler
    const editProfileForm = document.getElementById('editProfileForm');
    editProfileForm.addEventListener('submit', async function(e) {
        e.preventDefault();

        // Enable disabled fields before collecting data
        const citySelect = document.getElementById('city');
        const postalCodeSelect = document.getElementById('postalCode');
        const wasCityDisabled = citySelect.disabled;
        const wasPostalDisabled = postalCodeSelect.disabled;
        
        citySelect.disabled = false;
        postalCodeSelect.disabled = false;

        // Collect form data
        const formData = new FormData();
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
        formData.append('business_name', document.getElementById('businessName').value);
        formData.append('establishment_year', document.getElementById('establishmentYear').value);
        formData.append('address', document.getElementById('address').value);
        formData.append('province', document.getElementById('province').value);
        formData.append('city', citySelect.value);
        formData.append('postal_code', postalCodeSelect.value);
        formData.append('map_location', document.getElementById('mapLocation').value);
        formData.append('contact_person', document.getElementById('contactPerson').value);
        formData.append('phone', document.getElementById('phone').value);

        // Append new facility photos
        facilityFiles.forEach((file, index) => {
            formData.append('facility_photos[]', file);
        });

        // Append delete photos (akan ditambahkan oleh removeExistingFacilityPhoto)
        document.querySelectorAll('input[name="delete_photos[]"]').forEach(input => {
            formData.append('delete_photos[]', input.value);
        });

        try {
            const submitBtn = e.target.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.textContent = 'Menyimpan...';

            const response = await fetch('/mitra/profil/update', {
                method: 'POST',
                body: formData
            });

            const result = await response.json();

            if (response.ok && result.success) {
                alert('Profil berhasil diperbarui!');
                window.location.href = '/mitra/profil/profil';
            } else {
                console.error('Error response:', result);
                console.error('Validation errors:', JSON.stringify(result.errors, null, 2));
                
                // Format error messages
                let errorMsg = 'Gagal memperbarui profil:\n';
                if (result.errors) {
                    for (const [field, messages] of Object.entries(result.errors)) {
                        errorMsg += `\n${field}: ${Array.isArray(messages) ? messages.join(', ') : messages}`;
                    }
                } else {
                    errorMsg += result.message || 'Unknown error';
                }
                
                alert(errorMsg);
                
                // Restore disabled state if needed
                if (wasCityDisabled) citySelect.disabled = true;
                if (wasPostalDisabled) postalCodeSelect.disabled = true;
                
                submitBtn.disabled = false;
                submitBtn.textContent = 'Simpan Perubahan';
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat memperbarui profil');
            
            // Restore disabled state if needed
            if (wasCityDisabled) citySelect.disabled = true;
            if (wasPostalDisabled) postalCodeSelect.disabled = true;
            
            const submitBtn = e.target.querySelector('button[type="submit"]');
            submitBtn.disabled = false;
            submitBtn.textContent = 'Simpan Perubahan';
        }
    });
});

// Function to remove existing photo
function removeExistingPhoto(button) {
    if (confirm('Hapus foto ini?')) {
        button.closest('.preview-item').remove();
    }
}
