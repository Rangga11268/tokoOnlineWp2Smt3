<p align="center">
    <img src="public/image/logo.png" alt="Logo BSI" width="120"/>
</p>

<h1 align="center">Toko Online UBSI</h1>
<p align="center"><b>Studi Kasus Web Programming 2 - Semester 3</b></p>

---

Repository ini berisi source code studi kasus toko online sederhana untuk pembelajaran Web Programming 2 di Universitas Bina Sarana Informatika (UBSI).

**Author:** Darell Rangga

---

### ✨ Fitur Utama

- Autentikasi user (login/logout)
- Manajemen user (admin & user biasa)
- CRUD produk & kategori
- DataTables untuk tabel backend
- SweetAlert untuk konfirmasi
- CKEditor untuk input deskripsi produk

---

### 🚀 Tutorial Penggunaan (Untuk Pemula)

1. **Clone Repository**
    - Download/clone project ini ke komputer kamu (bisa pakai Git atau tombol "Code" di GitHub).
    - Contoh Git:
        ```bash
        git clone https://github.com/username/tokoOnline.git
        cd tokoOnline
        ```
2. **Install Dependency PHP**
    - Pastikan sudah install Composer.
    - Jalankan:
        ```bash
        composer install
        ```
3. **Install Dependency Frontend**
    - Pastikan sudah install Node.js & npm.
    - Jalankan:
        ```bash
        npm install
        ```
4. **Salin & Edit File .env**
    - Salin `.env.example` ke `.env` (Windows: `copy .env.example .env`)
    - Edit `.env` dan sesuaikan DB_DATABASE, DB_USERNAME, DB_PASSWORD
5. **Generate Key Aplikasi**
    - Jalankan:
        ```bash
        php artisan key:generate
        ```
6. **Migrasi & Seeder Database**
    - Jalankan:
        ```bash
        php artisan migrate --seed
        ```
7. **Jalankan Server Lokal**
    - Jalankan:
        ```bash
        php artisan serve
        ```
    - Akses di [http://localhost:8000](http://localhost:8000)
8. **Login ke Backend**
    - Buka [http://localhost:8000/backend/login](http://localhost:8000/backend/login)
    - Login admin:
        - **Email:** admin@gmail.com
        - **Password:** P@55word

---

### 💡 Tips & Kendala Umum

- Jika error database, cek database sudah dibuat & setting `.env` benar
- Jika tampilan tidak ada CSS/JS, jalankan `npm run build` atau `npm run dev`
- Untuk Windows, gunakan `copy .env.example .env`
- Jika ada perubahan kode, restart server atau jalankan `php artisan cache:clear`

---

> Proyek ini hanya untuk pembelajaran Web Programming 2 UBSI. Selamat belajar & semoga sukses!
