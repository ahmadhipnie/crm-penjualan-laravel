<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Konfirmasi Pembayaran</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #4CAF50;
            color: white;
            padding: 20px;
            text-align: center;
        }
        .content {
            background-color: #f9f9f9;
            padding: 20px;
            border: 1px solid #ddd;
        }
        .order-details {
            background-color: white;
            padding: 15px;
            margin: 15px 0;
            border-radius: 5px;
        }
        .item {
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }
        .total {
            font-size: 18px;
            font-weight: bold;
            color: #4CAF50;
            margin-top: 15px;
            text-align: right;
        }
        .footer {
            text-align: center;
            padding: 20px;
            color: #777;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>✓ Pembayaran Berhasil</h1>
        </div>

        <div class="content">
            <h2>Terima kasih atas pembelian Anda!</h2>
            <p>Halo {{ $penjualan->user->name }},</p>
            <p>Pembayaran Anda telah berhasil diproses. Berikut adalah detail pesanan Anda:</p>

            <div class="order-details">
                <h3>Detail Pesanan</h3>
                <p><strong>Kode Transaksi:</strong> {{ $penjualan->kode_transaksi }}</p>
                <p><strong>Tanggal Pemesanan:</strong> {{ $penjualan->created_at->format('d F Y H:i') }}</p>
                <p><strong>Status:</strong> <span style="color: #4CAF50;">Sedang Diproses</span></p>

                <h4>Alamat Pengiriman:</h4>
                <p>{{ $penjualan->alamat_pengiriman }}</p>

                <h4>Produk yang Dibeli:</h4>
                @foreach($penjualan->detailPenjualans as $detail)
                <div class="item">
                    <p><strong>{{ $detail->barang->nama_barang }}</strong></p>
                    <p>{{ $detail->qty }} x Rp{{ number_format($detail->harga, 0, ',', '.') }} = Rp{{ number_format($detail->subtotal, 0, ',', '.') }}</p>
                </div>
                @endforeach

                <div class="total">
                    Total Pembayaran: Rp{{ number_format($penjualan->total_harga, 0, ',', '.') }}
                </div>
            </div>

            <p>Pesanan Anda akan segera diproses dan dikirim melalui <strong>{{ $penjualan->jenisEkspedisi->nama_ekspedisi }}</strong>.</p>
            <p>Anda akan menerima notifikasi email ketika pesanan Anda telah dikirim.</p>

            <p>Jika Anda memiliki pertanyaan, silakan hubungi kami.</p>

            <p>Terima kasih telah berbelanja dengan kami!</p>
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} Furniture Store. All rights reserved.</p>
            <p>Email ini dikirim secara otomatis, mohon tidak membalas email ini.</p>
        </div>
    </div>
</body>
</html>
