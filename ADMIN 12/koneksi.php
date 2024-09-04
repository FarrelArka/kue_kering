<?php
$conn = new mysqli("localhost", "root", "", "croquant_cookies");

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>
