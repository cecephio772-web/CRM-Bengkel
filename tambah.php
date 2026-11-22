<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include 'config/database.php';

// Proses Simpan Data Pelanggan Baru
if (isset($_POST['submit_pelanggan'])) {
    $nama            = mysqli_real_escape_string($conn, $_POST['nama']);
    $email           = mysqli_real_escape_string($conn, $_POST['email']);
    $no_wa           = mysqli_real_escape_string($conn, $_POST['no_wa']);
    $kategori_segmen = mysqli_real_escape_string($conn, $_POST['kategori_segmen']);

    if (!empty($nama) && !empty($no_wa)) {
        
        // Query 1: Mengisi kolom 'no_hp' yang wajib diisi di database kamu
        $query1 = "INSERT INTO customers (nama, email, no_hp, kategori_segmen) 
                   VALUES ('$nama', '$email', '$no_wa', '$kategori_segmen')";
        
        if (!mysqli_query($conn, $query1)) {
            // Query 2: Cadangan jika kolom kategori_segmen belum ada
            $query2 = "INSERT INTO customers (nama, email, no_hp) 
                       VALUES ('$nama', '$email', '$no_wa')";
            
            if (!mysqli_query($conn, $query2)) {
                // Query 3: Cadangan gabungan seluruh kolom nomor (no_hp, no_wa, phone)
                $query3 = "INSERT INTO customers (nama, email, no_hp, no_wa, phone) 
                           VALUES ('$nama', '$email', '$no_wa', '$no_wa', '$no_wa')";
                
                if (!mysqli_query($conn, $query3)) {
                    die("<script>alert('❌ Gagal Simpan Database: " . mysqli_error($conn) . "'); window.history.back();</script>");
                }
            }
        }

        echo "<script>alert('✅ Data Pelanggan Baru Berhasil Disimpan!'); window.location.href='/bengkel-api/index.php';</script>";
        exit();
    } else {
        echo "<script>alert('⚠️ Nama dan Nomor WA/HP wajib diisi!'); window.history.back();</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EngineCRM - Tambah Pelanggan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-slate-900 text-slate-100 font-sans min-h-screen">

    <!-- Header Navigation -->
    <nav class="relative z-50 border-b border-slate-800 bg-slate-950 px-8 py-4 flex justify-between items-center">
        <div class="flex items-center space-x-2 text-xl font-bold text-blue-500">
            <i class="fa-solid fa-gear text-blue-500"></i>
            <span>ENGINECRM</span>
        </div>
        <div class="flex space-x-6 text-sm font-medium">
            <a href="/bengkel-api/index.php" class="text-blue-400 font-bold border-b-2 border-blue-400 py-1"><i class="fa-solid fa-users mr-1"></i> Pelanggan</a>
            <a href="/bengkel-api/booking.php" class="text-slate-300 hover:text-emerald-400 py-1 transition"><i class="fa-solid fa-calendar-check mr-1 text-emerald-400"></i> Booking Servis</a>
            <a href="/bengkel-api/poin-promo.php" class="text-slate-300 hover:text-purple-400 py-1 transition"><i class="fa-solid fa-gift mr-1 text-purple-400"></i> Poin & Promo</a>
            <a href="/bengkel-api/kirim-wa.php" class="text-slate-300 hover:text-emerald-400 py-1 transition"><i class="fa-brands fa-whatsapp mr-1 text-emerald-400"></i> Reminder WA</a>
        </div>
    </nav>

    <div class="p-8 max-w-2xl mx-auto space-y-6">
        
        <div class="flex justify-between items-center border-b border-slate-800 pb-4">
            <div>
                <h1 class="text-2xl font-bold text-white flex items-center gap-2">
                    <i class="fa-solid fa-user-plus text-blue-500"></i> Tambah Pelanggan Baru
                </h1>
                <p class="text-sm text-slate-400">Masukkan data profil dan kontak WhatsApp pelanggan.</p>
            </div>
            <a href="/bengkel-api/index.php" class="bg-slate-800 hover:bg-slate-700 text-slate-200 px-4 py-2 rounded-lg text-sm border border-slate-700 transition">
                <i class="fa-solid fa-arrow-left"></i> Kembali
            </a>
        </div>

        <!-- Form Tambah Pelanggan -->
        <div class="bg-slate-800/60 border border-slate-700 p-6 rounded-xl">
            <form action="/bengkel-api/tambah.php" method="POST" class="space-y-4">
                
                <div>
                    <label class="block text-slate-400 text-sm mb-1">Nama Lengkap Pelanggan *</label>
                    <input type="text" name="nama" required placeholder="Contoh: Budi Santoso" class="w-full bg-slate-900 border border-slate-700 rounded-lg p-2.5 text-white focus:outline-none focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-slate-400 text-sm mb-1">Nomor WhatsApp / HP *</label>
                    <input type="text" name="no_wa" required placeholder="Contoh: 08123456789" class="w-full bg-slate-900 border border-slate-700 rounded-lg p-2.5 text-white focus:outline-none focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-slate-400 text-sm mb-1">Email (Opsional)</label>
                    <input type="email" name="email" placeholder="budi@example.com" class="w-full bg-slate-900 border border-slate-700 rounded-lg p-2.5 text-white focus:outline-none focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-slate-400 text-sm mb-1">Kategori Segmen Pelanggan</label>
                    <select name="kategori_segmen" class="w-full bg-slate-900 border border-slate-700 rounded-lg p-2.5 text-white focus:outline-none focus:border-blue-500">
                        <option value="Ojol">🛵 Driver Ojol (Fast-Track & Diskon Oli)</option>
                        <option value="Ibu-Ibu">👩 Ibu-Ibu (Home Service / Antar-Jemput)</option>
                        <option value="Anak Muda">🏎️ Anak Muda (Night Modif & Sunmori Prep)</option>
                        <option value="Regular" selected>🔧 Regular / Umum</option>
                    </select>
                </div>

                <div class="pt-4">
                    <button type="submit" name="submit_pelanggan" class="w-full bg-blue-600 hover:bg-blue-500 font-bold py-3 rounded-lg text-white transition flex justify-center items-center gap-2">
                        <i class="fa-solid fa-save"></i> Simpan Data Pelanggan
                    </button>
                </div>

            </form>
        </div>

    </div>

</body>
</html>