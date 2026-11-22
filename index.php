<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include 'config/database.php';

// Ambil Total Pelanggan
$total_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM customers");
$total_data = mysqli_fetch_assoc($total_query);

// Ambil Data Pelanggan
$query = "SELECT * FROM customers ORDER BY id DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EngineCRM - Portal CRM Bengkel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-slate-900 text-slate-100 font-sans min-h-screen">

    <!-- Header Navigation Konsisten -->
    <nav class="relative z-50 border-b border-slate-800 bg-slate-950 px-8 py-4 flex justify-between items-center">
    <div class="flex items-center space-x-2 text-xl font-bold text-blue-500">
        <i class="fa-solid fa-gear text-blue-500"></i>
        <span>ENGINECRM</span>
    </div>
    <a href="/bengkel-api/index.php" class="text-blue-400 font-bold border-b-2 border-blue-400 py-1"><i class="fa-solid fa-users mr-1"></i> Pelanggan</a>
<a href="/bengkel-api/booking.php" class="text-slate-300 hover:text-emerald-400 py-1 transition"><i class="fa-solid fa-calendar-check mr-1 text-emerald-400"></i> Booking Servis</a>
<a href="/bengkel-api/poin-promo.php" class="text-slate-300 hover:text-purple-400 py-1 transition"><i class="fa-solid fa-gift mr-1 text-purple-400"></i> Poin & Promo</a>
<a href="/bengkel-api/kirim-wa.php" class="text-slate-300 hover:text-emerald-400 py-1 transition"><i class="fa-brands fa-whatsapp mr-1 text-emerald-400"></i> Reminder WA</a>
</nav>

    <div class="p-8 max-w-7xl mx-auto space-y-6">

        <!-- Stat Widget -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="md:col-span-2 bg-slate-800/60 border border-slate-700/50 rounded-xl p-6 flex flex-col justify-center">
                <h1 class="text-2xl font-bold text-white flex items-center gap-2">
                    Selamat Datang di Portal CRM Bengkel <i class="fa-solid fa-wrench text-slate-400"></i>
                </h1>
                <p class="text-slate-400 text-sm mt-1">
                    Kelola pelanggan, pantau status kendaraan, dan kirim pengingat WhatsApp dalam satu panel kontrol.
                </p>
            </div>
            
            <div class="bg-slate-800/60 border border-slate-700/50 rounded-xl p-6 flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">TOTAL PELANGGAN</p>
                    <h2 class="text-4xl font-extrabold text-white mt-1"><?= $total_data['total'] ?? 0; ?></h2>
                </div>
                <div class="bg-blue-600/20 text-blue-400 p-4 rounded-xl">
                    <i class="fa-solid fa-user text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Tabel Pelanggan -->
        <div class="bg-slate-800/40 border border-slate-700/50 rounded-xl p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-bold text-white flex items-center gap-2">
                    <i class="fa-solid fa-database text-blue-400"></i> Database Pelanggan
                </h3>
                <a href="tambah.php" class="bg-blue-600 hover:bg-blue-500 text-white font-medium px-4 py-2 rounded-lg text-sm flex items-center gap-2 transition">
                    <i class="fa-solid fa-user-plus"></i> Tambah Pelanggan
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-300">
                    <thead class="bg-slate-800/80 uppercase text-xs text-slate-400 border-b border-slate-700">
                        <tr>
                            <th class="py-3 px-4">ID</th>
                            <th class="py-3 px-4">PELANGGAN</th>
                            <th class="py-3 px-4">KONTAK WA</th>
                            <th class="py-3 px-4">SEGMEN</th>
                            <th class="py-3 px-4 text-center">AKSI CEPAT</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800">
                        <?php if($result && mysqli_num_rows($result) > 0): ?>
                            <?php while($row = mysqli_fetch_assoc($result)): ?>
                            <?php 
                                $nama = !empty($row['nama']) ? $row['nama'] : (!empty($row['name']) ? $row['name'] : 'Pelanggan #' . $row['id']);
                                $wa = !empty($row['no_wa']) ? $row['no_wa'] : (!empty($row['phone']) ? $row['phone'] : '-');
                                $segmen = !empty($row['kategori_segmen']) ? $row['kategori_segmen'] : (!empty($row['tipe_member']) ? $row['tipe_member'] : 'Umum');
                                $clean_wa = preg_replace('/[^0-9]/', '', $wa);
                                $pesan = urlencode("Halo " . $nama . ", ada pengingat dari EngineCRM Bengkel hari ini!");
                            ?>
                            <tr class="hover:bg-slate-800/30 transition">
                                <td class="py-4 px-4 font-mono text-slate-500">#<?= $row['id']; ?></td>
                                <td class="py-4 px-4">
                                    <div class="font-bold text-white"><?= htmlspecialchars($nama); ?></div>
                                    <div class="text-xs text-slate-400"><?= htmlspecialchars($row['email'] ?? ''); ?></div>
                                </td>
                                <td class="py-4 px-4 font-mono text-blue-400"><?= htmlspecialchars($wa); ?></td>
                                <td class="py-4 px-4">
                                    <span class="text-xs px-3 py-1 rounded-full bg-slate-700 text-slate-200">
                                        <?= htmlspecialchars($segmen); ?>
                                    </span>
                                </td>
                                <td class="py-4 px-4 flex justify-center space-x-2">
                                    <a href="https://wa.me/<?= $clean_wa; ?>?text=<?= $pesan; ?>" target="_blank" class="px-3 py-1.5 border border-emerald-600/60 text-emerald-400 hover:bg-emerald-600/10 rounded text-xs flex items-center gap-1 font-semibold">
                                        <i class="fa-brands fa-whatsapp"></i> Chat WA
                                        
                                    </a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="py-6 text-center text-slate-500">Belum ada data pelanggan di database.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</body>
</html>