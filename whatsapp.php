<?php
// api/whatsapp.php
require_once '../config/database.php';

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    if (!empty($data['customer_id']) && !empty($data['pesan'])) {
        try {
            // Kolom disesuaikan dengan struktur tabel asli di database
            $query = "INSERT INTO whatsapp_logs (customer_id, pesan) VALUES (:customer_id, :pesan)";
            $stmt = $conn->prepare($query);

            $stmt->bindParam(':customer_id', $data['customer_id']);
            $stmt->bindParam(':pesan', $data['pesan']);

            if ($stmt->execute()) {
                http_response_code(201);
                echo json_encode([
                    "status" => true,
                    "message" => "Pesan WhatsApp berhasil dicatat ke log!",
                    "log_id" => $conn->lastInsertId()
                ]);
            }
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(["status" => false, "message" => $e->getMessage()]);
        }
    } else {
        http_response_code(400);
        echo json_encode(["status" => false, "message" => "Customer ID dan Pesan wajib diisi!"]);
    }
}