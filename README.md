# ⚙️ ENGINECRM - Sistem Informasi CRM Bengkel Motor

**ENGINECRM** adalah aplikasi berbasis web yang dirancang untuk membantu bengkel mengelola hubungan pelanggan, penjadwalan servis berdasarkan segmen pasar, serta otomatisasi pengingat melalui WhatsApp.

## ✨ Fitur Utama

* **Dashboard & Database Pelanggan:** Pantau total pelanggan dan kelola data pelanggan dengan tombol aksi cepat (*Chat WA* langsung dari tabel).
* **Segmentasi Pelanggan & Layanan Khusus:** Penyesuaian layanan booking berdasarkan profil pelanggan:
  * 🛵 **Ojol:** *Fast-Track & Diskon Oli*
  * 👩 **Ibu-Ibu:** *Home Service / Antar-Jemput*
  * 🏍️ **Anak Muda:** *Night Modif & Sunmori Prep*
  * 🔧 **Regular / Umum:** Layanan servis standar
* **Manajemen Booking Servis:** Penjadwalan tanggal, jam, pencatatan keluhan motor, serta konfirmasi otomatis via WhatsApp.
* **Poin, Promo & Referral:** Sistem loyalitas pelanggan untuk meningkatkan retensi servis berkala.
* **Reminder WA:** Pengingat jadwal servis atau promo langsung ke WhatsApp pelanggan.

## 🛠️ Teknologi yang Digunakan

* **Backend:** PHP Native
* **Frontend:** HTML5, CSS3 (Dark Mode UI)
* **Database:** MySQL / MariaDB
* **Integrasi:** WhatsApp API / Click-to-Chat

## 🚀 Cara Menjalankan di Lokal (Localhost)

1. Clone atau download repositori ini ke folder `htdocs` (jika menggunakan XAMPP) atau `www` (jika menggunakan Laragon).
2. Buat database baru di phpMyAdmin dan import file `.sql` *(jika tersedia)*.
3. Sesuaikan konfigurasi koneksi database pada file konfigurasi PHP.
4. Buka browser dan akses `http://localhost/CRM-Bengkel/indeks.php`.

## 📸 Tampilan Aplikasi

### 1. Dashboard & Database Pelanggan
![Dashboard ENGINECRM](<Dashboard-crm.png>)

### 2. Manajemen Booking & Layanan Segmen
![Layanan Pemesanan](booking.png)

### 3. Poin, Promo & Referral
![Poin dan Promo](<poin & promo - crm . png>)

### 4. Pengingat WhatsApp (Reminder WA)
![Pengingat WA](reminder.png)
