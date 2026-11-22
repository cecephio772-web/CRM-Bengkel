<?php
// api/customers.php
require_once '../config/database.php';

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    // Ambil Semua Pelanggan
    try {
        $query = "SELECT * FROM customers ORDER BY id DESC";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        
        $customers = $stmt->fetchAll(PDO::FETCH_ASSOC);

        http_response_code(200);
        echo json_encode([
            "status" => true,
            "message" => "Berhasil mengambil data pelanggan",
            "data" => $customers
        ]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(["status" => false, "message" => $e->getMessage()]);
    }

} elseif ($method === 'POST') {
    // Tambah Pelanggan Baru
    $data = json_decode(file_get_contents("php://input"), true);

    if (!empty($data['nama']) && !empty($data['no_hp'])) {
        try {
            $query = "INSERT INTO customers (nama, no_hp, email, tipe_member) VALUES (:nama, :no_hp, :email, :tipe_member)";
            $stmt = $conn->prepare($query);

            $stmt->bindParam(':nama', $data['nama']);
            $stmt->bindParam(':no_hp', $data['no_hp']);
            $email = $data['email'] ?? null;
            $tipe_member = $data['tipe_member'] ?? 'Regular';
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':tipe_member', $tipe_member);

            if ($stmt->execute()) {
                http_response_code(201);
                echo json_encode([
                    "status" => true,
                    "message" => "Pelanggan berhasil ditambahkan!",
                    "id" => $conn->lastInsertId()
                ]);
            }
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(["status" => false, "message" => $e->getMessage()]);
        }
    } else {
        http_response_code(400);
        echo json_encode(["status" => false, "message" => "Nama dan No HP wajib diisi!"]);
    }
}