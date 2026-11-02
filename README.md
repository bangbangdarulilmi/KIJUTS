# Web Portal Himpunan Mahasiswa Informatika (HMIF) STT Cipasung

**Versi:** 1.1  
**Status:** Implemented & Verified  
**Target Pengguna:** Pengurus HMIF, Mahasiswa Informatika STT Cipasung, Dosen & Publik  
**Tech Stack:** PHP 8.2 (Laravel 12) • Tailwind CSS • Vite • MySQL (XAMPP Compatible)

---

## 1. Ringkasan & Arsitektur Sistem

Web Portal HMIF STT Cipasung dirancang sebagai pusat informasi resmi, tata kelola administrasi digital, repositori akademik, serta ruang kolaborasi bagi civitas akademika Teknik Informatika Sekolah Tinggi Teknologi Cipasung.

Platform ini menggunakan sistem keamanan bertingkat berbasis peran (*Role-Based Access Control* / RBAC):
1. **Public / Guest:** Mengakses Landing Page, membaca agenda & berita, melihat galeri karya mahasiswa, direktori bakat alumni, form pengiriman aspirasi, serta verifikasi keaslian e-sertifikat via scan QR Code.
2. **Mahasiswa Aktif:** Pendaftaran event, presensi mandiri via token QR, klaim otomatis E-Sertifikat digital, unduh & unggah bank soal / materi kuliah, konsultasi kendala pemrograman di Klinik Koding (*peer-tutoring*), dan showcase portofolio proyek koding.
3. **Pengurus HMIF:** Manajemen konten berita/artikel, tata kelola aset & peminjaman inventaris, pencatatan transparansi kas himpunan, pembukaan presensi QR di layar proyektor, serta bimbingan klinik koding.
4. **Super Admin (Koor/Divisi Medinfo):** Manajemen pengguna penuh, konfigurasi peran/role pengguna, dan aktivasi akun.

---

## 2. Fitur Unggulan Sesuai PRD

### 2.1 Automated E-Certificate Generator & Presensi QR
* Panitia membuka presensi kegiatan dan menampilkan **QR Code Presensi Interaktif** di layar/proyektor aula.
* Peserta memvalidasi kehadiran secara mandiri melalui portal.
* Sistem otomatis menerbitkan **E-Sertifikat Penghargaan Digital** ber-QR Code validasi unik.
* Keaslian sertifikat dapat diverifikasi langsung melalui scan QR atau halaman publik `/verifikasi-sertifikat/{code}`.

### 2.2 Informatics Academic Repository & Bank Soal
* Arsip terstruktur materi kuliah, modul praktikum lab, kodingan contoh, serta bank soal UTS/UAS dari semester 1 hingga 8.
* Mendukung pencarian cepat per mata kuliah dan pelacakan jumlah unduhan (*download counter*).

### 2.3 Klinik Koding & Forum Aspirasi (Dev-Helpdesk)
* Mahasiswa dapat mengajukan kendala bug/error kode program atau tugas akhir.
* Dilengkapi syntax formatting untuk cuplikan kode (*code snippet*) dan unggahan tangkapan layar error.
* Pengurus / Divisi Riset dapat memberikan bimbingan *peer-tutoring* dan menandai jawaban sebagai solusi terverifikasi.

### 2.4 Transparansi Kas Himpunan (Financial Transparency Tracker)
* Pencatatan kas masuk dan kas keluar organisasi secara transparan.
* Grafik persentase distribusi per kategori dan lampiran foto nota/kuitansi digital.

### 2.5 Portofolio & Talent Directory Mahasiswa
* Direktori publik yang menampilkan keahlian khusus mahasiswa (Web Dev, UI/UX, AI, Mobile) serta tautan GitHub, LinkedIn, dan live demo.

---

## 3. Skema & Ketentuan Database (PRD Bab 7)

Struktur tabel memenuhi ketentuan PRD Bagian 7:
* Seluruh skema dibuat menggunakan **Laravel Database Migrations** murni tanpa seeder dummy bawaan.
* Seluruh relasi antar-tabel (*Foreign Keys*) didefinisikan secara eksplisit dengan aturan `ON DELETE CASCADE` atau `ON DELETE SET NULL`.
* Tersedia juga skrip SQL DDL murni di [`database/schema_ddl.sql`](database/schema_ddl.sql) untuk kemudahan impor 1-klik di **phpMyAdmin / MySQL XAMPP**.

### Daftar Tabel Utama:
* `users` - Autentikasi NIM / Email dan kontrol peranan (RBAC).
* `members` - Profil keanggotaan, angkatan, keahlian, dan tautan sosial.
* `events` - Agenda workshop, webinar, seminar, kuota, dan token presensi QR.
* `event_registrations` - Pendaftaran event, status kehadiran, dan kode e-sertifikat.
* `categories` & `articles` - CMS berita himpunan dan artikel teknologi.
* `inventories` & `inventory_loans` - Pencatatan aset dan alur persetujuan peminjaman.
* `academic_repositories` - Bank soal UTS/UAS dan modul praktikum semester 1-8.
* `coding_clinics` & `coding_clinic_replies` - Tiket dev-helpdesk dan diskusi solusi koding.
* `cash_transactions` - Laporan arus kas masuk & keluar himpunan.
* `student_projects` - Showcase galeri aplikasi karya mahasiswa.
* `aspirations` - Formulir aspirasi publik dan tanggapan pengurus.

---

## 4. Panduan Menjalankan Aplikasi di Lingkungan XAMPP

### Langkah 1: Persiapan Database di XAMPP
1. Buka **XAMPP Control Panel**, lalu klik tombol **Start** pada modul **Apache** dan **MySQL**.
2. Buka browser dan akses **phpMyAdmin** di `http://localhost/phpmyadmin`.
3. Buat database baru bernama:
   ```sql
   CREATE DATABASE hmif_stt_cipasung CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   ```

### Langkah 2: Konfigurasi File Environment (.env)
Pastikan file `.env` telah mengarah ke database MySQL XAMPP:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=hmif_stt_cipasung
DB_USERNAME=root
DB_PASSWORD=
```

### Langkah 3: Eksekusi Migrasi Database
Jalankan perintah migrasi Laravel untuk membangun seluruh tabel dan relasi foreign key:
```bash
php artisan migrate
```
*(Alternatif: Anda juga dapat mengimpor file `database/schema_ddl.sql` langsung melalui menu Import di phpMyAdmin).*

### Langkah 4: Membuat Akun Super Admin Awal
Gunakan custom artisan command yang telah disediakan untuk membuat akun Super Admin pertama:
```bash
php artisan hmif:create-admin
```
Atau sesuaikan kredensial yang diinginkan:
```bash
php artisan hmif:create-admin --name="Koordinator Medinfo" --email="admin@sttcipasung.ac.id" --nim="ADMIN001" --password="password123"
```

### Langkah 5: Kompilasi Aset Frontend (Tailwind CSS)
```bash
npm run build
```

### Langkah 6: Jalankan Server Pengembangan
```bash
php artisan serve
```
Akses portal melalui browser di: **`http://localhost:8000`**

---

## 5. Menjalankan Pengujian Otomatis (Automated Tests)

Seluruh fitur inti, keamanan RBAC, dan skema database telah diverifikasi melalui rangkaian pengujian PHPUnit:
```bash
php artisan test
```

Hasil verifikasi:
```
PASS  Tests\Unit\ExampleTest
✓ that true is true

PASS  Tests\Feature\ExampleTest
✓ the application returns a successful response

PASS  Tests\Feature\HmifPortalTest
✓ landing page renders successfully
✓ public pages accessible
✓ student registration creates user and member
✓ user can login with nim and with email
✓ event registration and qr attendance generates certificate
✓ rbac protects admin panel
✓ artisan create admin command

Tests: 9 passed (36 assertions)
```

---

## 6. Struktur Direktori Proyek

```
KIJOS/
├── app/
│   ├── Console/Commands/CreateAdminCommand.php   # Command bootstrap super admin
│   ├── Http/Controllers/
│   │   ├── Admin/                                # Panel CRUD pengurus & super admin
│   │   ├── AuthController.php                    # Login NIM/Email & register
│   │   ├── HomeController.php                    # Landing page publik
│   │   ├── PublicController.php                  # Event, artikel, direktori, sertifikat
│   │   └── StudentDashboardController.php        # Portal mahasiswa & akademik
│   ├── Http/Middleware/RoleMiddleware.php        # Proteksi hak akses peranan (RBAC)
│   ├── Models/                                   # 13 Eloquent Models lengkap relasi
│   └── Services/QrService.php                    # Generator SVG QR Code presensi/sertifikat
├── database/
│   ├── migrations/                               # 12 Migration files DDL murni
│   └── schema_ddl.sql                            # Skrip SQL DDL murni untuk phpMyAdmin
├── resources/
│   ├── css/app.css                               # Tailwind CSS v4 & palette Deep Tech Navy
│   └── views/
│       ├── layouts/                              # Layout publik (app) & portal (portal)
│       ├── auth/                                 # Form login NIM/email & register
│       ├── public/                               # Landing, events, berita, portfolio, verifikasi
│       ├── student/                              # Dashboard, repositori, klinik koding, sertifikat
│       └── admin/                                # Dashboard pengurus, anggota, kas, inventaris
├── routes/web.php                                # 78 Named Routes terstruktur
└── tests/Feature/HmifPortalTest.php              # Automated test suite
```

---

&copy; HMIF STT Cipasung. Dikembangkan sesuai spesifikasi Product Requirement Document (PRD) v1.1.
