# 🪑 CRM Furniture Laravel - Installation Guide

## 🚀 **Quick Setup**

```bash
# Clone atau extract project
cd crm_laravel

# Install dependencies
composer install
npm install

# Copy environment
cp .env.example .env

# Generate key
php artisan key:generate

# Setup database di .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=crm_furniture
DB_USERNAME=root
DB_PASSWORD=

# Run migrations dan seeders
php artisan migrate:fresh --seed

# Start development server
php artisan serve
```

## 📱 **Features Overview**

### **🏠 Beranda (Homepage)**
- **URL**: `/` atau `/beranda`
- **Features**: 
  - Display furniture catalog dengan gambar
  - Search by nama barang
  - Filter by kategori furniture
  - Pagination
  - Responsive design

### **🛏️ Detail Produk**
- **URL**: `/beranda/{id}`
- **Features**:
  - Galeri gambar dengan thumbnail
  - Detail lengkap furniture
  - Spesifikasi dan deskripsi
  - Info harga dan stok

### **🗂️ Database Models**
1. **User** - Customer dan admin
2. **AlamatUser** - Alamat pengiriman
3. **Kategori** - Kategori furniture (Sofa, Meja, Lemari, dll)
4. **Barang** - Produk furniture
5. **GambarBarang** - Multiple gambar per produk
6. **JenisEkspedisi** - JNE, J&T, TIKI, dll
7. **Penjualan** - Transaksi penjualan
8. **DetailPenjualan** - Item dalam transaksi
9. **Email** - Email customer
10. **Reply** - Balasan email
11. **EmailSent** - Log email terkirim

## 🎨 **Sample Data**

### **Furniture Products** (7 items):
1. **Sofa Minimalis Modern** - Rp 3,500,000
2. **Meja Makan Kayu Jati** - Rp 2,800,000  
3. **Lemari Pakaian 3 Pintu** - Rp 4,200,000
4. **Kursi Kantor Ergonomis** - Rp 1,500,000
5. **Tempat Tidur King Size** - Rp 5,500,000
6. **Rak Buku Minimalis** - Rp 800,000
7. **Meja Kerja Home Office** - Rp 1,200,000

### **Categories**:
- Sofa & Kursi
- Meja & Makan  
- Lemari & Rak
- Tempat Tidur
- Aksesoris Rumah

## 🖼️ **Image Management**

### **Directory Structure**:
```
public/
├── img/placeholder-furniture.jpg
├── gambar_barang/          # Product images
├── gambar_bukti_sampai/    # Delivery proof
└── attachments/            # Email files
```

### **Placeholder System**:
- Otomatis fallback jika gambar tidak ada
- Menggunakan `placeholder-furniture.jpg`
- JavaScript error handler untuk backup

## 🔧 **Development Notes**

### **Key Controllers**:
- `BerandaController` - Homepage dan product display
- Models dengan relationships lengkap
- File management service

### **Routes**:
```php
Route::get('/', [BerandaController::class, 'index'])->name('beranda');
Route::get('/beranda', [BerandaController::class, 'index']);
Route::get('/beranda/{id}', [BerandaController::class, 'detail']);
```

### **Blade Views**:
- `beranda/index.blade.php` - Product catalog
- `beranda/detail_barang.blade.php` - Product detail

## ⚡ **Performance Tips**

### **Database**:
- Indexed columns untuk search
- Eager loading untuk relationships
- Pagination untuk large datasets

### **Images**:
- Placeholder system untuk performance
- Lazy loading bisa ditambahkan
- Image optimization recommended

## 📱 **Responsive Design**

- Bootstrap 5 integration
- Mobile-first approach  
- Touch-friendly navigation
- Optimized untuk semua device size

## 🛡️ **Security Features**

- File upload validation
- XSS protection
- CSRF tokens
- Proper input sanitization

---

**Ready to use!** 🎉 

Akses homepage di `http://localhost:8000` untuk melihat catalog furniture yang telah di-seed dengan data sample.

Sistem file management sudah siap, tinggal implementasi upload form sesuai kebutuhan bisnis Anda.
