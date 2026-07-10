# SkillShare

SkillShare adalah platform web bagi mahasiswa untuk berbagi keterampilan dan menemukan teman belajar.

Pengguna dapat membuat akun, mengelola profil, menambahkan keterampilan yang dimiliki maupun yang ingin dipelajari, serta mencari pengguna lain berdasarkan kategori keterampilan untuk membangun kolaborasi belajar.

## Teknologi yang Digunakan

- Laravel 13
- PHP
- MySQL
- Blade Template
- HTML, CSS, dan JavaScript
- Bootstrap
- XAMPP
- Git dan GitHub

## Persyaratan

Sebelum menjalankan project, pastikan sudah menginstal:

- PHP
- Composer
- Git
- XAMPP
- Node.js dan NPM
- Visual Studio Code

Cek instalasi melalui terminal:

```bash
php -v
composer -V
git --version
node -v
npm -v
```

## Cara Menjalankan Project

### 1. Masuk ke folder XAMPP

```bash
cd C:\xampp\htdocs
```

### 2. Clone repository

```bash
git clone https://github.com/arsenicbreaker/skillShare.git
```

### 3. Masuk ke folder project

```bash
cd skillShare
```

### 4. Install dependency Laravel

```bash
composer install
```

### 5. Install dependency frontend

```bash
npm install
```

### 6. Salin file environment

Untuk PowerShell atau Command Prompt:

```bash
copy .env.example .env
```

Apabila menggunakan Git Bash:

```bash
cp .env.example .env
```

### 7. Generate application key

```bash
php artisan key:generate
```

## Konfigurasi Database

### 1. Jalankan XAMPP

Aktifkan:

- Apache
- MySQL

### 2. Buka phpMyAdmin

Buka alamat berikut melalui browser:

```text
http://localhost/phpmyadmin
```

### 3. Buat database baru

Buat database dengan nama:

```text
skillshare
```

### 4. Atur file `.env`

Buka file `.env`, lalu sesuaikan bagian database:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=skillshare
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Jalankan migration

```bash
php artisan migrate
```

Apabila project menggunakan seeder:

```bash
php artisan migrate --seed
```

## Menjalankan Aplikasi

Jalankan server Laravel:

```bash
php artisan serve
```

Kemudian buka:

```text
http://127.0.0.1:8000
```

Jalankan Vite pada terminal yang berbeda:

```bash
npm run dev
```

Gunakan dua terminal:

```text
Terminal 1: php artisan serve
Terminal 2: npm run dev
```

## Alur Kolaborasi GitHub

Setiap anggota disarankan mengerjakan fitur pada branch masing-masing dan tidak langsung mengubah branch `main`.

### 1. Ambil perubahan terbaru

```bash
git checkout main
git pull origin main
```

### 2. Buat branch baru

Format nama branch:

```text
feature/nama-fitur
```

Contoh:

```bash
git checkout -b feature/login
```

Contoh branch lainnya:

```text
feature/register
feature/profile
feature/skill
feature/search-user
fix/navbar
fix/login-validation
```

### 3. Kerjakan fitur

Setelah selesai, cek perubahan:

```bash
git status
```

### 4. Tambahkan perubahan

```bash
git add .
```

### 5. Commit perubahan

```bash
git commit -m "feat: menambahkan halaman login"
```

### 6. Push branch ke GitHub

```bash
git push origin feature/login
```

### 7. Buat Pull Request

Setelah branch berhasil di-push:

1. Buka repository GitHub.
2. Pilih menu **Pull Requests**.
3. Klik **New Pull Request**.
4. Pilih branch fitur yang telah dibuat.
5. Pastikan tujuan branch adalah `main`.
6. Tambahkan deskripsi perubahan.
7. Klik **Create Pull Request**.
8. Tunggu review sebelum melakukan merge.

## Format Commit

Gunakan format commit yang jelas:

```text
feat: menambahkan fitur baru
fix: memperbaiki error
style: mengubah tampilan
refactor: merapikan kode
docs: memperbarui dokumentasi
chore: perubahan konfigurasi
```

Contoh:

```bash
git commit -m "feat: menambahkan fitur pencarian pengguna"
git commit -m "fix: memperbaiki validasi form login"
git commit -m "style: memperbaiki tampilan navbar"
git commit -m "docs: memperbarui README"
```

## Mengambil Perubahan dari Main

Sebelum melanjutkan pekerjaan, ambil perubahan terbaru:

```bash
git checkout main
git pull origin main
```

Kemudian kembali ke branch masing-masing:

```bash
git checkout feature/login
git merge main
```

Apabila tidak ada konflik, lanjutkan pekerjaan seperti biasa.

## Mengatasi Merge Conflict

Apabila muncul merge conflict:

1. Buka file yang mengalami konflik.
2. Cari tanda berikut:

```text
<<<<<<< HEAD
Kode milik kita
=======
Kode dari branch lain
>>>>>>> main
```

3. Pilih atau gabungkan kode yang benar.
4. Hapus seluruh tanda konflik.
5. Simpan file.
6. Jalankan perintah:

```bash
git add .
git commit -m "fix: menyelesaikan merge conflict"
git push
```

Jangan melakukan merge secara sembarangan apabila belum memahami bagian kode yang mengalami konflik.

## Aturan Kolaborasi

- Jangan langsung melakukan push ke branch `main`.
- Gunakan branch terpisah untuk setiap fitur.
- Selalu jalankan `git pull origin main` sebelum mulai bekerja.
- Jangan mengunggah file `.env`.
- Jangan mengunggah folder `vendor`.
- Jangan mengunggah folder `node_modules`.
- Jangan mengubah kode milik anggota lain tanpa koordinasi.
- Pastikan fitur berjalan sebelum membuat Pull Request.
- Gunakan nama commit yang jelas.
- Satu Pull Request sebaiknya hanya berisi satu fitur utama.

## File yang Tidak Boleh Dibagikan

Pastikan file berikut tercantum dalam `.gitignore`:

```gitignore
.env
/vendor
/node_modules
/public/build
```

File `.env.example` tetap disimpan karena digunakan sebagai contoh konfigurasi.

## Perintah yang Sering Digunakan

```bash
# Menjalankan Laravel
php artisan serve

# Menjalankan Vite
npm run dev

# Menjalankan migration
php artisan migrate

# Mengulang seluruh migration
php artisan migrate:fresh

# Mengulang migration beserta seeder
php artisan migrate:fresh --seed

# Membersihkan cache
php artisan optimize:clear

# Melihat daftar route
php artisan route:list

# Mengecek perubahan Git
git status

# Melihat seluruh branch
git branch

# Berpindah branch
git checkout nama-branch

# Mengambil update terbaru
git pull origin main
```

## Struktur Dasar Project

```text
skillShare/
├── app/
│   ├── Http/Controllers/
│   └── Models/
├── database/
│   ├── migrations/
│   └── seeders/
├── public/
├── resources/
│   ├── css/
│   ├── js/
│   └── views/
├── routes/
│   └── web.php
├── .env.example
├── artisan
├── composer.json
├── package.json
└── README.md
```

## Tim Pengembang

Project ini dikembangkan secara kolaboratif oleh mahasiswa untuk memenuhi kebutuhan pembelajaran dan pengembangan aplikasi web.

### Repository

```text
https://github.com/arsenicbreaker/skillShare
```
