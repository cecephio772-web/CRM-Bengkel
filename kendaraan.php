<?php
// 1. KONEKSI DATABASE (PDO)
$host = 'localhost';
$db   = 'bengkel_crm';
$user = 'root';
$pass = ''; 

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Koneksi Database Gagal: " . $e->getMessage());
}

// 2. PROSES TAMBAH KENDARAAN (POST)
$message = '';
$alertType = 'info';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customer_id     = $_POST['customer_id'] ?? null;
    $jenis_kendaraan = $_POST['jenis_kendaraan'] ?? 'Motor';
    $plat_nomor      = trim($_POST['plat_nomor'] ?? '');
    $merk_tipe       = trim($_POST['merk_tipe'] ?? '');
    $tahun           = $_POST['tahun'] ?? date('Y');

    if ($customer_id && $plat_nomor && $merk_tipe) {
        // Otomatisasi penentuan jenis kendaraan berdasarkan nama merk
        $keywordsMobil = '/(avanza|xenia|brio|hr-v|hrv|xpander|pajero|fortuner|civic|alphard|innova|agya|ayla|calya|sigra|jazz|city|creta|terios|rush)/i';
        if (preg_match($keywordsMobil, $merk_tipe)) {
            $jenis_kendaraan = 'Mobil';
        }

        try {
            $stmt = $pdo->prepare("INSERT INTO vehicles (customer_id, jenis_kendaraan, plat_nomor, merk_tipe, tahun) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$customer_id, $jenis_kendaraan, $plat_nomor, $merk_tipe, $tahun]);
            $message = "Kendaraan berhasil ditambahkan!";
            $alertType = "success";
        } catch (PDOException $e) {
            $message = "Gagal menyimpan: " . $e->getMessage();
            $alertType = "danger";
        }
    } else {
        $message = "Mohon lengkapi seluruh formulir!";
        $alertType = "warning";
    }
}

// 3. AMBIL DAFTAR PELANGGAN UNTUK DROPDOWN
$stmtCustomers = $pdo->query("SELECT id, nama FROM customers ORDER BY nama ASC");
$customersList = $stmtCustomers->fetchAll(PDO::FETCH_ASSOC);

// 4. QUERY DATA KENDARAAN (GET)
$customer_id_filter = $_GET['customer_id'] ?? null;

if ($customer_id_filter) {
    $stmt = $pdo->prepare("SELECT * FROM vehicles WHERE customer_id = ? ORDER BY id DESC");
    $stmt->execute([$customer_id_filter]);
    $vehicles = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $stmt = $pdo->query("SELECT * FROM vehicles ORDER BY id DESC");
    $vehicles = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Kendaraan - Bengkel CRM</title>
    <!-- Bootstrap 5 CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #0f172a;
            color: #f8fafc;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .glass-card {
            background: rgba(30, 41, 59, 0.75);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37);
        }
        .form-control, .form-select {
            background-color: #1e293b !important;
            border-color: #334155 !important;
            color: #f8fafc !important;
        }
        .form-control:focus, .form-select:focus {
            border-color: #3b82f6 !important;
            box-shadow: 0 0 0 0.25rem rgba(59, 130, 246, 0.25) !important;
        }
        .badge-mobil {
            background-color: #0284c7;
            color: #ffffff;
        }
        .badge-motor {
            background-color: #eab308;
            color: #000000;
        }
    </style>
</head>
<body class="p-3 p-md-5">

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-white mb-0">
            <i class="bi bi-speedometer2 text-primary me-2"></i>Bengkel CRM - Layanan Service
        </h3>
        <?php if ($customer_id_filter): ?>
            <a href="kendaraan.php" class="btn btn-outline-light btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Tampilkan Semua
            </a>
        <?php endif; ?>
    </div>

    <?php if ($message): ?>
        <div class="alert alert-<?= $alertType ?> alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($message) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="row g-4">
        <!-- Form Input Kendaraan -->
        <div class="col-lg-4">
            <div class="glass-card p-4">
                <h5 class="fw-bold text-light mb-3">
                    <i class="bi bi-plus-circle-fill text-primary me-2"></i>Tambah Kendaraan
                </h5>
                <form action="" method="POST">
                    <div class="mb-3">
                        <label class="form-label text-slate-300">Pilih Pelanggan</label>
                        <select name="customer_id" class="form-select" required>
                            <option value="">-- Pilih Pelanggan --</option>
                            <?php foreach ($customersList as $cust): ?>
                                <option value="<?= $cust['id'] ?>" <?= ($customer_id_filter == $cust['id']) ? 'selected' : '' ?>>
                                    #<?= $cust['id'] ?> - <?= htmlspecialchars($cust['nama']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-slate-300">Jenis Kendaraan</label>
                        <select name="jenis_kendaraan" class="form-select" required>
                            <option value="Motor">🏍️ Motor</option>
                            <option value="Mobil">🚗 Mobil</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-slate-300">Plat Nomor</label>
                        <input type="text" name="plat_nomor" class="form-control" placeholder="Contoh: B 1234 ABC" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-slate-300">Merk & Tipe Kendaraan</label>
                        <input type="text" name="merk_tipe" class="form-control" placeholder="Contoh: Toyota Avanza / Yamaha Aerox" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-slate-300">Tahun</label>
                        <input type="number" name="tahun" class="form-control" value="2023" required>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 fw-bold">
                        <i class="bi bi-save me-1"></i> Simpan Kendaraan
                    </button>
                </form>
            </div>
        </div>

        <!-- Tabel Daftar Kendaraan -->
        <div class="col-lg-8">
            <div class="glass-card p-4">
                <h5 class="fw-bold text-light mb-3">
                    <i class="bi bi-car-front-fill text-warning me-2"></i>Daftar Service Kendaraan
                    <?php if ($customer_id_filter): ?>
                        <span class="badge bg-primary fs-6 ms-2">Pelanggan #<?= htmlspecialchars($customer_id_filter) ?></span>
                    <?php endif; ?>
                </h5>
                
                <div class="table-responsive">
                    <table class="table table-dark table-hover align-middle mb-0">
                        <thead class="table-secondary">
                            <tr>
                                <th>Jenis</th>
                                <th>Plat Nomor</th>
                                <th>Merk / Tipe</th>
                                <th>Tahun</th>
                                <th>ID Cust</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($vehicles) > 0): ?>
                                <?php foreach ($vehicles as $row): 
                                    $merk = strtolower($row['merk_tipe'] ?? '');
                                    $dbJenis = $row['jenis_kendaraan'] ?? 'Motor';
                                    
                                    $isMobil = ($dbJenis === 'Mobil') || 
                                               preg_match('/(avanza|xenia|brio|hr-v|hrv|xpander|pajero|fortuner|civic|alphard|innova|agya|ayla|calya|sigra|jazz|city|creta|terios|rush)/i', $merk);
                                ?>
                                <tr>
                                    <td>
                                        <?php if ($isMobil): ?>
                                            <span class="badge badge-mobil"><i class="bi bi-car-front-fill me-1"></i> Mobil</span>
                                        <?php else: ?>
                                            <span class="badge badge-motor"><i class="bi bi-scooter me-1"></i> Motor</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="fw-bold text-uppercase"><?= htmlspecialchars($row['plat_nomor'] ?? '-') ?></td>
                                    <td><?= htmlspecialchars($row['merk_tipe'] ?? '-') ?></td>
                                    <td><?= htmlspecialchars($row['tahun'] ?? '-') ?></td>
                                    <td>
                                        <a href="kendaraan.php?customer_id=<?= $row['customer_id'] ?>" class="badge bg-secondary text-decoration-none">
                                            #<?= htmlspecialchars($row['customer_id'] ?? '-') ?>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">Belum ada data kendaraan.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>