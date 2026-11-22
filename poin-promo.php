<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include 'config/database.php';

// Ambil data pelanggan dari database
$query_customers = "SELECT * FROM customers ORDER BY id DESC";
$result_customers = mysqli_query($conn,$query_customers);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EngineCRM - Poin & Promo Segmen</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-slate-900 text-slate-100 font-sans min-h-screen">
    

    <!-- Header Navigation Utuh -->
    <nav class="relative z-50 border-b border-slate-800 bg-slate-950 px-8 py-4 flex justify-between items-center">
    <div class="flex items-center space-x-2 text-xl font-bold text-blue-500">
        <i class="fa-solid fa-gear text-blue-500"></i>
        <span>ENGINECRM</span>
    </div>
    <div class="flex space-x-6 text-sm font-medium">
        <a href="index.php" class="text-slate-300 hover:text-blue-400 py-1 transition"><i class="fa-solid fa-users mr-1"></i> Pelanggan</a>
        <a href="booking.php" class="text-slate-300 hover:text-emerald-400 py-1 transition"><i class="fa-solid fa-calendar-check mr-1 text-emerald-400"></i> Booking Servis</a>
        <a href="poin-promo.php" class="text-purple-400 font-bold border-b-2 border-purple-400 py-1"><i class="fa-solid fa-gift mr-1"></i> Poin & Promo</a>
        <a href="kirim-wa.php" class="text-slate-300 hover:text-emerald-400 py-1 transition"><i class="fa-brands fa-whatsapp mr-1 text-emerald-400"></i> Reminder WA</a>
    </div>
</nav>

    <div class="p-8 max-w-7xl mx-auto space-y-8">

        <!-- Banner Judul -->
        <div class="flex justify-between items-center border-b border-slate-800 pb-4">
            <div>
                <h1 class="text-2xl font-bold text-white flex items-center gap-2">
                    <i class="fa-solid fa-gift text-purple-400"></i> Fitur Poin & Promo Khusus Segmen
                </h1>
                <p class="text-sm text-slate-400">Kelola promo khusus untuk driver Ojol, Ibu-Ibu, dan Anak Muda.</p>
            </div>
            <a href="index.php" class="bg-slate-800 hover:bg-slate-700 px-4 py-2 rounded text-sm text-white border border-slate-700">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Dashboard
            </a>
        </div>

        <!-- Kartu Promo Per Segmen -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-slate-800/60 border border-emerald-500/30 p-6 rounded-xl space-y-2">
                <div class="text-emerald-400 font-bold text-lg">🛵 Segmen Ojol</div>
                <p class="text-xs text-slate-300">Voucher Potongan Ganti Oli Rp 15.000 + Garansi Bebas Antri (Fast Track).</p>
                <span class="inline-block bg-emerald-500/10 text-emerald-400 text-xs px-2.5 py-1 rounded border border-emerald-500/20 font-mono">KODE: OJOLGACOR</span>
            </div>

            <div class="bg-slate-800/60 border border-pink-500/30 p-6 rounded-xl space-y-2">
                <div class="text-pink-400 font-bold text-lg">👩 Segmen Ibu-Ibu</div>
                <p class="text-xs text-slate-300">Gratis Biaya Antar-Jemput Motor (Home Service) untuk radius hingga 5km.</p>
                <span class="inline-block bg-pink-500/10 text-pink-400 text-xs px-2.5 py-1 rounded border border-pink-500/20 font-mono">KODE: HOMESERVICE</span>
            </div>

            <div class="bg-slate-800/60 border border-purple-500/30 p-6 rounded-xl space-y-2">
                <div class="text-purple-400 font-bold text-lg">🏎️ Segmen Anak Muda</div>
                <p class="text-xs text-slate-300">Bonus 50 Poin Modif setiap Tune-Up Malam (Night Service) & Sunmori Prep.</p>
                <span class="inline-block bg-purple-500/10 text-purple-400 text-xs px-2.5 py-1 rounded border border-purple-500/20 font-mono">KODE: SUNMORINIGHT</span>
            </div>
        </div>

        <!-- Tabel