<?php
$servername = "localhost";
$username = "root";
$password = ""; // Sesuaikan dengan password database
$dbname = "croquant_cookies"; // Ganti dengan nama database kamu

// Buat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>