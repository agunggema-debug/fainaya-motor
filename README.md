# ⚡ FAINAYA MOTOR: WAREHOUSE & CASHIER INDUSTRIAL HUD

[![Laravel Version](https://img.shields.io/badge/Laravel-11.x-red.svg)](https://laravel.com)
[![PHP Version](https://img.shields.io/badge/PHP-8.2%20%7C%208.4-blue.svg)](https://php.net)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-3.x-38bdf8.svg)](https://tailwindcss.com)
[![Deployment](https://img.shields.io/badge/Render-Live-emerald.svg)](https://render.com)

Aplikasi manajemen antrean bengkel, finalisasi pembayaran (kasir), pemotongan stok otomatis gudang, dan perekaman medis digital siklus kesehatan kendaraan secara *real-time*. Antarmuka dirancang khusus dengan tema **Cyberpunk Dark Mode HUD / Gaming Questboard**.

---

## 🧭 KRONOLOGI ALUR PROSES PEMBUATAN SYSTEM

Proses perancangan sistem terintegrasi ini diselesaikan melalui beberapa tahapan arsitektur modular:

### 🔹 FASE 1: Fondasi Data
* **Sinkronisasi Database:** Membangun relasi tabel dinamis antara pelanggan (`customers`), transaksi antrean (`services`), detail suku cadang (`service_details`), dan inventaris gudang (`items`).

### 🔹 FASE 2: Manajemen Multi-Role & Secure Routing Board
* **Pembagian Kasta Pengguna:** Memisahkan hak akses menggunakan Laravel Gates (`@can`) ke dalam 3 entitas operasional bengkel:
  * 🎭 **Guild Master / Merchant Station (Kasir):** Berhak melakukan registrasi antrean awal dan melayani penagihan pembayaran.
  * ⚔️ **Combatant Station (Mekanik):** Berhak melakukan *Accept Quest* (memulai servis), memodifikasi penggunaan suku cadang (*Manage Loot*), serta mengunci status pengerjaan fisik unit.
  * 📦 **Inventory Manager (Gudang):** Mengontrol sirkulasi masuk-keluar barang, melakukan *restock*, dan mendeteksi batas minimal persediaan.

### 🔹 FASE 3: Mesin Otomatisasi Stok 4-in-1 (`ServiceController.php`)
* **Pengurangan Stok Otomatis:** Saat mekanik menginput penggantian suku cadang di panel mekanik, sistem secara *real-time* langsung mengeksekusi fungsi `decrement('stock')`.
* **Proteksi Concurrency Control:** Penambahan metode `lockForUpdate()` pada database MySQL untuk mencegah kesalahan kalkulasi stok jika ada dua mekanik yang mengambil barang sejenis di detik yang sama.
* **Sistem Peringatan Limit:** Mengintegrasikan visualisasi `⚠️ CRITICAL_LOW_STOCK` berkedip jika stok suku cadang menembus batas minimum aman (`min_stock`).

### 🔹 FASE 4: Finalisasi Kasir & Rekam Medis Kendaraan (Invoice HUD)
* **Kalkulator Kembalian Kasir:** Menghitung selisih nominal `amount_paid` (uang bayar) secara aman dari sisi *server-side* dan mencatat penanggung jawab transaksi secara otomatis.
* **Dua Dimensi Visual (Print-Ready CSS):** Merancang `invoice.blade.php` dengan teknik manipulasi `@media print`. Tampilan layar tetap bertema gelap neon fiksi ilmiah, namun saat tombol cetak ditekan, latar belakang otomatis berubah putih bersih untuk efisiensi tinta printer thermal bengkel.
* **Rekam Medis Historis:** Menyisipkan fitur pelacak riwayat kerusakan motor masa lalu (`$vehicleHistory`) berbasis plat nomor kendaraan unik agar mekanik dapat memantau rekam medis penyakit mesin motor pelanggan.

---

## 🛠️ SPESIFIKASI TEKNIS & FITUR UTAMA

| Sektor Fitur | Teknis Implementasi | Target Output |
| :--- | :--- | :--- |
| **Pencegahan Over-Allocation** | MySQL `DB::transaction()` & `lockForUpdate()` | Nol risiko manipulasi selisih stok gudang |
| **Dual-Mode Document** | CSS Tailwind `@media print` | Cetak nota bersih (hemat tinta) & Layar monitor *Gaming HUD* |
| **Keamanan Antrean** | Middleware Authentication & Laravel Gates | Mekanik tidak bisa mencampuri kasir, begitu pula sebaliknya |
| **Penyimpanan STNK** | Metode `updateStnk()` & Identifikasi Nomor Rangka | Data validitas hukum kepemilikan motor tersimpan rapi |

---

## 💻 CARA MENJALANKAN DI LINGKUNGAN LOKAL

1. **Clone Repository & Masuk ke Direktori:**
```bash
   git clone [https://github.com/USERNAME_ANDA/fainaya-motor.git](https://github.com/USERNAME_ANDA/fainaya-motor.git)
   cd fainaya-motor
```
---
## 💻Tampilan Login

<img width="1094" height="874" alt="image" src="https://github.com/user-attachments/assets/e5b52092-2025-46c6-a029-3ff45905395e" />

## 💻Tampilan Dashboard

<img width="1445" height="1079" alt="image" src="https://github.com/user-attachments/assets/1f94f492-337c-4187-84bf-ce881dbbc4d7" />

## 💻Tampilan Input Customer

<img width="983" height="1069" alt="image" src="https://github.com/user-attachments/assets/14ed5a71-b5a5-42a6-97ab-7fcf0a09cb18" />

## 💻Tampilan Cetak invoice

<img width="1044" height="982" alt="image" src="https://github.com/user-attachments/assets/4274bf7f-0ad6-4ac6-9624-ab5faee86e25" />
