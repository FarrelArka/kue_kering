<?php
session_start(); // Memulai session

// Cek apakah pengguna sudah login
if (!isset($_SESSION['username']) || !isset($_SESSION['role'])) {
    header("Location: login.php");
    exit;
}

// Cek apakah metode request adalah POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari formulir
    $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;
    $points = isset($_POST['points']) ? intval($_POST['points']) : 0;

    // Koneksi ke database
    include "koneksi.php";

    // Validasi input
    if ($user_id > 0 && $points >= 0) {
        // Query untuk memperbarui poin pengguna
        $sql = "UPDATE point SET point = ? WHERE user_id = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("ii", $points, $user_id);
            if ($stmt->execute()) {
                // Redirect kembali ke halaman manage_points.php dengan pesan sukses
                header("Location: ../manage_points.php?status=success");
                exit;
            } else {
                // Redirect kembali dengan pesan error
                header("Location: ../manage_points.php?status=error");
                exit;
            }
        } else {
            // Redirect kembali dengan pesan error
            header("Location: ../manage_points.php?status=error");
            exit;
        }
    } else {
        // Redirect kembali dengan pesan error
        header("Location: ../manage_points.php?status=error");
        exit;
    }
} else {
    // Redirect jika bukan metode POST
    header("Location: manage_points.php");
    exit;
}

// Tutup koneksi
$conn->close();
?>
