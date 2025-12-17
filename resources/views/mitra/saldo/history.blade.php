<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PRISMO - History Transaksi</title>
    <link rel="icon" type="image/png" href="{{ asset('images/Icon-prismo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/Icon-prismo.png') }}">
    <link rel="stylesheet" href="{{ asset('css/history.css') }}">
    <link rel="preload" href="/images/Icon-prismo.png" as="image">
</head>
<body>
    <div class="container">
        <header class="header">
            <div class="header__content">
                <div class="header__left">
                    <div class="header__brand">
                        <img src="{{ asset('images/logo.png') }}" alt="PRISMO" class="logo" width="120" height="40">
                    </div>
                </div>

                <div class="header__center">
                    <h1 class="header__title">Laporan Keuangan</h1>
                </div>

                <div class="user-menu">
                    <a href="{{ route('mitra.saldo') }}" class="btn btn--back">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor" style="margin-right: 8px;">
                            <path d="M10.707 2.293a1 1 0 010 1.414L6.414 8l4.293 4.293a1 1 0 01-1.414 1.414l-5-5a1 1 0 010-1.414l5-5a1 1 0 011.414 0z"/>
                        </svg>
                        Kembali
                    </a>
                </div>
            </div>
        </header>

        <main class="main">
            <!-- Konten Laporan Keuangan -->
            <div class="laporan-container">

                <div class="laporan-controls">
                    <div class="control-group">
                        <!-- Desktop View Selector -->
                        <div class="view-selector desktop-view">
                            <button class="view-btn active" data-view="daily">Harian</button>
                            <button class="view-btn" data-view="monthly">Bulanan</button>
                            <button class="view-btn" data-view="yearly">Tahunan</button>
                        </div>

                        <!-- Mobile View Selector -->
                        <div class="view-selector-mobile mobile-view">
                            <select class="view-dropdown" id="viewDropdown">
                                <option value="daily">Harian</option>
                                <option value="monthly">Bulanan</option>
                                <option value="yearly">Tahunan</option>
                            </select>
                        </div>

                        <!-- Controls Row untuk Desktop: Filter + Export sejajar -->
                        <div class="controls-row" id="controlsRow">
                            <div class="date-filter" id="dateFilter">
                                <input type="date" id="filter-date" value="{{ isset($filterDate) ? $filterDate->format('Y-m-d') : now()->format('Y-m-d') }}">
                                <button id="filter-btn">Filter Tanggal</button>
                            </div>

                            <div class="export-container">
                                <button class="export-btn">Export</button>
                                <div class="export-dropdown">
                                    <button id="export-pdf">Export PDF</button>
                                    <button id="export-excel">Export Excel</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tampilan Harian -->
                <div class="daily-view laporan-view active">
                    <div class="summary">
                        <div class="summary-item">
                            <h3>Total Transaksi</h3>
                            <p id="total-transactions">{{ $todayCount }}</p>
                        </div>
                        <div class="summary-item">
                            <h3>Total Pendapatan Harian</h3>
                            <p class="total-amount" id="total-income">Rp {{ number_format($todayRevenue, 0, ',', '.') }}</p>
                        </div>
                    </div>

                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th style="width: 25%;">Jenis Layanan</th>
                                    <th style="width: 20%; text-align: center;">Jenis Kendaraan</th>
                                    <th style="width: 25%; text-align: center;">Plat Nomor</th>
                                    <th style="width: 30%; text-align: right;">Sub Total</th>
                                </tr>
                            </thead>
                            <tbody id="transaction-table">
                                @forelse($todayBookings as $booking)
                                <tr>
                                    <td><span class="service-type">{{ $booking->service_type }}</span></td>
                                    <td style="text-align: center;">{{ $booking->vehicle_type ?? '-' }}</td>
                                    <td style="text-align: center;">{{ $booking->vehicle_plate ?? '-' }}</td>
                                    <td class="amount">Rp {{ number_format($booking->base_price, 0, ',', '.') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" style="text-align: center; padding: 40px; color: #999;">
                                        Belum ada transaksi hari ini
                                    </td>
                                </tr>
                                @endforelse
                                @if($todayCount > 0)
                                <tr style="font-weight: bold; background: #f8fafc;">
                                    <td colspan="3" style="text-align: left; padding-left: 20px;">Total Pendapatan Harian:</td>
                                    <td class="amount" style="text-align: right; padding-right: 20px;">Rp {{ number_format($todayRevenue, 0, ',', '.') }}</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Tampilan Bulanan -->
                <div class="monthly-view laporan-view">
                    <div class="summary">
                        <div class="summary-item">
                            <h3>Total Transaksi</h3>
                            <p id="monthly-transactions">{{ $monthlyCount }}</p>
                        </div>
                        <div class="summary-item">
                            <h3>Total Pendapatan Bulan Ini</h3>
                            <p class="total-amount" id="monthly-income">Rp {{ number_format($monthlyRevenue, 0, ',', '.') }}</p>
                        </div>
                    </div>

                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th style="width: 40%;">Bulan</th>
                                    <th style="width: 30%; text-align: center;">Total Transaksi</th>
                                    <th style="width: 30%; text-align: right;">Total Per Bulan</th>
                                </tr>
                            </thead>
                            <tbody id="monthly-table">
                                @forelse($monthlyData as $month)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($month->month . '-01')->format('F Y') }}</td>
                                    <td style="text-align: center;">{{ $month->total_transactions }}</td>
                                    <td class="amount">Rp {{ number_format($month->total_revenue, 0, ',', '.') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" style="text-align: center; padding: 40px; color: #999;">
                                        Belum ada data bulanan
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Tampilan Tahunan -->
                <div class="yearly-view laporan-view">
                    <div class="summary">
                        <div class="summary-item">
                            <h3>Total Transaksi</h3>
                            <p id="yearly-transactions">{{ $yearlyCount }}</p>
                        </div>
                        <div class="summary-item">
                            <h3>Total Pendapatan Tahun Ini</h3>
                            <p class="total-amount" id="yearly-income">Rp {{ number_format($yearlyRevenue, 0, ',', '.') }}</p>
                        </div>
                    </div>

                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th style="width: 40%;">Tahun</th>
                                    <th style="width: 30%; text-align: center;">Total Transaksi</th>
                                    <th style="width: 30%; text-align: right;">Total Per Tahun</th>
                                </tr>
                            </thead>
                            <tbody id="yearly-table">
                                @forelse($yearlyData as $year)
                                <tr>
                                    <td>{{ $year->year }}</td>
                                    <td style="text-align: center;">{{ $year->total_transactions }}</td>
                                    <td class="amount">Rp {{ number_format($year->total_revenue, 0, ',', '.') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" style="text-align: center; padding: 40px; color: #999;">
                                        Belum ada data tahunan
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <footer class="laporan-footer">
                    <p>Laporan dibuat secara otomatis</p>
                </footer>
            </div>
        </main>
    </div>

    <script>
        // Pass server data to JavaScript
        window.reportData = {
            todayBookings: @json($todayBookings->values()),
            todayCount: {{ $todayCount }},
            todayRevenue: {{ $todayRevenue }},
            monthlyData: @json($monthlyData),
            yearlyData: @json($yearlyData)
        };

        console.log('Report Data Loaded:', window.reportData);
    </script>
    <script src="{{ asset('js/history.js') }}"></script>
</body>
</html>
