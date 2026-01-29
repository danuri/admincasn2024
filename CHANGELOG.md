# Changelog

Semua perubahan penting pada proyek ini akan didokumentasikan dalam file ini.

Format ini berdasarkan [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
dan proyek ini mengikuti [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

---

## [2.0.0] - 2025-12-09

### ✨ Fitur Baru

#### Modul Paruh Waktu (Tenaga Kontrak)
- **Draft Kontrak Otomatis**: Sistem pembuatan draft kontrak kerja secara otomatis untuk peserta paruh waktu
  - Generate dokumen kontrak dalam format Word (.docx)
  - Konversi otomatis dokumen Word ke PDF
  - Template kontrak yang dapat dikustomisasi
  - Integrasi dengan sistem Tanda Tangan Elektronik (TTE)
  
- **Manajemen Dokumen Kontrak**:
  - Upload dan penyimpanan dokumen kontrak
  - Sistem folder terorganisir untuk penyimpanan dokumen
  - Download dokumen kontrak yang telah ditandatangani
  
- **Integrasi Tanda Tangan Elektronik**:
  - Pengiriman dokumen ke sistem TTE BSrE (Badan Siber dan Sandi Negara)
  - Tracking status penandatanganan dokumen
  - Validasi dokumen yang telah ditandatangani

#### Ekspor dan Pelaporan
- **Export Data Peserta**: 
  - Export data peserta ke format Excel (.xlsx)
  - Export khusus untuk peserta paruh waktu dengan filter status
  - Export peserta lulus dengan detail lengkap
  - Customizable columns untuk export data

- **Cetak SPRP (Surat Pemberitahuan Hasil Penetapan)**:
  - Generate SPRP dalam format PDF
  - Template SPRP yang profesional
  - Bulk printing untuk multiple peserta

#### Sistem Cache
- **Implementasi Cache**: 
  - Cache untuk meningkatkan performa aplikasi
  - Caching data peserta dan hasil seleksi
  - Cache management untuk data yang sering diakses
  - Clear cache functionality

### 🔧 Perbaikan & Peningkatan

#### Performance
- Optimasi query database untuk load data peserta
- Implementasi lazy loading pada table data
- Caching hasil query yang sering diakses

#### User Interface
- Perbaikan tampilan halaman paruh waktu
  - Improved table layout
  - Better responsive design
  - Enhanced user experience

#### File Management
- Reorganisasi struktur folder penyimpanan
  - Folder terpisah untuk setiap jenis dokumen
  - Penamaan file yang lebih terstruktur
  - Automatic folder creation

### 📝 Perubahan Template
- Update template lokasi penyimpanan
- Perubahan direktori template kontrak
- Standardisasi format template dokumen

### 🐛 Bug Fixes
- Fix issue pada upload dokumen
- Perbaikan error handling pada proses TTE
- Fix encoding issue pada export Excel
- Perbaikan path untuk file download

### 🔒 Security
- Validasi input pada form upload dokumen
- Sanitasi data sebelum export
- Improved authentication untuk akses dokumen sensitif

---

## [1.5.0] - 2025-11-20

### ✨ Fitur Baru
- **Draft Kontrak**: Fitur pembuatan draft kontrak awal
- **Template Management**: Sistem manajemen template dokumen

### 🔧 Perbaikan
- Rename folder download untuk konsistensi
- Change location template untuk aksesibilitas lebih baik
- Generate draft SK (Surat Keputusan)

---

## [1.4.0] - 2025-11-19

### ✨ Fitur Baru
- **Dropzone Integration**: Implementasi Dropzone untuk upload file yang lebih user-friendly
  - Drag & drop file upload
  - Preview file sebelum upload
  - Multiple file upload support

### 🔧 Perbaikan
- Perbaikan struktur direktori template
- Optimasi lokasi penyimpanan file
- Update path handling untuk cross-platform compatibility

---

## [1.3.0] - 2025-01-14

### 🔧 Perbaikan
- **Update Data Peserta**: 
  - Perbaikan update status peserta
  - Validasi data peserta yang lebih ketat
  - Improved error handling

### 📊 Database
- Update struktur tabel peserta
- Optimasi index database
- Migration script untuk data cleanup

---

## [1.2.0] - 2025-01-01 s/d 2025-11-01

### ✨ Fitur Baru

#### Modul PPPK (Pegawai Pemerintah dengan Perjanjian Kerja)
- Manajemen data PPPK
- Upload dokumen persyaratan PPPK
- Verifikasi berkas PPPK

#### Modul SKB (Seleksi Kompetensi Bidang)
- Penjadwalan SKB
- Manajemen peserta SKB
- Input nilai SKB

#### Supervisi dan Monitoring
- Dashboard supervisi untuk admin
- Real-time monitoring proses seleksi
- Reporting dan analytics

#### Surat Menyurat
- Template surat administrasi
- Generate surat otomatis
- Tracking surat keluar

### 🛠 Technical Improvements
- Upgrade ke CodeIgniter 4 (latest stable)
- Implementasi RESTful API
- Improved security measures
- Database optimization
- Code refactoring untuk maintainability

### 📚 Dependencies Updates
- **PHPSpreadsheet**: Untuk export/import Excel
- **Dompdf**: Untuk generate PDF
- **AWS SDK**: Untuk integrasi cloud storage
- **Firebase PHP-JWT**: Untuk authentication API
- **Guzzle HTTP**: Untuk HTTP client

---

## [1.0.0] - 2024

### 🎉 Initial Release
- Sistem manajemen administrasi CASN 2024
- Modul dasar pendaftaran peserta
- Manajemen formasi jabatan
- Sistem autentikasi dan otorisasi
- Dashboard admin

---

## Teknologi yang Digunakan

### Backend
- **Framework**: CodeIgniter 4
- **PHP Version**: 8.1+
- **Database**: MySQL/MariaDB

### Libraries
- PHPSpreadsheet (Export/Import Excel)
- Dompdf (PDF Generation)
- AWS SDK (Cloud Storage)
- PhpOffice (Document Processing)
- Guzzle (HTTP Client)
- Firebase PHP-JWT (Authentication)

### Frontend
- HTML5, CSS3, JavaScript
- Bootstrap (UI Framework)
- jQuery
- Dropzone.js (File Upload)
- Moment.js (Date/Time)
- Toastify.js (Notifications)
- Prism.js (Code Highlighting)
- CKEditor 5 (Rich Text Editor)

---

## Catatan Migrasi

### Migrasi dari v1.x ke v2.0.0

> [!IMPORTANT]
> Pastikan untuk melakukan backup database sebelum melakukan update.

#### Perubahan Database
1. Tabel baru untuk manajemen kontrak
2. Field tambahan pada tabel peserta untuk status paruh waktu
3. Tabel untuk tracking dokumen TTE

#### Konfigurasi Baru
1. Setup credential BSrE untuk TTE
2. Konfigurasi AWS S3 untuk penyimpanan dokumen (opsional)
3. Update environment variables untuk cache

#### File & Direktori
1. Folder baru: `writable/uploads/kontrak/`
2. Folder template: `app/Views/templates/kontrak/`
3. Cache directory: `writable/cache/`

---

## Kontributor

- **Danuri** - Lead Developer & Maintainer

---

## Lisensi

Proyek ini dilisensikan di bawah lisensi yang tercantum dalam file [LICENSE](LICENSE).

---

## Support & Dokumentasi

Untuk pertanyaan, bug reports, atau feature requests, silakan hubungi tim development.

### Changelog Notes
- ✨ Fitur Baru
- 🔧 Perbaikan & Peningkatan
- 🐛 Bug Fixes
- 🔒 Security
- 📝 Dokumentasi
- 🛠 Technical
- 📊 Database
- 🎨 UI/UX
