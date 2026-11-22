<?php
header('Content-Type: application/json');
require_once '../config.php'; // Penyesuaian ke koneksi DB kamu

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $customer_id = $_GET['customer_id'] ?? null;
    if ($customer_id) {
        $stmt = $pdo->prepare("SELECT * FROM vehicles WHERE customer_id = ?");
        $stmt->execute([$customer_id]);
    } else {
        $stmt = $pdo->query("SELECT v.*, c.nama FROM vehicles v JOIN customers c ON v.customer_id = c.id");
    }
    echo json_encode(['status' => 'success', 'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
    exit;
}

if ($method === 'POST') {
    $input = json_decode(file_get_contents('php_input'), true) ?? $_POST;
    
    $customer_id    = $input['customer_id'] ?? null;
    $jenis_kendaraan = $input['jenis_kendaraan'] ?? 'Motor';
    $plat_nomor     = $input['plat_nomor'] ?? '';
    $merk_tipe      = $input['merk_tipe'] ?? '';
    $tahun          = $input['tahun'] ?? date('Y');

    if ($customer_id && $plat_nomor && $merk_tipe) {
        $stmt = $pdo->prepare("INSERT INTO vehicles (customer_id, jenis_kendaraan, plat_nomor, merk_tipe, tahun) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$customer_id, $jenis_kendaraan, $plat_nomor, $merk_tipe, $tahun]);
        echo json_encode(['status' => 'success', 'message' => 'Kendaraan berhasil ditambahkan']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Data tidak lengkap']);
    }
    exit;
}
?>