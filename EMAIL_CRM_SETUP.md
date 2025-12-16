# Email CRM Feature - Setup Guide

## Fitur Email CRM

Fitur ini memungkinkan sistem untuk:
- ✅ Otomatis mengambil email dari inbox Gmail menggunakan IMAP
- ✅ Menyimpan email ke database dengan status (unread, read, replied)
- ✅ Menampilkan daftar email dengan filter status
- ✅ Membaca detail email dan otomatis mengubah status menjadi "read"
- ✅ Membalas email langsung dari sistem
- ✅ Menyimpan lampiran email
- ✅ Melihat history balasan

## Setup Instructions

### 1. Enable IMAP Extension PHP

Pastikan extension `php_imap` sudah enabled di `php.ini`:

```ini
extension=imap
```

Restart web server setelah mengubah php.ini.

### 2. Update .env File

Tambahkan konfigurasi IMAP ke file `.env`:

```env
# Existing SMTP Config (untuk kirim email)
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=hypenieyt@gmail.com
MAIL_PASSWORD=cjnpuhrtcqodlkgr
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="hypenieyt@gmail.com"
MAIL_FROM_NAME="${APP_NAME}"

# Add IMAP Config (untuk terima email)
MAIL_IMAP_HOST=imap.gmail.com
MAIL_IMAP_PORT=993
```

**PENTING:** IMAP host berbeda dengan SMTP host!
- SMTP (kirim): `smtp.gmail.com`
- IMAP (terima): `imap.gmail.com`

### 3. Enable Gmail IMAP

1. Login ke Gmail (hypenieyt@gmail.com)
2. Klik ikon gear (Settings) → See all settings
3. Tab "Forwarding and POP/IMAP"
4. Enable "IMAP Access"
5. Save Changes

### 4. Google App Password

Pastikan menggunakan App Password (bukan password biasa):
- Current App Password: `cjnpuhrtcqodlkgr`

Jika belum punya, buat di: https://myaccount.google.com/apppasswords

### 5. Test Fetch Emails Manually

Jalankan command untuk test fetch emails:

```bash
php artisan emails:fetch
```

Output yang diharapkan:
```
Starting to fetch emails...
Connected to mailbox successfully
Found X new email(s)
Saved email: [Subject]
Email fetch completed successfully
```

### 6. Setup Schedule Worker

Untuk menjalankan otomatis setiap 5 menit, jalankan:

```bash
php artisan schedule:work
```

Command ini akan running di foreground. Untuk production, gunakan cron job:

**Windows Task Scheduler:**
- Program: `php`
- Arguments: `C:\path\to\artisan schedule:run`
- Trigger: Every 1 minute

**Linux Cron:**
```bash
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

## File Structure

```
app/
├── Console/
│   ├── Commands/
│   │   └── FetchEmails.php          # Command untuk fetch emails
│   └── Kernel.php                    # Schedule configuration
├── Http/Controllers/admin/
│   └── EmailAdminController.php      # Controller untuk email management
└── Models/
    ├── Email.php                      # Model Email
    └── Reply.php                      # Model Reply

resources/views/admin/emails/
├── index.blade.php                    # Daftar email dengan filter status
├── show.blade.php                     # Detail email
└── reply.blade.php                    # Form balas email

public/
└── attachments/                       # Folder untuk menyimpan lampiran

database/migrations/
├── *_create_emails.php                # Migration tabel emails
└── *_create_replies.php               # Migration tabel replies
```

## Menu Navigation

Menu "Emails" sudah ditambahkan di sidebar admin dengan section "CRM":

```
├── SALES MANAGEMENT
│   ├── Penjualan
│   └── Laporan (Coming Soon)
├── CRM
│   └── Emails ← NEW
└── ACCOUNT
    ├── Profil
    └── Logout
```

## Routes

| Method | URI | Action | Description |
|--------|-----|--------|-------------|
| GET | `/admin/emails` | index | Daftar email dengan filter |
| GET | `/admin/emails/{id}` | show | Detail email (auto mark as read) |
| GET | `/admin/emails/{id}/reply` | reply | Form balas email |
| POST | `/admin/emails/{id}/reply` | sendReply | Kirim balasan |
| DELETE | `/admin/emails/{id}` | destroy | Hapus email |
| POST | `/admin/emails/{id}/mark-unread` | markAsUnread | Tandai belum dibaca |

## Status Email

1. **unread** (Belum Dibaca) - Badge Warning
   - Email baru dari fetch command
   - Ditampilkan dengan background highlight di list

2. **read** (Sudah Dibaca) - Badge Info
   - Otomatis berubah saat membuka detail email
   - Menandakan email sudah dilihat

3. **replied** (Sudah Dibalas) - Badge Success
   - Otomatis berubah setelah mengirim balasan
   - Menandakan email sudah ditindaklanjuti

## Features

### 1. Auto Fetch Emails
- Berjalan otomatis setiap 5 menit via schedule
- Hanya mengambil email yang belum dibaca (UNSEEN)
- Mendecode subject dan body email
- Menyimpan attachment ke folder `public/attachments`

### 2. Email List
- Filter by status (All, Unread, Read, Replied)
- Badge counter untuk setiap status
- Highlight untuk email unread
- Icon paperclip untuk email dengan attachment
- Timestamp relative (e.g., "5 minutes ago")

### 3. Email Detail
- Auto mark as read saat dibuka
- Tampilkan isi email dengan formatting
- Download attachment
- Lihat history balasan
- Action buttons: Reply, Mark Unread, Delete

### 4. Reply Email
- Preview email asli
- Subject auto-populated dengan "Re:"
- Rich text editor untuk body
- Multiple file attachments
- Auto update status ke "replied"

## Troubleshooting

### Error: "Cannot connect to mailbox: invalid remote specification"

**Penyebab:** IMAP host salah (menggunakan SMTP host)

**Solusi:**
1. Pastikan di `.env` ada:
   ```env
   MAIL_IMAP_HOST=imap.gmail.com
   MAIL_IMAP_PORT=993
   ```
2. Jalankan `php artisan config:clear`

### Error: "Login aborted" atau "AUTHENTICATE failed"

**Penyebab:** Autentikasi Gmail gagal

**Solusi:**

**Opsi 1: Gunakan App Password (RECOMMENDED)**
1. Buka https://myaccount.google.com/apppasswords
2. Pilih "Mail" dan device "Other"
3. Generate password baru
4. Update di `.env`:
   ```env
   MAIL_PASSWORD=your-new-app-password
   ```
5. Jalankan `php artisan config:clear`

**Opsi 2: Enable Less Secure Apps** (Deprecated by Google)
⚠️ Google sudah tidak support ini lagi sejak May 2022

**Opsi 3: Enable 2-Step Verification & App Password**
1. Buka https://myaccount.google.com/security
2. Enable "2-Step Verification"
3. Kembali ke step Opsi 1

### Error: "IMAP is disabled"

**Solusi:**
1. Login ke Gmail
2. Settings (⚙️) → See all settings
3. Tab "Forwarding and POP/IMAP"
4. Enable "IMAP Access"
5. Save Changes
6. Tunggu 5-10 menit untuk propagasi

### Error: "Cannot connect to mailbox"

**Solusi:**
1. Cek IMAP enabled di Gmail
2. Pastikan App Password benar
3. Cek firewall tidak block port 993
4. Verify extension `php_imap` enabled

### Error: "imap functions not available"

**Solusi:**
```bash
# Windows XAMPP
Enable extension=imap di php.ini

# Linux
sudo apt-get install php-imap
sudo systemctl restart apache2
```

### Email tidak ter-fetch

**Solusi:**
1. Test manual: `php artisan emails:fetch`
2. Cek log: `storage/logs/laravel.log`
3. Pastikan schedule:work running
4. Verify ada email baru di inbox

## Security Notes

⚠️ **Important:**
- Jangan commit `.env` ke repository
- Gunakan App Password, bukan password utama Gmail
- Limit file upload size di server (php.ini: upload_max_filesize)
- Validate file types untuk attachment
- Sanitize email body untuk prevent XSS

## Production Checklist

- [ ] Enable IMAP extension
- [ ] Update .env dengan credentials production
- [ ] Setup cron job untuk schedule
- [ ] Set proper file permissions untuk folder attachments (755)
- [ ] Monitor disk space untuk attachments
- [ ] Setup email notification untuk fetch errors
- [ ] Configure rate limiting untuk email send
- [ ] Setup backup untuk database emails

## Command Reference

```bash
# Fetch emails manually
php artisan emails:fetch

# Run scheduler (development)
php artisan schedule:work

# Run scheduler once (for cron)
php artisan schedule:run

# Clear cache
php artisan config:clear
php artisan cache:clear
```

---

**Created:** December 6, 2025
**Version:** 1.0
**Laravel Version:** 10.x
