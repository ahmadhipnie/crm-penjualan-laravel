# 📂 File Management System - CRM Furniture Laravel

## 📁 **Struktur Folder Upload**

```
public/
├── img/
│   └── placeholder-furniture.jpg     # Placeholder untuk gambar furniture
├── gambar_barang/                    # Gambar produk furniture
│   ├── .gitignore
│   └── README.md
├── gambar_bukti_sampai/              # Bukti pengiriman barang
│   ├── .gitignore
│   └── README.md
└── attachments/                      # File attachment email
    ├── email/                        # Attachment email masuk
    ├── reply/                        # Attachment balasan email
    ├── .gitignore
    └── README.md
```

## 🎯 **Fitur yang Telah Diimplementasi**

### **1. Gambar Barang/Furniture**
- **Path**: `public/gambar_barang/`
- **Format**: `{sku_barang}_{tipe}.{ekstensi}`
- **Tipe**: main, detail, angle, room
- **Placeholder**: Otomatis fallback ke `placeholder-furniture.jpg`
- **Model Accessor**: GambarBarang memiliki accessor untuk URL dengan fallback

### **2. Gambar Bukti Sampai**
- **Path**: `public/gambar_bukti_sampai/`
- **Format**: `bukti_{id_penjualan}_{timestamp}.{ekstensi}`
- **Model Accessor**: Penjualan memiliki accessor untuk URL gambar bukti

### **3. File Attachment Email**
- **Path Email**: `public/attachments/email/`
- **Path Reply**: `public/attachments/reply/`
- **Format**: `{type}_{id}_{timestamp}_{filename}`
- **Model Accessor**: Email/Reply/EmailSent memiliki accessor JSON → Array

## 🛠️ **Service Helper**

### **FileUploadService** (`app/Services/FileUploadService.php`)

```php
// Upload gambar barang
FileUploadService::uploadGambarBarang($file, $barangId);

// Upload bukti sampai
FileUploadService::uploadGambarBuktiSampai($file, $penjualanId);

// Upload attachment email
FileUploadService::uploadAttachmentEmail($file, $emailId);

// Upload attachment reply
FileUploadService::uploadAttachmentReply($file, $replyId);

// Delete file
FileUploadService::deleteFile($path);

// Get image with fallback
FileUploadService::getImageUrl($path, 'furniture');
```

## 📝 **Model Accessors**

### **GambarBarang Model**
```php
// Accessor untuk URL dengan fallback
$gambar->gambar_url; // Otomatis fallback ke placeholder

// Full URL
$gambar->full_url; // asset() URL lengkap
```

### **Penjualan Model**
```php
// Gambar bukti sampai
$penjualan->gambar_bukti_sampai; // null atau asset URL

// Generate kode transaksi
Penjualan::generateKodeTransaksi(); // TRX20231203001
```

### **Email/Reply/EmailSent Models**
```php
// Attachments as array with validation
$email->attachments; // Array dengan path, url, filename

// Set attachments
$email->attachments = ['path1', 'path2']; // Otomatis JSON encode
```

## 🎨 **Frontend Implementation**

### **View Integration**
- **Index Page**: Otomatis load gambar dengan fallback placeholder
- **Detail Page**: Gallery dengan thumbnail yang bisa diklik
- **JavaScript**: Interaksi thumbnail dan main image

### **Placeholder System**
- File tidak ditemukan → otomatis placeholder-furniture.jpg
- onerror handler pada tag img untuk fallback
- Konsisten di seluruh sistem

## 🔒 **Security & Best Practices**

### **File Validation**
- Cek eksistensi file sebelum display
- Sanitize nama file untuk mencegah path traversal
- Size limit untuk setiap tipe upload
- Virus scanning (dapat ditambahkan)

### **Path Management**
- Menggunakan public_path() untuk file operations
- asset() untuk URL generation
- Relative path untuk database storage
- Cross-platform path handling

## 📊 **Database Schema Update**

### **Seeder Data**
- Path gambar sekarang menggunakan `gambar_barang/`
- Konsisten dengan struktur folder public
- Sample data 7 produk furniture dengan 4 gambar each

### **Migration Ready**
- Foreign key constraints with SET NULL
- Proper data types untuk file paths
- JSON columns untuk multiple attachments

## 🚀 **Usage Examples**

### **Dalam Controller**
```php
// Upload gambar barang
if ($request->hasFile('gambar')) {
    $path = FileUploadService::uploadGambarBarang(
        $request->file('gambar'), 
        $barang->id
    );
    
    GambarBarang::create([
        'id_barang' => $barang->id,
        'gambar_url' => $path,
        'is_primary' => true
    ]);
}
```

### **Dalam View**
```blade
{{-- Gambar dengan fallback otomatis --}}
<img src="{{ asset($barang->gambarBarangs->first()->gambar_url) }}" 
     alt="{{ $barang->nama_barang }}"
     onerror="this.src='{{ asset('img/placeholder-furniture.jpg') }}'">

{{-- Attachment email --}}
@foreach($email->attachments as $attachment)
    <a href="{{ $attachment['url'] }}" target="_blank">
        {{ $attachment['filename'] }}
    </a>
@endforeach
```

## ✅ **Status Implementasi**

- ✅ Struktur folder dibuat
- ✅ Seeder diupdate dengan path yang benar  
- ✅ Model accessor implemented
- ✅ FileUploadService helper ready
- ✅ Frontend placeholder system
- ✅ Documentation lengkap
- ✅ Git ignore untuk folder upload
- ✅ Security considerations

Website furniture CRM Laravel Anda sekarang memiliki sistem file management yang lengkap dan robust! 🎉
