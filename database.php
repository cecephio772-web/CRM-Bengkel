<?php
$host = "localhost";
$user = "root";      // Default Laragon
$pass = "";          // Default Laragon (kosong)
$db   = "bengkel_crm"; // Nama database di HeidiSQL kamu

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi Database Gagal: " . mysqli_connect_error());
}
?>