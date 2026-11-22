<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include 'config/database.php';

// 1. Proses Simpan Booking Baru
if (isset($_POST['submit_booking'])) {
    $customer_id    = mysqli_real_escape_string($conn, $_POST['customer_id']);
    $tanggal        = mysqli_real_escape_string($conn, $_POST['tanggal_booking']);
    $jam            = mysqli_real_escape_string($conn, $_POST['jam_booking']);
    $segmen         = mysqli_real_escape_string($conn, $_POST['kategori_segmen']);
    $layanan        = mysqli_real_escape_string($conn, $_POST['layanan_khusus']);
    $catatan        = mysqli_real_escape_string($conn, $_POST['catatan']);

    if (!empty($customer_id) && !empty($tanggal) && !empty($jam)) {
        $query = "INSERT INTO booking (customer_id, tanggal_booking, jam_booking, kategori_segmen, layanan_khusus, catatan, status) 
                  VALUES ('$customer_id', '$tanggal', '$jam', '$segmen', '$layanan', '$catatan', 'Pending')";
        
        if (mysqli_query($conn, $query)) {
            echo "<script>alert('✅ Booking Berhasil Disimpan!'); window.location.href='/bengkel-api/booking.php';</script>";
            exit();
        } else {
            echo "<script>alert('❌ Gagal Simpan Database: " . mysqli_error($conn) . "');</script>";
        }
    } else {
        echo "<script>alert('⚠️ Harap isi semua kolom yang wajib!');</script>";
    }
}

// 2. Query Data Booking Aman (Ambil Semua Kolom Customer Tanpa Menyebut Nama Kolom Tertentu)
$query_booking = "SELECT b.*, c.* FROM booking b LEFT JOIN customers c ON b.customer_id = c.id ORDER BY b.id DESC";
$result_booking = mysqli_query($conn, $query_booking);

// 3. Query Customer Dropdown
$customers = mysqli_query($conn, "SELECT * FROM customers ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EngineCRM - Booking Servis</title>
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
            <a href="/bengkel-api/index.php" class="text-slate-300 hover:text-blue-400 py-1 transition flex items-center gap-1.5"><i class="fa-solid fa-users"></i> Pelanggan</a>
            <a href="/bengkel-api/booking.php" class="text-emerald-400 font-bold border-b-2 border-emerald-400 py-1 flex items-center gap-1.5"><i class="fa-solid fa-calendar-check"></i> Booking Servis</a>
            <a href="/bengkel-api/poin-promo.php" class="text-slate-300 hover:text-purple-400 py-1 transition flex items-center gap-1.5"><i class="fa-solid fa-gift text-purple-400"></i> Poin & Promo</a>
            <a href="/bengkel-api/kirim-wa.php" class="text-slate-300 hover:text-emerald-400 py-1 transition flex items-center gap-1.5"><i class="fa-brands fa-whatsapp text-emerald-400"></i> Reminder WA</a>
        </div>
    </nav>

    <div class="p-8 max-w-7xl mx-auto space-y-8">
        
        <div class="flex justify-between items-center border-b border-slate-800 pb-4">
            <div>
                <h1 class="text-2xl font-bold text-white flex items-center gap-2">
                    <i class="fa-solid fa-calendar-check text-emerald-400"></i> Manajemen Booking & Layanan Segmen
                </h1>
                <p class="text-sm text-slate-400">Jadwalkan servis dan kirim konfirmasi WA otomatis sesuai segmen pelanggan.</p>
            </div>
            <a href="/bengkel-api/index.php" class="bg-slate-800 hover:bg-slate-700 text-slate-200 px-4 py-2 rounded-lg text-sm flex items-center gap-2 border border-slate-700 transition">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Dashboard
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Form Buat Janji Servis -->
            <div class="bg-slate-800/60 border border-slate-700 p-6 rounded-xl space-y-4">
                <h2 class="text-lg font-semibold text-blue-400"><i class="fa-solid fa-plus-circle"></i> Buat Janji Servis (Booking)</h2>
                
                <form action="/bengkel-api/booking.php" method="POST" class="space-y-4 text-sm">
                    <div>
                        <label class="block text-slate-400 mb-1">Pilih Pelanggan</label>
                        <select name="customer_id" required class="w-full bg-slate-900 border border-slate-700 rounded p-2 text-white">
                            <option value="">-- Pilih Customer --</option>
                            <?php if ($customers && mysqli_num_rows($customers) > 0): ?>
                                <?php while($c = mysqli_fetch_assoc($customers)): ?>
                                    <?php $nama_cust = !empty($c['nama']) ? $c['nama'] : (!empty($c['name']) ? $c['name'] : 'Pelanggan #' . $c['id']); ?>
                                    <option value="<?= $c['id']; ?>"><?= htmlspecialchars($nama_cust); ?></option>
                                <?php endwhile; ?>
                            <?php endif; ?>
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-slate-400 mb-1">Tanggal</label>
                            <input type="date" name="tanggal_booking" required class="w-full bg-slate-900 border border-slate-700 rounded p-2 text-white">
                        </div>
                        <div>
                            <label class="block text-slate-400 mb-1">Jam</label>
                            <input type="time" name="jam_booking" required class="w-full bg-slate-900 border border-slate-700 rounded p-2 text-white">
                        </div>
                    </div>

                    <div>
                        <label class="block text-slate-400 mb-1">Kategori Segmen Fitur</label>
                        <select name="kategori_segmen" required class="w-full bg-slate-900 border border-slate-700 rounded p-2 text-white">
                            <option value="Ojol">🛵 Ojol (Fast-Track & Diskon Oli)</option>
                            <option value="Ibu-Ibu">👩 Ibu-Ibu (Home Service / Antar-Jemput)</option>
                            <option value="Anak Muda">🏎️ Anak Muda (Night Modif & Sunmori Prep)</option>
                            <option value="Regular">🔧 Regular / Umum</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-slate-400 mb-1">Paket Layanan Khusus</label>
                        <input type="text" name="layanan_khusus" placeholder="Misal: Ganti Oli Cepat / Jemput Motor" required class="w-full bg-slate-900 border border-slate-700 rounded p-2 text-white">
                    </div>

                    <div>
                        <label class="block text-slate-400 mb-1">Catatan Tambahan</label>
                        <textarea name="catatan" rows="2" class="w-full bg-slate-900 border border-slate-700 rounded p-2 text-white" placeholder="Keluhan motor atau instruksi alamat..."></textarea>
                    </div>

                    <button type="submit" name="submit_booking" class="w-full bg-blue-600 hover:bg-blue-500 font-bold py-2 rounded text-white transition">
                        <i class="fa-solid fa-paper-plane"></i> Simpan Jadwal Booking
                    </button>
                </form>
            </div>

            <!-- Tabel Antrian & Kirim WA Otomatis -->
            <div class="lg:col-span-2 bg-slate-800/40 border border-slate-700 p-6 rounded-xl space-y-4">
                <h2 class="text-lg font-semibold text-emerald-400"><i class="fa-solid fa-list-check"></i> Daftar Antrian Booking & Blast WA</h2>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-300">
                        <thead class="bg-slate-800 text-xs text-slate-400 uppercase border-b border-slate-700">
                            <tr>
                                <th class="p-3">Pelanggan</th>
                                <th class="p-3">Jadwal (Tgl & Jam)</th>
                                <th class="p-3">Segmen & Layanan</th>
                                <th class="p-3 text-center">Aksi Kirim WA</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800">
                            <?php if ($result_booking && mysqli_num_rows($result_booking) > 0): ?>
                                <?php while($b = mysqli_fetch_assoc($result_booking)): ?>
                                <?php 
                                    // Pengecekan Fleksibel Semua Kemungkinan Nama Kolom
                                    $nama_p     = !empty($b['nama']) ? $b['nama'] : (!empty($b['name']) ? $b['name'] : 'Pelanggan #' . $b['customer_id']);
                                    $nomor_p    = !empty($b['no_hp']) ? $b['no_hp'] : (!empty($b['no_wa']) ? $b['no_wa'] : (!empty($b['phone']) ? $b['phone'] : (!empty($b['nomor_hp']) ? $b['nomor_hp'] : '')));
                                    $no_wa      = preg_replace('/[^0-9]/', '', $nomor_p);
                                    $tgl_fmt    = !empty($b['tanggal_booking']) ? date('d-m-Y', strtotime($b['tanggal_booking'])) : '-';
                                    $jam_fmt    = !empty($b['jam_booking']) ? date('H:i', strtotime($b['jam_booking'])) : '-';
                                    $segmen_cust= $b['kategori_segmen'] ?? 'Regular';

                                    // TEMPLATE PESAN OTOMATIS PANJANG DAN MENARIK
                                    if ($segmen_cust == 'Ojol') {
                                        $msg = "Halo Bro {$nama_p}! 👋\n\nMotor dipake tempur harian buat nyari nafkah harus selalu dalam kondisi prima biar orderan makin gacor dan mesin tetep adem! 🛵💨\n\nKhusus untuk Driver Ojol mitra EngineCRM Bengkel, kami sediakan promo & fasilitas spesial hari ini:\n\n🎁 Diskon Khusus Potongan Oli Rp 15.000\n⚡ Jalur Bebas Antri (Fast-Track Service)\n🔍 Gratis Cek Tekanan Ban, Rem & Rantai/CVT\n\n📅 Jadwal Servis: {$tgl_fmt} | Jam {$jam_fmt} WIB\n📍 Lokasi: EngineCRM Bengkel Utama\n\nBalas pesan ini dengan 'SIAP' atau tunjukkan aplikasi Ojol kamu saat tiba di bengkel ya Bro! 🛠️";
                                    } elseif ($segmen_cust == 'Ibu-Ibu') {
                                        $msg = "Selamat Pagi/Siang Ibu {$nama_p} 🌸\n\nSemoga aktivitas Ibu dan keluarga senantiasa dilancarkan ya Bu. Kami dari EngineCRM Bengkel ingin menginformasikan bahwa motor Ibu sudah memasuki jadwal perawatan berkala.\n\nTakut kerepotan atau tidak sempat keluar rumah? Tenang saja Bu, kami sediakan layanan khusus:\n\n🏠 GRATIS Biaya Antar-Jemput Motor (Home Service - Radius 5 KM)\n🛡️ Pengecekan Komponen Keselamatan Gratis (Rem, Ban, & Lampu)\n👩‍🔧 Laporan Kondisi Motor Transparan sebelum pengerjaan.\n\n📅 Rencana Penjemputan: {$tgl_fmt} | Jam {$jam_fmt} WIB\n\nApakah jadwal tersebut sudah sesuai dengan waktu luang Ibu? Silakan balas WA ini untuk konfirmasi lokasi penjemputan ya Bu. Terima kasih! 🙏";
                                    } elseif ($segmen_cust == 'Anak Muda') {
                                        $msg = "Bro {$nama_p}! 🔥\n\nPersiapan buat nongkrong weekend dan jalur Sunmori nih! Biar tarikan motor makin responsif dan mesin tetep adem pas diajak ngegas, EngineCRM Bengkel membuka program Night-Modif & Performance Tune-Up:\n\n🛠️ Paket Italian Tune-Up & Injector Cleaning Deep Service\n⚡ Pengecekan & Setting Performa CVT / Rantai\n🎁 Bonus Instant +50 Poin CRM Modif\n\n📅 Booking Slot: {$tgl_fmt} | Jam {$jam_fmt} WIB\n\nAmankan slot servis kamu sekarang sebelum kuota malam ini penuh. Balas 'GAS' untuk konfirmasi kehadiranmu Bro! 🏍️💨";
                                    } else {
                                        $msg = "Halo Kak {$nama_p},\n\nSemoga harimu menyenangkan! 👋\n\nBerbahagia mengabarkan bahwa jadwal servis berkala kendaraan Kakak di EngineCRM Bengkel telah disiapkan untuk tanggal {$tgl_fmt} jam {$jam_fmt} WIB.\n\nPerawatan rutin akan membantu menjaga konsumsi bensin tetap hemat dan komponen mesin awet dalam jangka panjang.\n\nJika Kakak ingin melakukan penyesuaian jam kedatangan, silakan tanyakan melalui balasan pesan ini ya Kak. Terima kasih! 😊👍";
                                    }

                                    $wa_link = "https://wa.me/" . $no_wa . "?text=" . urlencode($msg);
                                ?>
                                <tr class="hover:bg-slate-800/50">
                                    <td class="p-3 font-bold text-white">
                                        <?= htmlspecialchars($nama_p); ?>
                                    </td>
                                    <td class="p-3 font-mono text-xs">
                                        <div class="text-blue-400"><?= $tgl_fmt; ?></div>
                                        <div class="text-slate-400"><?= $jam_fmt; ?> WIB</div>
                                    </td>
                                    <td class="p-3">
                                        <span class="text-xs px-2 py-0.5 rounded border 
                                            <?php 
                                                if($segmen_cust == 'Ojol') echo 'bg-emerald-500/10 text-emerald-400 border-emerald-500/30';
                                                elseif($segmen_cust == 'Ibu-Ibu') echo 'bg-pink-500/10 text-pink-400 border-pink-500/30';
                                                else echo 'bg-purple-500/10 text-purple-400 border-purple-500/30';
                                            ?>">
                                            <?= htmlspecialchars($segmen_cust); ?>
                                        </span>
                                        <div class="text-xs text-slate-300 mt-1"><?= htmlspecialchars($b['layanan_khusus']); ?></div>
                                    </td>
                                    <td class="p-3 text-center">
                                        <?php if (!empty($no_wa)): ?>
                                            <a href="<?= $wa_link; ?>" target="_blank" class="bg-emerald-600 hover:bg-emerald-500 text-white text-xs px-3 py-1.5 rounded inline-flex items-center gap-1.5 font-semibold transition">
                                                <i class="fa-brands fa-whatsapp"></i> Remind WA
                                            </a>
                                        <?php else: ?>
                                            <span class="text-xs text-slate-500">Tanpa Kontak WA</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="p-4 text-center text-slate-500">Belum ada antrian booking servis. Silakan buat booking baru di sebelah kiri.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

</body>
</html>