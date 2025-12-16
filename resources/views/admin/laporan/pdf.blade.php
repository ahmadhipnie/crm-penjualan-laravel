<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Penjualan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h2 {
            margin: 5px 0;
            color: #333;
        }
        .info {
            margin-bottom: 20px;
        }
        .info table {
            width: 100%;
        }
        .info td {
            padding: 5px;
        }
        .summary {
            background: #f5f5f5;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .summary-item {
            display: inline-block;
            width: 48%;
            margin-bottom: 10px;
        }
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table.data-table th {
            background-color: #667eea;
            color: white;
            padding: 10px;
            text-align: left;
            font-weight: bold;
        }
        table.data-table td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }
        table.data-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .badge {
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
        }
        .badge-success {
            background-color: #28a745;
            color: white;
        }
        .badge-warning {
            background-color: #ffc107;
            color: #333;
        }
        .badge-info {
            background-color: #17a2b8;
            color: white;
        }
        .badge-primary {
            background-color: #007bff;
            color: white;
        }
        .badge-danger {
            background-color: #dc3545;
            color: white;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>LAPORAN PENJUALAN</h2>
        <p>Furniture Store</p>
    </div>

    <div class="info">
        <table>
            <tr>
                <td width="150"><strong>Tanggal Cetak:</strong></td>
                <td>{{ date('d F Y H:i:s') }}</td>
            </tr>
            @if($startDate && $endDate)
            <tr>
                <td><strong>Periode:</strong></td>
                <td>{{ \Carbon\Carbon::parse($startDate)->format('d F Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d F Y') }}</td>
            </tr>
            @elseif($startDate)
            <tr>
                <td><strong>Dari Tanggal:</strong></td>
                <td>{{ \Carbon\Carbon::parse($startDate)->format('d F Y') }}</td>
            </tr>
            @elseif($endDate)
            <tr>
                <td><strong>Sampai Tanggal:</strong></td>
                <td>{{ \Carbon\Carbon::parse($endDate)->format('d F Y') }}</td>
            </tr>
            @endif
            @if($status && $status != 'all')
            <tr>
                <td><strong>Status:</strong></td>
                <td>{{ ucfirst($status) }}</td>
            </tr>
            @endif
        </table>
    </div>

    <div class="summary">
        <h3 style="margin-top: 0;">Ringkasan</h3>
        <div class="summary-item">
            <strong>Total Transaksi:</strong> {{ $totalPenjualan }} transaksi
        </div>
        <div class="summary-item">
            <strong>Total Pendapatan:</strong> Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
        </div>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 15%;">No Invoice</th>
                <th style="width: 12%;">Tanggal</th>
                <th style="width: 18%;">Customer</th>
                <th style="width: 25%;">Produk</th>
                <th style="width: 15%;">Total Harga</th>
                <th style="width: 10%;">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($penjualan as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->kode_transaksi }}</td>
                <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y') }}</td>
                <td>
                    {{ $item->user->nama ?? '-' }}<br>
                    <small style="color: #666;">{{ $item->user->email ?? '-' }}</small>
                </td>
                <td>
                    @foreach($item->detailPenjualans as $detail)
                        {{ $detail->barang->nama_barang ?? '-' }} ({{ $detail->qty }}x)
                        @if(!$loop->last), @endif
                    @endforeach
                </td>
                <td>Rp {{ number_format($item->total_harga, 0, ',', '.') }}</td>
                <td>
                    @if($item->status == 'selesai')
                        <span class="badge badge-success">Selesai</span>
                    @elseif($item->status == 'menunggu_pembayaran')
                        <span class="badge badge-warning">Menunggu Pembayaran</span>
                    @elseif($item->status == 'sedang_diproses')
                        <span class="badge badge-info">Sedang Diproses</span>
                    @elseif($item->status == 'dikirim')
                        <span class="badge badge-primary">Dikirim</span>
                    @elseif($item->status == 'sampai')
                        <span class="badge badge-primary">Sampai</span>
                    @else
                        <span class="badge badge-danger">Dibatalkan</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak pada {{ date('d F Y H:i:s') }} | Furniture Store CRM System</p>
    </div>
</body>
</html>
