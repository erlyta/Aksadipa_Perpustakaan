<img width="2880" height="1800" alt="image" src="https://github.com/user-attachments/assets/7cb4ca31-e635-4822-9e95-af7513672ccf" /># 📘 Aksadipa – Aplikasi Perpustakaan Digital 📚

> Sebuah aplikasi perpustakaan digital berbasis Laravel yang dirancang untuk mempermudah pengelolaan buku, peminjaman, dan pengguna dalam satu sistem yang modern dan terstruktur.

---

## ✨ Tentang Project

Aksadipa adalah aplikasi perpustakaan digital yang dikembangkan menggunakan framework Laravel.
Aplikasi ini dibuat untuk membantu admin, petugas, dan pengguna dalam mengelola proses peminjaman buku secara digital, cepat, dan efisien.

Project ini cocok digunakan untuk:

* Sistem perpustakaan sekolah
* Project tugas akhir / portofolio
* Sistem manajemen buku digital

---

## 🖥️ Tampilan Fitur Utama

🎯 Sistem memiliki beberapa role:

* 👑 Admin
* 🧑‍💼 Petugas
* 🙎🏻 User / Peminjam

---

## 🚀 Fitur Unggulan

### 📚 Manajemen Buku

* Tambah, edit, hapus data buku
* ISBN buku
* Kategori buku
* Cover buku

### 👥 Manajemen Pengguna

* Login & Register
* Role user (Admin, Petugas, User)
* Edit profil & avatar

### 📖 Sistem Peminjaman

* Peminjaman buku digital
* Kode peminjaman (scan code)
* Durasi pinjam fleksibel
* Status pinjaman (Dipinjam / Dikembalikan)
* Denda keterlambatan

### ❤️ Fitur User

* Favorit buku
* Lihat detail buku
* Riwayat peminjaman
* Dashboard user

### 📊 Laporan & Admin Panel

* Laporan peminjaman
* Export laporan
* Dashboard statistik
* Manajemen kategori buku

---

## 🛠️ Teknologi yang Digunakan

| Teknologi        | Keterangan               |
| ---------------- | ------------------------ |
| Laravel          | Framework Backend (PHP)  |
| MySQL            | Database                 |
| Blade            | Template Engine          |
| CSS & JavaScript | Frontend                 |
| XAMPP            | Local Development Server |
| Git & GitHub     | Version Control          |

---

## 📂 Struktur Project (Singkat)

```
app/            -> Controller & Logic
resources/      -> Tampilan Blade
routes/         -> Routing Web
database/       -> Migration & Seeder
public/         -> Asset (CSS, Image)
storage/        -> File Storage
```

---

## ⚙️ Cara Instalasi & Menjalankan Project

Ikuti langkah ini dengan lembut ya 🌼

### 1️⃣ Clone Repository

```bash
git clone https://github.com/erlyta/Aksadipa_Perpustakaan.git
```

### 2️⃣ Masuk ke Folder Project

```bash
cd Aksadipa_Perpustakaan
```

### 3️⃣ Install Dependency

```bash
composer install
```

### 4️⃣ Copy File Environment

```bash
cp .env.example .env
```

### 5️⃣ Generate App Key

```bash
php artisan key:generate
```

### 6️⃣ Atur Database

* Buka phpMyAdmin (XAMPP)
* Buat database baru (misal: perpustakaan)
* Sesuaikan file `.env`:

```
DB_DATABASE=perpustakaan
DB_USERNAME=root
DB_PASSWORD=
```

### 7️⃣ Migrasi Database

```bash
php artisan migrate --seed
```

### 8️⃣ Jalankan Server Laravel

```bash
php artisan serve
```

Lalu buka di browser:

```
http://127.0.0.1:8000
```

---

## 🔐 Akun Default (Seeder)

| Role  | Email                                     | Password |
| ----- | ----------------------------------------- | -------- |
| Admin | [admin@gmail.com](mailto:admin@gmail.com) | admin123 |
| User  | [user1@gmail.com](mailto:user@gmail.com)  | 123456   |

---

## 🎓 Tujuan Pengembangan

Project ini dikembangkan sebagai:

* Project pembelajaran Laravel
* Portofolio pengembangan web
* Sistem informasi perpustakaan digital berbasis web

---

## 👩‍💻 Developer

**Erlyta** 💗
SMK – Project Aplikasi Perpustakaan Digital
Tahun: 2026

---

## ⭐ Penutup

Terima kasih telah mengunjungi repository ini.
Semoga project ini bermanfaat dan dapat menjadi inspirasi dalam pengembangan aplikasi berbasis Laravel yang elegan dan fungsional.
