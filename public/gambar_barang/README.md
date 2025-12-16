# Folder Gambar Barang

Folder ini digunakan untuk menyimpan gambar-gambar furniture/barang.

## Struktur Penamaan File:
- Format: `{sku_barang}_{tipe}.{ekstensi}`
- Contoh: `SFA001_main.jpg`, `SFA001_detail.jpg`
- Tipe gambar: main (utama), detail, angle, room

## Ukuran yang Disarankan:
- Gambar utama: 800x600px
- Gambar detail: 600x600px
- Format: JPG, PNG
- Ukuran maksimal: 2MB per file

## Penggunaan:
Gambar akan otomatis di-load melalui model GambarBarang dengan fallback ke placeholder jika file tidak ditemukan.
