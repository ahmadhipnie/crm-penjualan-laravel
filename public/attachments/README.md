# Folder Attachments

Folder ini digunakan untuk menyimpan file attachment dari sistem email CRM.

## Struktur Folder:
- `email/` - Attachment dari email masuk
- `reply/` - Attachment dari balasan email

## Struktur Penamaan File:
### Email Attachments:
- Format: `email_{id_email}_{timestamp}_{original_filename}`
- Contoh: `email_1_1701234567_document.pdf`

### Reply Attachments:
- Format: `reply_{id_reply}_{timestamp}_{original_filename}`
- Contoh: `reply_1_1701234567_response.pdf`

## Spesifikasi:
- Tipe file yang diizinkan: PDF, DOC, DOCX, XLS, XLSX, JPG, PNG, ZIP
- Ukuran maksimal: 10MB per file
- Encoding: UTF-8 untuk nama file

## Keamanan:
- File di-scan untuk virus sebelum disimpan
- Nama file di-sanitize untuk mencegah path traversal
- Access log dicatat untuk audit trail
