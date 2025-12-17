<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .text-right {
            text-align: right;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
    </style>
</head>
<body>
    <h1>{{ $title }}</h1>
    
    @if($type === 'daily')
        <table>
            <thead>
                <tr>
                    <th>Jenis Layanan</th>
                    <th>Jenis Kendaraan</th>
                    <th>Plat Nomor</th>
                    <th class="text-right">Sub Total</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $booking)
                    <tr>
                        <td>{{ $booking->service_type }}</td>
                        <td>{{ $booking->vehicle_type }}</td>
                        <td>{{ $booking->vehicle_plate }}</td>
                        <td class="text-right">Rp {{ number_format($booking->base_price, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="text-align: center;">Tidak ada data</td>
                    </tr>
                @endforelse
                @if($data->count() > 0)
                    <tr>
                        <td colspan="3" style="text-align: right; font-weight: bold;">Total:</td>
                        <td class="text-right" style="font-weight: bold;">Rp {{ number_format($data->sum('base_price'), 0, ',', '.') }}</td>
                    </tr>
                @endif
            </tbody>
        </table>
    @elseif($type === 'monthly')
        <table>
            <thead>
                <tr>
                    <th>Bulan</th>
                    <th class="text-right">Total Transaksi</th>
                    <th class="text-right">Total Pendapatan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $item)
                    <tr>
                        <td>{{ $item->month }}</td>
                        <td class="text-right">{{ $item->total_transactions }}</td>
                        <td class="text-right">Rp {{ number_format($item->total_revenue, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" style="text-align: center;">Tidak ada data</td>
                    </tr>
                @endforelse
                @if($data->count() > 0)
                    <tr>
                        <td style="text-align: right; font-weight: bold;">Total:</td>
                        <td class="text-right" style="font-weight: bold;">{{ $data->sum('total_transactions') }}</td>
                        <td class="text-right" style="font-weight: bold;">Rp {{ number_format($data->sum('total_revenue'), 0, ',', '.') }}</td>
                    </tr>
                @endif
            </tbody>
        </table>
    @else
        <table>
            <thead>
                <tr>
                    <th>Tahun</th>
                    <th class="text-right">Total Transaksi</th>
                    <th class="text-right">Total Pendapatan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $item)
                    <tr>
                        <td>{{ $item->year }}</td>
                        <td class="text-right">{{ $item->total_transactions }}</td>
                        <td class="text-right">Rp {{ number_format($item->total_revenue, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" style="text-align: center;">Tidak ada data</td>
                    </tr>
                @endforelse
                @if($data->count() > 0)
                    <tr>
                        <td style="text-align: right; font-weight: bold;">Total:</td>
                        <td class="text-right" style="font-weight: bold;">{{ $data->sum('total_transactions') }}</td>
                        <td class="text-right" style="font-weight: bold;">Rp {{ number_format($data->sum('total_revenue'), 0, ',', '.') }}</td>
                    </tr>
                @endif
            </tbody>
        </table>
    @endif
    
    <div class="footer">
        <p>Dicetak pada: {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>
</body>
</html>
