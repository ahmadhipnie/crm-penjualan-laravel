<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Promo Furniture</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .content {
            background: #fff;
            padding: 30px 20px;
            border: 1px solid #e0e0e0;
        }
        .greeting {
            font-size: 18px;
            margin-bottom: 20px;
            color: #667eea;
        }
        .message {
            margin-bottom: 30px;
            white-space: pre-line;
        }
        .products-section {
            margin-top: 30px;
        }
        .products-title {
            font-size: 20px;
            color: #667eea;
            margin-bottom: 20px;
            border-bottom: 2px solid #667eea;
            padding-bottom: 10px;
        }
        .product-item {
            display: flex;
            margin-bottom: 20px;
            padding: 15px;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            background: #f9f9f9;
        }
        .product-image {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 8px;
            margin-right: 15px;
        }
        .product-info {
            flex: 1;
        }
        .product-name {
            font-size: 18px;
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
        }
        .product-price {
            font-size: 20px;
            color: #27ae60;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .product-desc {
            color: #666;
            font-size: 14px;
            margin-bottom: 10px;
        }
        .product-stock {
            color: #999;
            font-size: 13px;
        }
        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
            font-weight: bold;
        }
        .footer {
            background: #f5f5f5;
            padding: 20px;
            text-align: center;
            color: #666;
            font-size: 12px;
            border-radius: 0 0 10px 10px;
            border: 1px solid #e0e0e0;
            border-top: none;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1 style="margin: 0;">Furniture Store</h1>
        <p style="margin: 10px 0 0 0;">Furniture Berkualitas untuk Rumah Anda</p>
    </div>

    <div class="content">
        <div class="greeting">
            Halo, {{ $customerName }}! 👋
        </div>

        <div class="message">
            {!! nl2br(e($messageContent)) !!}
        </div>

        @if(isset($products) && count($products) > 0)
        <div class="products-section">
            <div class="products-title">
                🛋️ Produk Pilihan Kami
            </div>

            @foreach($products as $product)
            <div class="product-item">
                @if($product->gambarBarangs->isNotEmpty())
                <img src="{{ url($product->gambarBarangs->first()->gambar_url) }}"
                     alt="{{ $product->nama_barang }}"
                     class="product-image">
                @else
                <img src="{{ url('img/placeholder-furniture.png') }}"
                     alt="{{ $product->nama_barang }}"
                     class="product-image">
                @endif

                <div class="product-info">
                    <div class="product-name">{{ $product->nama_barang }}</div>
                    <div class="product-price">Rp {{ number_format($product->harga, 0, ',', '.') }}</div>
                    <div class="product-desc">{{ Str::limit($product->deskripsi, 100) }}</div>
                    <div class="product-stock">Stok tersedia: {{ $product->stok }} unit</div>
                </div>
            </div>
            @endforeach
        </div>
        @endif

        <center>
            <a href="{{ url('/') }}" class="cta-button">
                🛒 Belanja Sekarang
            </a>
        </center>
    </div>

    <div class="footer">
        <p>Email ini dikirim dari Furniture Store</p>
        <p>Terima kasih atas kepercayaan Anda kepada kami! 🙏</p>
    </div>
</body>
</html>
