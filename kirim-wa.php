<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include 'config/database.php';

// Ambil Seluruh Data Pelanggan
$query_customers = "SELECT * FROM customers ORDER BY id DESC";
$result_customers = mysqli_query($conn, $query_customers);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EngineCRM - Blast & Reminder WA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-slate-900 text-slate-100 font-sans min-h-screen">

    <!-- Header Navigation Root Absolute -->
    <nav class="relative z-50 border-b border-slate-800 bg-slate-950 px-8 py-4 flex justify-between items-center">
        <div class="flex items-center space-x-2 text-xl font-bold text-blue-500">
            <i class="fa-solid fa-gear text-blue-500"></i>
            <span>ENGINECRM</span>
        </div>
        <div class="flex space-x-6 text-sm font-medium">
            <a href="/bengkel-api/index.php" class="text-slate-300 hover:text-blue-400 py-1 transition flex items-center gap-1.5"><i class="fa-solid fa-users"></i> Pelanggan</a>
            <a href="/bengkel-api/booking.php" class="text-slate-300 hover:text-emerald-400 py-1 transition flex items-center gap-1.5"><i class="fa-solid fa-calendar-check text-emerald-400"></i> Booking Servis</a>
            <a href="/bengkel-api/poin-promo.php" class="text-slate-300 hover:text-purple-400 py-1 transition flex items-center gap-1.5"><i class="fa-solid fa-gift text-purple-400"></i> Poin & Promo</a>
            <a href="/bengkel-api/kirim-wa.php" class="text-emerald-400 font-bold border-b-2 border-emerald-400 py-1 flex items-center gap-1.5"><i class="fa-brands fa-whatsapp"></i> Reminder WA</a>
        </div>
    </nav>

    <div class="p-8 max-w-6xl mx-auto space-y-6">
        
        <div class="flex justify-between items-center border-b border-slate-800 pb-4">
            <div>
                <h1 class="text-2xl font-bold text-white flex items-center gap-2">
                    <i class="fa-brands fa-whatsapp text-emerald-400"></i> Reminder & Campaign WhatsApp
                </h1>
                <p class="text-sm text-slate-400">Kirim pesan promo dan penawaran personal otomatis sesuai segmen pelanggan.</p>
            </div>
            <a href="/bengkel-api/index.php" class="bg-slate-800 hover:bg-slate-700 text-slate-200 px-4 py-2 rounded-lg text-sm border border-slate-700 transition">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Dashboard
            </a>
        </div>

        <!-- Tabel Kirim Pesan WA -->
        <div class="bg-slate-800/60 border border-slate-700 p-6 rounded-xl space-y-4">
            <h2 class="text-lg font-semibold text-emerald-400"><i class="fa-solid fa-paper-plane"></i> Daftar Kontak Pelanggan</h2>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-300">
                    <thead class="bg-slate-800 text-xs text-slate-400 uppercase border-b border-slate-700">
                        <tr>
                            <th class="p-3">Pelanggan</th>
                            <th class="p-3">Kontak WA</th>
                            <th class="p-3">Segmen</th>
                            <th class="p-3 text-center">Aksi Blast WA</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800">
                        <?php if ($result_customers && mysqli_num_rows($result_customers) > 0): ?>
                            <?php while($c = mysqli_fetch_assoc($result_customers)): ?>
                            <?php 
                                $nama_p     = !empty($c['nama']) ? $c['nama'] : (!empty($c['name']) ? $c['name'] : 'Pelanggan #' . $c['id']);
                                $nomor_p    = !empty($c['no_wa']) ? $c['no_wa'] : (!empty($c['phone']) ? $c['phone'] : (!empty($c['no_hp']) ? $c['no_hp'] : ($c['nomor_hp'] ?? '')));
                                $no_wa      = preg_replace('/[^0-9]/', '', $nomor_p);
                                $segmen_p   = $c['kategori_segmen'] ?? $c['tipe_member'] ?? 'Regular';

                                // TEMPLATE PESAN PROMO MENARIK BERDASARKAN SEGMEN
                                if ($segmen_p == 'Ojol') {
                                    $msg = "Halo Bro {$nama_p}! 👋\n\nMotor dipake tempur harian buat nyari nafkah harus selalu dalam kondisi prima biar orderan makin gacor! 🛵💨\n\nPromo Spesial Driver Ojol hari ini di EngineCRM Bengkel:\n🎁 Diskon Oli Rp 15.000\n⚡ Jalur Bebas Antri (Fast-Track)\n\nYuk amankan slot servis kamu hari ini!";
                                } elseif ($segmen_p == 'Ibu-Ibu') {
                                    $msg = "Selamat Pagi/Siang Ibu {$nama_p} 🌸\n\nSemoga aktivitas Ibu dan keluarga senantiasa lancarkan. Motor sudah waktunya dicek berkala demi keamanan berkendara bersama keluarga Bu.\n\n🏠 Nikmati Layanan GRATIS Antar-Jemput Motor dari EngineCRM Bengkel!\n\nBalas WA ini untuk jadwalkan penjemputan ya Bu 😊";
                                } elseif ($segmen_p == 'Anak Muda') {
                                    $msg = "Bro {$nama_p}! 🔥\n\nPersiapan buat Sunmori weekend nih! Biar tarikan motor makin responsif dan mesin tetep adem:\n\n🛠️ Night-Service Tune Up & Injector Cleaning\n🎁 Bonus +50 Poin CRM Modif!\n\nAmankan slot servis kamu sekarang Bro!";
                                } else {
                                    $msg = "Halo Kak {$nama_p},\n\nSudah waktunya melakukan servis berkala untuk kendaraan Anda di EngineCRM Bengkel agar mesin tetap halus, hemat bensin, dan awet.\n\nYuk jadwalkan kedatangan Kakak hari ini! 😊👍";
                                }

                                $wa_link = "https://wa.me/" . $no_wa . "?text=" . urlencode($msg);
                            ?>
                            <tr class="hover:bg-slate-800/50">
                                <td class="p-3 font-bold text-white">
                                    <?= htmlspecialchars($nama_p); ?>
                                </td>
                                <td class="p-3 font-mono text-xs text-slate-300">
                                    <?= !empty($no_wa) ? htmlspecialchars($nomor_p) : '-'; ?>
                                </td>
                                <td class="p-3">
                                    <span class="text-xs px-2 py-0.5 rounded border 
                                        <?php 
                                            if($segmen_p == 'Ojol') echo 'bg-emerald-500/10 text-emerald-400 border-emerald-500/30';
                                            elseif($segmen_p == 'Ibu-Ibu') echo 'bg-pink-500/10 text-pink-400 border-pink-500/30';
                                            else echo 'bg-purple-500/10 text-purple-400 border-purple-500/30';
                                        ?>">
                                        <?= htmlspecialchars($segmen_p); ?>
                                    </span>
                                </td>
                                <td class="p-3 text-center">
                                    <?php if (!empty($no_wa)): ?>
                                        <a href="<?= $wa_link; ?>" target="_blank" class="bg-emerald-600 hover:bg-emerald-500 text-white text-xs px-3 py-1.5 rounded inline-flex items-center gap-1.5 font-semibold transition">
                                            <i class="fa-brands fa-whatsapp"></i> Kirim WA Otomatis
                                        </a>
                                    <?php else: ?>
                                        <span class="text-xs text-slate-500">Tanpa Kontak WA</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="p-4 text-center text-slate-500">Belum ada data pelanggan di database.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</body>
</html>