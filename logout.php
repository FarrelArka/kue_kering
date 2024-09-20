<?php
session_start(); // Memulai sesi

// Menghancurkan semua data sesi
session_destroy();

// Mengarahkan pengguna kembali ke halaman login
header("Location: index.php");
exit;
?>
