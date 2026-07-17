# Teh Poci Kasir (Point of Sales & Manajemen Stok Gudang)

Aplikasi web full-stack Point of Sales (POS) dan Manajemen Stok Gudang khusus untuk gerai waralaba **Es Teh Poci**. Aplikasi ini dikembangkan menggunakan Laravel 13, Laravel Breeze (Blade & Tailwind CSS), MySQL, dan diuji menggunakan Pest PHP.

---

## Fitur Utama

1. **Autentikasi Pengguna & Hak Akses (Role/Gate)**:
   - **Owner**: Memiliki akses penuh ke Dashboard Laporan Penjualan, Manajemen Menu & Harga (CRUD), serta Manajemen Stok Gudang/Bahan Baku (CRUD).
   - **Kasir**: Hanya memiliki akses ke Halaman Kasir / Transaksi untuk melayani pembelian.

2. **Manajemen Menu & Harga (CRUD)**:
   - Pengelolaan varian rasa Es Teh Poci (Original, Jasmine, Milk Tea, dll.) disertai harga jual dan pilihan ukuran (`Medium` / `Large`).
   - Setiap menu dapat memiliki konfigurasi resep (bahan baku apa saja yang digunakan dan seberapa banyak per cup).

3. **Halaman Kasir / Checkout**:
   - Grid menu interaktif dengan keranjang belanja dinamis (didukung oleh Javascript).
   - Perhitungan kembalian otomatis saat kasir menginput jumlah uang bayar.
   - **Database Transaction & Auto-Deduction**: Stok bahan baku di gudang otomatis terpotong saat transaksi sukses disimpan. Transaksi akan di-rollback jika ada bahan baku yang stoknya tidak mencukupi.

4. **Manajemen Bahan Baku / Stok Gudang (CRUD)**:
   - Pencatatan stok bahan baku (Cup Medium, Cup Large, Sedotan, Bubuk Teh, Gula Cair, dll).
   - **Sistem Warning/Alert**: Dashboard owner akan menampilkan notifikasi peringatan jika ada bahan baku dengan sisa stok di bawah batas minimum (`min_stock`).

5. **Dashboard & Laporan (Owner)**:
   - Total pendapatan hari ini & bulan ini.
   - Total volume cup terjual hari ini & bulan ini.
   - Tabel riwayat transaksi lengkap dengan rincian item terjual.

---

## Kredensial Akun Default (Seeded)

Jalankan perintah database seeder untuk membuat akun berikut:

* **Owner**:
  - Email: `owner@tehpoci.com`
  - Password: `password`
* **Kasir**:
  - Email: `kasir@tehpoci.com`
  - Password: `password`

---

## Instalasi & Cara Menjalankan

1. **Clone repository & Composer Install**:
   ```bash
   composer install
   ```

2. **Salin Environment file & buat Application Key**:
   ```bash
   copy .env.example .env
   php artisan key:generate
   ```

3. **Migrasi Database & Seeding**:
   Pastikan konfigurasi database di `.env` sudah benar. Kemudian jalankan:
   ```bash
   php artisan migrate:fresh --seed
   ```

4. **Kompilasi Aset Frontend**:
   ```bash
   npm install
   npm run build
   ```

5. **Jalankan Aplikasi**:
   ```bash
   php artisan serve
   ```
   Buka `http://localhost:8000` di web browser Anda.

---

## Pengujian (Testing)

Aplikasi ini menggunakan **Pest PHP** sebagai framework testing.

Jalankan perintah berikut untuk mengeksekusi semua pengujian (termasuk tes otorisasi role dan pemotongan stok otomatis):
```bash
php artisan test
```
atau
```bash
vendor/bin/pest
```
