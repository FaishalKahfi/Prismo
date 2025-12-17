// Use real data from server
console.log(" Loading services data...", window.servicesData);
const servicesData = window.servicesData || [];

// Data Kota berdasarkan Provinsi
const kotaByProvinsi = {
    Aceh: [
        "Banda Aceh",
        "Sabang",
        "Lhokseumawe",
        "Langsa",
        "Subulussalam",
        "Aceh Barat",
        "Aceh Barat Daya",
        "Aceh Besar",
        "Aceh Jaya",
        "Aceh Selatan",
        "Aceh Singkil",
        "Aceh Tamiang",
        "Aceh Tengah",
        "Aceh Tenggara",
        "Aceh Timur",
        "Aceh Utara",
        "Bener Meriah",
        "Bireuen",
        "Gayo Lues",
        "Nagan Raya",
        "Pidie",
        "Pidie Jaya",
        "Simeulue",
    ],
    "Sumatera Utara": [
        "Medan",
        "Binjai",
        "Padangsidimpuan",
        "Pematangsiantar",
        "Sibolga",
        "Tanjungbalai",
        "Tebing Tinggi",
        "Asahan",
        "Batubara",
        "Dairi",
        "Deli Serdang",
        "Humbang Hasundutan",
        "Karo",
        "Labuhanbatu",
        "Labuhanbatu Selatan",
        "Labuhanbatu Utara",
        "Langkat",
        "Mandailing Natal",
        "Nias",
        "Nias Barat",
        "Nias Selatan",
        "Nias Utara",
        "Padang Lawas",
        "Padang Lawas Utara",
        "Pakpak Bharat",
        "Samosir",
        "Serdang Bedagai",
        "Simalungun",
        "Tapanuli Selatan",
        "Tapanuli Tengah",
        "Tapanuli Utara",
        "Toba",
    ],
    "Sumatera Barat": [
        "Padang",
        "Bukittinggi",
        "Padangpanjang",
        "Pariaman",
        "Payakumbuh",
        "Sawahlunto",
        "Solok",
        "Agam",
        "Dharmasraya",
        "Kepulauan Mentawai",
        "Lima Puluh Kota",
        "Padang Pariaman",
        "Pasaman",
        "Pasaman Barat",
        "Pesisir Selatan",
        "Sijunjung",
        "Solok",
        "Solok Selatan",
        "Tanah Datar",
    ],
    Riau: [
        "Pekanbaru",
        "Dumai",
        "Bengkalis",
        "Indragiri Hilir",
        "Indragiri Hulu",
        "Kampar",
        "Kepulauan Meranti",
        "Kuantan Singingi",
        "Pelalawan",
        "Rokan Hilir",
        "Rokan Hulu",
        "Siak",
    ],
    Jambi: [
        "Jambi",
        "Sungai Penuh",
        "Batang Hari",
        "Bungo",
        "Kerinci",
        "Merangin",
        "Muaro Jambi",
        "Sarolangun",
        "Tanjung Jabung Barat",
        "Tanjung Jabung Timur",
        "Tebo",
    ],
    "Sumatera Selatan": [
        "Palembang",
        "Lubuklinggau",
        "Pagar Alam",
        "Prabumulih",
        "Banyuasin",
        "Empat Lawang",
        "Lahat",
        "Muara Enim",
        "Musi Banyuasin",
        "Musi Rawas",
        "Musi Rawas Utara",
        "Ogan Ilir",
        "Ogan Komering Ilir",
        "Ogan Komering Ulu",
        "Ogan Komering Ulu Selatan",
        "Ogan Komering Ulu Timur",
        "Penukal Abab Lematang Ilir",
    ],
    Bengkulu: [
        "Bengkulu",
        "Bengkulu Selatan",
        "Bengkulu Tengah",
        "Bengkulu Utara",
        "Kaur",
        "Kepahiang",
        "Lebong",
        "Mukomuko",
        "Rejang Lebong",
        "Seluma",
    ],
    Lampung: [
        "Bandar Lampung",
        "Metro",
        "Lampung Barat",
        "Lampung Selatan",
        "Lampung Tengah",
        "Lampung Timur",
        "Lampung Utara",
        "Mesuji",
        "Pesawaran",
        "Pesisir Barat",
        "Pringsewu",
        "Tanggamus",
        "Tulang Bawang",
        "Tulang Bawang Barat",
        "Way Kanan",
    ],
    "Kepulauan Bangka Belitung": [
        "Pangkalpinang",
        "Bangka",
        "Bangka Barat",
        "Bangka Selatan",
        "Bangka Tengah",
        "Belitung",
        "Belitung Timur",
    ],
    "Kepulauan Riau": [
        "Batam",
        "Tanjungpinang",
        "Bintan",
        "Karimun",
        "Kepulauan Anambas",
        "Lingga",
        "Natuna",
    ],
    "DKI Jakarta": [
        "Jakarta Pusat",
        "Jakarta Selatan",
        "Jakarta Timur",
        "Jakarta Barat",
        "Jakarta Utara",
        "Kepulauan Seribu",
    ],
    "Jawa Barat": [
        "Bandung",
        "Banjar",
        "Bekasi",
        "Bogor",
        "Cimahi",
        "Cirebon",
        "Depok",
        "Sukabumi",
        "Tasikmalaya",
        "Bandung Barat",
        "Ciamis",
        "Cianjur",
        "Garut",
        "Indramayu",
        "Karawang",
        "Kuningan",
        "Majalengka",
        "Pangandaran",
        "Purwakarta",
        "Subang",
        "Sumedang",
        "Kabupaten Bandung",
        "Kabupaten Bekasi",
        "Kabupaten Bogor",
        "Kabupaten Cirebon",
        "Kabupaten Sukabumi",
        "Kabupaten Tasikmalaya",
    ],
    "Jawa Tengah": [
        "Magelang",
        "Pekalongan",
        "Salatiga",
        "Semarang",
        "Surakarta",
        "Tegal",
        "Banjarnegara",
        "Banyumas",
        "Batang",
        "Blora",
        "Boyolali",
        "Brebes",
        "Cilacap",
        "Demak",
        "Grobogan",
        "Jepara",
        "Karanganyar",
        "Kebumen",
        "Kendal",
        "Klaten",
        "Kudus",
        "Pati",
        "Pemalang",
        "Purbalingga",
        "Purworejo",
        "Rembang",
        "Sragen",
        "Sukoharjo",
        "Temanggung",
        "Wonogiri",
        "Wonosobo",
        "Kabupaten Magelang",
        "Kabupaten Pekalongan",
        "Kabupaten Semarang",
        "Kabupaten Tegal",
    ],
    "DI Yogyakarta": [
        "Yogyakarta",
        "Bantul",
        "Gunung Kidul",
        "Kulon Progo",
        "Sleman",
    ],
    "Jawa Timur": [
        "Batu",
        "Blitar",
        "Kediri",
        "Madiun",
        "Malang",
        "Mojokerto",
        "Pasuruan",
        "Probolinggo",
        "Surabaya",
        "Bangkalan",
        "Banyuwangi",
        "Bojonegoro",
        "Bondowoso",
        "Gresik",
        "Jember",
        "Jombang",
        "Lamongan",
        "Lumajang",
        "Magetan",
        "Nganjuk",
        "Ngawi",
        "Pacitan",
        "Pamekasan",
        "Ponorogo",
        "Sampang",
        "Sidoarjo",
        "Situbondo",
        "Sumenep",
        "Trenggalek",
        "Tuban",
        "Tulungagung",
        "Kabupaten Blitar",
        "Kabupaten Kediri",
        "Kabupaten Madiun",
        "Kabupaten Malang",
        "Kabupaten Mojokerto",
        "Kabupaten Pasuruan",
        "Kabupaten Probolinggo",
    ],
    Banten: [
        "Cilegon",
        "Serang",
        "Tangerang",
        "Tangerang Selatan",
        "Lebak",
        "Pandeglang",
        "Kabupaten Serang",
        "Kabupaten Tangerang",
    ],
    Bali: [
        "Denpasar",
        "Badung",
        "Bangli",
        "Buleleng",
        "Gianyar",
        "Jembrana",
        "Karangasem",
        "Klungkung",
        "Tabanan",
    ],
    "Nusa Tenggara Barat": [
        "Bima",
        "Mataram",
        "Bima",
        "Dompu",
        "Lombok Barat",
        "Lombok Tengah",
        "Lombok Timur",
        "Lombok Utara",
        "Sumbawa",
        "Sumbawa Barat",
    ],
    "Nusa Tenggara Timur": [
        "Kupang",
        "Alor",
        "Belu",
        "Ende",
        "Flores Timur",
        "Kupang",
        "Lembata",
        "Malaka",
        "Manggarai",
        "Manggarai Barat",
        "Manggarai Timur",
        "Nagekeo",
        "Ngada",
        "Rote Ndao",
        "Sabu Raijua",
        "Sikka",
        "Sumba Barat",
        "Sumba Barat Daya",
        "Sumba Tengah",
        "Sumba Timur",
        "Timor Tengah Selatan",
        "Timor Tengah Utara",
    ],
    "Kalimantan Barat": [
        "Pontianak",
        "Singkawang",
        "Bengkayang",
        "Kapuas Hulu",
        "Kayong Utara",
        "Ketapang",
        "Kubu Raya",
        "Landak",
        "Melawi",
        "Mempawah",
        "Sambas",
        "Sanggau",
        "Sekadau",
        "Sintang",
    ],
    "Kalimantan Tengah": [
        "Palangka Raya",
        "Barito Selatan",
        "Barito Timur",
        "Barito Utara",
        "Gunung Mas",
        "Kapuas",
        "Katingan",
        "Kotawaringin Barat",
        "Kotawaringin Timur",
        "Lamandau",
        "Murung Raya",
        "Pulang Pisau",
        "Seruyan",
        "Sukamara",
    ],
    "Kalimantan Selatan": [
        "Banjarbaru",
        "Banjarmasin",
        "Balangan",
        "Banjar",
        "Barito Kuala",
        "Hulu Sungai Selatan",
        "Hulu Sungai Tengah",
        "Hulu Sungai Utara",
        "Kotabaru",
        "Tabalong",
        "Tanah Bumbu",
        "Tanah Laut",
        "Tapin",
    ],
    "Kalimantan Timur": [
        "Balikpapan",
        "Bontang",
        "Samarinda",
        "Berau",
        "Kutai Barat",
        "Kutai Kartanegara",
        "Kutai Timur",
        "Mahakam Ulu",
        "Paser",
        "Penajam Paser Utara",
    ],
    "Kalimantan Utara": [
        "Tarakan",
        "Bulungan",
        "Malinau",
        "Nunukan",
        "Tana Tidung",
    ],
    "Sulawesi Utara": [
        "Bitung",
        "Kotamobagu",
        "Manado",
        "Tomohon",
        "Bolaang Mongondow",
        "Bolaang Mongondow Selatan",
        "Bolaang Mongondow Timur",
        "Bolaang Mongondow Utara",
        "Kepulauan Sangihe",
        "Kepulauan Siau Tagulandang Biaro",
        "Kepulauan Talaud",
        "Minahasa",
        "Minahasa Selatan",
        "Minahasa Tenggara",
        "Minahasa Utara",
    ],
    "Sulawesi Tengah": [
        "Palu",
        "Banggai",
        "Banggai Kepulauan",
        "Banggai Laut",
        "Buol",
        "Donggala",
        "Morowali",
        "Morowali Utara",
        "Parigi Moutong",
        "Poso",
        "Sigi",
        "Tojo Una-Una",
        "Toli-Toli",
    ],
    "Sulawesi Selatan": [
        "Makassar",
        "Palopo",
        "Parepare",
        "Bantaeng",
        "Barru",
        "Bone",
        "Bulukumba",
        "Enrekang",
        "Gowa",
        "Jeneponto",
        "Kepulauan Selayar",
        "Luwu",
        "Luwu Timur",
        "Luwu Utara",
        "Maros",
        "Pangkajene dan Kepulauan",
        "Pinrang",
        "Sidenreng Rappang",
        "Sinjai",
        "Soppeng",
        "Takalar",
        "Tana Toraja",
        "Toraja Utara",
        "Wajo",
    ],
    "Sulawesi Tenggara": [
        "Bau-Bau",
        "Kendari",
        "Bombana",
        "Buton",
        "Buton Selatan",
        "Buton Tengah",
        "Buton Utara",
        "Kolaka",
        "Kolaka Timur",
        "Kolaka Utara",
        "Konawe",
        "Konawe Kepulauan",
        "Konawe Selatan",
        "Konawe Utara",
        "Muna",
        "Muna Barat",
        "Wakatobi",
    ],
    Gorontalo: [
        "Gorontalo",
        "Boalemo",
        "Bone Bolango",
        "Gorontalo",
        "Gorontalo Utara",
        "Pohuwato",
    ],
    "Sulawesi Barat": [
        "Majene",
        "Mamasa",
        "Mamuju",
        "Mamuju Tengah",
        "Mamuju Utara",
        "Polewali Mandar",
    ],
    Maluku: [
        "Ambon",
        "Tual",
        "Buru",
        "Buru Selatan",
        "Kepulauan Aru",
        "Maluku Barat Daya",
        "Maluku Tengah",
        "Maluku Tenggara",
        "Maluku Tenggara Barat",
        "Seram Bagian Barat",
        "Seram Bagian Timur",
    ],
    "Maluku Utara": [
        "Ternate",
        "Tidore Kepulauan",
        "Halmahera Barat",
        "Halmahera Selatan",
        "Halmahera Tengah",
        "Halmahera Timur",
        "Halmahera Utara",
        "Kepulauan Sula",
        "Pulau Morotai",
        "Pulau Taliabu",
    ],
    "Papua Barat": [
        "Sorong",
        "Fakfak",
        "Kaimana",
        "Manokwari",
        "Manokwari Selatan",
        "Maybrat",
        "Pegunungan Arfak",
        "Raja Ampat",
        "Sorong",
        "Sorong Selatan",
        "Tambrauw",
        "Teluk Bintuni",
        "Teluk Wondama",
    ],
    Papua: [
        "Jayapura",
        "Asmat",
        "Biak Numfor",
        "Boven Digoel",
        "Deiyai",
        "Dogiyai",
        "Intan Jaya",
        "Jayapura",
        "Jayawijaya",
        "Keerom",
        "Kepulauan Yapen",
        "Lanny Jaya",
        "Mamberamo Raya",
        "Mamberamo Tengah",
        "Mappi",
        "Merauke",
        "Mimika",
        "Nabire",
        "Nduga",
        "Paniai",
        "Pegunungan Bintang",
        "Puncak",
        "Puncak Jaya",
        "Sarmi",
        "Supiori",
        "Tolikara",
        "Waropen",
        "Yahukimo",
        "Yalimo",
    ],
    "Papua Barat Daya": [
        "Sorong",
        "Fakfak",
        "Kaimana",
        "Manokwari",
        "Maybrat",
        "Raja Ampat",
        "Sorong Selatan",
        "Tambrauw",
    ],
    "Papua Pegunungan": [
        "Jayawijaya",
        "Lanny Jaya",
        "Mamberamo Tengah",
        "Nduga",
        "Pegunungan Bintang",
        "Tolikara",
        "Yahukimo",
        "Yalimo",
    ],
    "Papua Selatan": ["Asmat", "Boven Digoel", "Mappi", "Merauke"],
    "Papua Tengah": [
        "Deiyai",
        "Dogiyai",
        "Intan Jaya",
        "Mimika",
        "Nabire",
        "Paniai",
        "Puncak",
        "Puncak Jaya",
    ],
};

// Filter state
let currentFilters = {
    provinsi: "",
    kota: "",
    searchTerm: "",
};

// Navigation Active State Management
function initNavigation() {
    const navLinks = document.querySelectorAll(".nav-menu .nav-link");

    function setActiveNav() {
        const currentPage = window.location.pathname;

        navLinks.forEach((link) => {
            link.classList.remove("active");

            const linkHref = link.getAttribute("href");

            // Check for exact match or if current page ends with the link path
            if (
                currentPage === linkHref ||
                (currentPage === "/dashboard" &&
                    linkHref.includes("/dashboard")) ||
                (currentPage.includes("/customer/dashboard") &&
                    linkHref.includes("/dashboard"))
            ) {
                link.classList.add("active");
            }
        });
    }

    // Event listeners untuk click
    navLinks.forEach((link) => {
        link.addEventListener("click", function (e) {
            navLinks.forEach((l) => l.classList.remove("active"));
            this.classList.add("active");
        });
    });

    // Set active state on page load
    setActiveNav();
    window.addEventListener("popstate", setActiveNav);
}

// Notification Permission Popup (for browser notification permission)
function initNotificationPermission() {
    const notificationPopup = document.querySelector(".notification-popup");
    const btnBlokir = document.querySelector(".btn-blokir");
    const btnIzinkan = document.querySelector(".btn-izinkan");

    // Notification Popup
    if (btnBlokir && notificationPopup) {
        btnBlokir.addEventListener("click", () => {
            notificationPopup.style.display = "none";
        });
    }

    if (btnIzinkan && notificationPopup) {
        btnIzinkan.addEventListener("click", () => {
            notificationPopup.style.display = "none";
            if ("Notification" in window) {
                Notification.requestPermission();
            }
        });
    }

    // Notification panel toggle is now handled by notification-system.js
    console.log("🔔 Using shared notification-system.js");
}

// Service Cards Generation - Now handled by pagination
function generateServiceCards() {
    // This function is now handled by initServicesNavigation with pagination
    // Keeping for compatibility with other code that might call it
    return;
}

// Search Functionality
function initSearch() {
    const searchInput = document.querySelector(".search-input");
    if (searchInput) {
        let searchTimeout;

        searchInput.addEventListener("input", (e) => {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                const searchTerm = e.target.value.toLowerCase().trim();
                if (searchTerm.length > 0) {
                    filterServices(searchTerm);
                } else {
                    resetServiceFilter();
                }
            }, 300);
        });
    }
}

function filterServices(searchTerm) {
    const serviceCards = document.querySelectorAll(".service-card");
    let hasResults = false;

    serviceCards.forEach((card) => {
        const serviceName = card
            .querySelector(".service-name")
            .textContent.toLowerCase();
        const serviceLocation = card
            .querySelector(".service-location")
            .textContent.toLowerCase();

        if (
            serviceName.includes(searchTerm) ||
            serviceLocation.includes(searchTerm)
        ) {
            card.style.display = "block";
            hasResults = true;
        } else {
            card.style.display = "none";
        }
    });

    showNoResults(!hasResults);
}

function resetServiceFilter() {
    const serviceCards = document.querySelectorAll(".service-card");
    serviceCards.forEach((card) => {
        card.style.display = "block";
    });
    showNoResults(false);
}

function showNoResults(show) {
    let noResults = document.getElementById("noResults");
    const servicesGrid = document.getElementById("servicesGrid");

    if (show && !noResults && servicesGrid) {
        noResults = document.createElement("div");
        noResults.id = "noResults";
        noResults.className = "no-results";
        noResults.textContent =
            "Tidak ada layanan yang sesuai dengan pencarian";
        servicesGrid.appendChild(noResults);
    } else if (!show && noResults) {
        noResults.remove();
    }
}

// Service Card Interactions
function initServiceCards() {
    const serviceCards = document.querySelectorAll(".service-card");

    serviceCards.forEach((card) => {
        card.addEventListener("mouseenter", () => {
            card.style.transform = "translateY(-8px)";
            card.style.transition = "transform 0.3s ease, box-shadow 0.3s ease";
        });

        card.addEventListener("mouseleave", () => {
            card.style.transform = "translateY(0)";
        });
    });
}

// Promo Button Handler
function handleKlaim() {
    console.log("Promo diklaim");
    // Redirect logic bisa ditambahkan di sini
    // window.location.href = '/customer/booking/Rbooking.html?promo=WELCOME2024';
}

// Load User Profile - Avatar now loaded from database via Blade template
function loadUserProfile() {
    // All data loaded from database via Blade template
    console.log("✅ User profile loaded from database");
}

// Main Initialization Function
function initializePrismoApp() {
    // Initialize semua komponen
    initNavigation();
    initNotificationPermission();
    initSortPanel();
    generateServiceCards();
    initSearch();
    initServiceCards();
    initServicesNavigation();
    loadUserProfile();
    initMobileMenu();

    // Expose handleKlaim ke global scope untuk HTML onclick
    window.handleKlaim = handleKlaim;

    console.log("Prismo App Initialized Successfully");
}

// Mobile Menu Toggle
function initMobileMenu() {
    const menuToggle = document.getElementById("menuToggle");
    const mainNav = document.getElementById("mainNav");

    if (menuToggle && mainNav) {
        menuToggle.addEventListener("click", function () {
            mainNav.classList.toggle("active");
        });

        // Close menu when clicking outside
        document.addEventListener("click", function (event) {
            if (
                !menuToggle.contains(event.target) &&
                !mainNav.contains(event.target)
            ) {
                mainNav.classList.remove("active");
            }
        });

        // Close menu when clicking a nav link
        const navLinks = mainNav.querySelectorAll(".nav-link");
        navLinks.forEach((link) => {
            link.addEventListener("click", function () {
                mainNav.classList.remove("active");
            });
        });
    }
}

// Sort Panel Functions
function initSortPanel() {
    console.log("🔧 Initializing Sort Panel...");
    const sortBtn = document.getElementById("sortBtn");
    const sortPanel = document.getElementById("sortPanel");
    const sortOverlay = document.getElementById("sortOverlay");
    const closeSortBtn = document.getElementById("closeSortBtn");
    const provinsiSelect = document.getElementById("provinsiSelect");
    const kotaSelect = document.getElementById("kotaSelect");
    const applyFilterBtn = document.getElementById("applyFilterBtn");
    const resetFilterBtn = document.getElementById("resetFilterBtn");
    const searchInput = document.getElementById("searchInput");

    console.log("Sort elements:", {
        sortBtn,
        sortPanel,
        provinsiSelect,
        kotaSelect,
        applyFilterBtn,
    });

    // Open sort panel
    if (sortBtn) {
        sortBtn.addEventListener("click", function () {
            console.log("Sort button clicked");
            sortPanel.classList.add("show");
            sortOverlay.classList.add("show");
        });
    }

    // Close sort panel
    function closeSortPanel() {
        sortPanel.classList.remove("show");
        sortOverlay.classList.remove("show");
    }

    if (closeSortBtn) {
        closeSortBtn.addEventListener("click", closeSortPanel);
    }

    if (sortOverlay) {
        sortOverlay.addEventListener("click", closeSortPanel);
    }

    // Handle provinsi change
    if (provinsiSelect) {
        provinsiSelect.addEventListener("change", function () {
            const selectedProvinsi = this.value;
            kotaSelect.innerHTML =
                '<option value="">Semua Kota/Kabupaten</option>';

            if (selectedProvinsi && kotaByProvinsi[selectedProvinsi]) {
                kotaSelect.disabled = false;
                kotaByProvinsi[selectedProvinsi].forEach((kota) => {
                    const option = document.createElement("option");
                    option.value = kota;
                    option.textContent = kota;
                    kotaSelect.appendChild(option);
                });
            } else {
                kotaSelect.disabled = true;
                kotaSelect.innerHTML =
                    '<option value="">Pilih provinsi terlebih dahulu</option>';
            }
        });
    }

    // Apply filter - reload page with query parameters
    if (applyFilterBtn) {
        applyFilterBtn.addEventListener("click", function () {
            const provinsi = provinsiSelect.value.trim();
            const kota = kotaSelect.value.trim();
            const search = searchInput ? searchInput.value.trim() : "";

            const params = new URLSearchParams();
            if (provinsi) params.append("provinsi", provinsi);
            if (kota) params.append("kota", kota);
            if (search) params.append("search", search);

            window.location.href =
                "/customer/dashboard" +
                (params.toString() ? "?" + params.toString() : "");
        });
    }

    // Reset filter - reload page without parameters
    if (resetFilterBtn) {
        resetFilterBtn.addEventListener("click", function () {
            window.location.href = "/customer/dashboard";
        });
    }

    // Search functionality - trigger search on Enter key
    if (searchInput) {
        searchInput.addEventListener("keypress", function (e) {
            if (e.key === "Enter") {
                const provinsi = provinsiSelect
                    ? provinsiSelect.value.trim()
                    : "";
                const kota = kotaSelect ? kotaSelect.value.trim() : "";
                const search = this.value.trim();

                const params = new URLSearchParams();
                if (provinsi) params.append("provinsi", provinsi);
                if (kota) params.append("kota", kota);
                if (search) params.append("search", search);

                window.location.href =
                    "/customer/dashboard" +
                    (params.toString() ? "?" + params.toString() : "");
            }
        });
    }
}

function applyFilters() {
    let filteredServices = servicesData;

    console.log("Applying filters:", currentFilters);
    console.log("Total services before filter:", filteredServices.length);

    // Check if any filter is active
    const hasActiveFilter =
        currentFilters.provinsi ||
        currentFilters.kota ||
        currentFilters.searchTerm;

    if (!hasActiveFilter) {
        // No filter active, show all with pagination
        clearFiltersAndShowAll();
        return;
    }

    // Filter by provinsi
    if (currentFilters.provinsi) {
        filteredServices = filteredServices.filter(
            (service) => service.provinsi === currentFilters.provinsi
        );
        console.log("After provinsi filter:", filteredServices.length);
    }

    // Filter by kota
    if (currentFilters.kota) {
        filteredServices = filteredServices.filter((service) => {
            const match = service.kota === currentFilters.kota;
            console.log(
                `Comparing service.kota="${service.kota}" with filter="${currentFilters.kota}": ${match}`
            );
            return match;
        });
        console.log("After kota filter:", filteredServices.length);
    }

    // Filter by search term
    if (currentFilters.searchTerm) {
        filteredServices = filteredServices.filter(
            (service) =>
                service.name
                    .toLowerCase()
                    .includes(currentFilters.searchTerm) ||
                service.location
                    .toLowerCase()
                    .includes(currentFilters.searchTerm)
        );
    }

    console.log("Final filtered services:", filteredServices.length);
    // Re-generate service cards with filtered data
    generateFilteredServiceCards(filteredServices);
}

function generateFilteredServiceCards(services) {
    const servicesGrid = document.getElementById("servicesGrid");
    if (!servicesGrid) return;

    if (services.length === 0) {
        servicesGrid.innerHTML = `
            <div style="grid-column: 1 / -1; text-align: center; padding: 40px;">
                <i class="fas fa-search" style="font-size: 48px; color: #ccc; margin-bottom: 16px;"></i>
                <p style="font-size: 16px; color: #666;">Tidak ada layanan yang sesuai dengan filter</p>
            </div>
        `;
        return;
    }

    servicesGrid.innerHTML = "";
    services.forEach((service) => {
        const serviceCard = document.createElement("div");
        serviceCard.className = "service-card";
        serviceCard.onclick = () =>
            (window.location.href = "/customer/detail-mitra/minipro");

        const statusClass =
            service.status === "open" ? "status-open" : "status-closed";
        const statusText = service.status === "open" ? "Buka" : "Tutup";

        serviceCard.innerHTML = `
            <div class="service-image">
                <img src="${service.image}" alt="${service.name}">
            </div>
            <div class="service-info">
                <div class="service-header">
                    <h3 class="service-name">${service.name}</h3>
                    <div class="service-rating">
                        <i class="fas fa-star"></i>
                        <span>${service.rating > 0 ? service.rating : "-"} ${
            service.reviews > 0 ? `(${service.reviews})` : ""
        }</span>
                        ${
                            service.completed_bookings > 0
                                ? `<span style="color: #666; margin-left: 8px;">${service.completed_bookings} booking</span>`
                                : ""
                        }
                    </div>
                </div>
                <p class="service-location">
                    <i class="fas fa-map-marker-alt"></i>
                    ${service.kota}, ${service.provinsi}
                </p>
                <p class="service-address">
                    ${service.location}
                </p>
                <div class="service-prices">
                    <div class="price-item">
                        <span>Basic Steam</span>
                        <span class="price">Rp ${service.prices.basic.toLocaleString()}</span>
                    </div>
                    <div class="price-item">
                        <span>Premium Steam</span>
                        <span class="price">Rp ${service.prices.premium.toLocaleString()}</span>
                    </div>
                    <div class="price-item">
                        <span>Complete Detail</span>
                        <span class="price">Rp ${service.prices.complete.toLocaleString()}</span>
                    </div>
                </div>
            </div>
        `;
        servicesGrid.appendChild(serviceCard);
    });

    // Hide pagination when filtering
    const navLeft = document.getElementById("servicesNavLeft");
    const navRight = document.getElementById("servicesNavRight");
    const pagination = document.getElementById("servicesPagination");

    if (navLeft) navLeft.style.display = "none";
    if (navRight) navRight.style.display = "none";
    if (pagination) pagination.style.display = "none";
}

function clearFiltersAndShowAll() {
    // Show pagination again
    const navLeft = document.getElementById("servicesNavLeft");
    const navRight = document.getElementById("servicesNavRight");
    const pagination = document.getElementById("servicesPagination");

    if (navLeft) navLeft.style.display = "";
    if (navRight) navRight.style.display = "";
    if (pagination) pagination.style.display = "";

    // Re-render all data with pagination
    initServicesNavigation();
}

// ========== SERVICES NAVIGATION ==========
function initServicesNavigation() {
    const servicesGrid = document.getElementById("servicesGrid");
    const navLeft = document.getElementById("servicesNavLeft");
    const navRight = document.getElementById("servicesNavRight");
    const pagination = document.getElementById("servicesPagination");

    if (!servicesGrid || !navLeft || !navRight) {
        console.warn("⚠️ Services navigation elements not found");
        return;
    }

    console.log("🎯 Initializing services navigation with data:", servicesData);

    if (!servicesData || servicesData.length === 0) {
        console.warn("⚠️ No services data available");
        servicesGrid.innerHTML =
            '<p style="text-align: center; padding: 40px; color: #666;">Belum ada mitra tersedia</p>';
        return;
    }

    // Calculate items per page based on screen width
    const getItemsPerPage = () => {
        const width = window.innerWidth;
        if (width >= 768) {
            return 15; // 3 kolom x 5 baris
        } else {
            return 10; // 2 kolom x 5 baris atau 1 kolom x 10 baris
        }
    };

    let itemsPerPage = getItemsPerPage();
    let currentPage = 0;
    let totalPages = Math.ceil(servicesData.length / itemsPerPage);

    // Render services for current page
    const renderServices = (page) => {
        servicesGrid.innerHTML = "";

        const startIndex = page * itemsPerPage;
        const endIndex = Math.min(
            startIndex + itemsPerPage,
            servicesData.length
        );
        const pageServices = servicesData.slice(startIndex, endIndex);

        pageServices.forEach((service) => {
            const serviceCard = document.createElement("div");
            serviceCard.className = "service-card";
            serviceCard.onclick = () =>
                (window.location.href = `/customer/detail-mitra/minipro/${service.id}`);

            const statusClass =
                service.status === "open" ? "status-open" : "status-closed";
            const statusText = service.status === "open" ? "Buka" : "Tutup";

            // Debug log for rating
            console.log(
                `📍 Rendering ${service.name}: rating=${service.rating}, reviews=${service.reviews}, completed=${service.completed_bookings}`
            );

            // Rating display: show rating (reviews count) if exists
            let ratingDisplay = `<i class="fas fa-star"></i><span>${
                service.rating > 0 ? service.rating : "-"
            } ${service.reviews > 0 ? `(${service.reviews})` : ""}</span>`;

            // Completed bookings info - show separately
            const completedInfo =
                service.completed_bookings > 0
                    ? `<span style="color: #666; font-size: 13px; margin-left: 8px;">${service.completed_bookings} booking</span>`
                    : "";

            serviceCard.innerHTML = `
                <div class="service-image">
                    <img src="${service.image}" alt="${service.name}">
                </div>
                <div class="service-info">
                    <div class="service-header">
                        <h3 class="service-name">${service.name}</h3>
                        <div class="service-rating">
                            ${ratingDisplay}
                            ${completedInfo}
                        </div>
                    </div>
                    <p class="service-location">
                        <i class="fas fa-map-marker-alt"></i>
                        ${service.kota}, ${service.provinsi}
                    </p>
                    <p class="service-address">
                        ${service.location}
                    </p>
                    <div class="service-prices">
                        ${
                            service.services && service.services.length > 0
                                ? service.services
                                      .slice(0, 3)
                                      .map(
                                          (s) => `
                                <div class="price-item">
                                    <span>${s.name}</span>
                                    <span class="price">Rp ${(
                                        s.price || 0
                                    ).toLocaleString("id-ID")}</span>
                                </div>
                            `
                                      )
                                      .join("")
                                : '<div class="price-item"><span>Belum ada layanan</span><span class="price">-</span></div>'
                        }
                    </div>
                    <div class="service-footer" style="justify-content: center;">
                        <div class="service-status ${statusClass}">
                            <i class="fas fa-circle"></i>
                            <span>${statusText}</span>
                        </div>
                    </div>
                </div>
            `;

            servicesGrid.appendChild(serviceCard);
        });

        updateNavButtons();
    };

    // Generate pagination dots
    const generatePagination = () => {
        if (!pagination) return;
        pagination.innerHTML = "";

        if (totalPages <= 1) return;

        for (let i = 0; i < totalPages; i++) {
            const pageBtn = document.createElement("button");
            pageBtn.className = "pagination-page";
            pageBtn.textContent = i + 1;
            pageBtn.setAttribute("data-page", i);
            if (i === currentPage) pageBtn.classList.add("active");

            pageBtn.addEventListener("click", () => {
                currentPage = i;
                renderServices(currentPage);
                updatePaginationDots();
            });

            pagination.appendChild(pageBtn);
        }
    };

    // Update pagination dots
    const updatePaginationDots = () => {
        if (!pagination) return;
        const pages = pagination.querySelectorAll(".pagination-page");
        pages.forEach((page, index) => {
            page.classList.toggle("active", index === currentPage);
        });
    };

    // Update navigation button states
    const updateNavButtons = () => {
        navLeft.disabled = currentPage === 0;
        navRight.disabled = currentPage === totalPages - 1;
    };

    // Previous page
    navLeft.addEventListener("click", () => {
        if (currentPage > 0) {
            currentPage--;
            renderServices(currentPage);
            updatePaginationDots();
        }
    });

    // Next page
    navRight.addEventListener("click", () => {
        if (currentPage < totalPages - 1) {
            currentPage++;
            renderServices(currentPage);
            updatePaginationDots();
        }
    });

    // Initial render
    generatePagination();
    renderServices(currentPage);

    // Handle window resize to update items per page
    window.addEventListener("resize", () => {
        const newItemsPerPage = getItemsPerPage();
        if (newItemsPerPage !== itemsPerPage) {
            itemsPerPage = newItemsPerPage;
            totalPages = Math.ceil(servicesData.length / itemsPerPage);
            currentPage = 0; // Reset to first page
            generatePagination();
            renderServices(currentPage);
            updateNavButtons();
        }
    });
}

// Auto-initialize aplikasi saat DOM ready
if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", () => {
        console.log("🚀 DOM Content Loaded - Initializing Prismo App");
        initializePrismoApp();
    });
} else {
    console.log("🚀 DOM Already Ready - Initializing Prismo App");
    initializePrismoApp();
}

// Additional safeguard: Re-initialize services if data wasn't ready
window.addEventListener("load", () => {
    console.log("🔄 Window fully loaded - Checking services initialization");
    const servicesGrid = document.getElementById("servicesGrid");
    if (
        servicesGrid &&
        (!servicesGrid.children || servicesGrid.children.length === 0)
    ) {
        console.log("⚠️ Services grid empty after load, re-initializing...");
        if (window.servicesData && window.servicesData.length > 0) {
            initServicesNavigation();
        }
    }
});
