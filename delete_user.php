<?php
session_start();
include "koneksi.php";

// Cek apakah pengguna sudah login
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit;
}

// Cek apakah parameter `user_id` sudah dikirim
if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];

    try {
        // Mulai transaksi
        $conn->begin_transaction();

        // Hapus data dari tabel `cart`
        $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->close();

        // Hapus data dari tabel `point`
        $stmt = $conn->prepare("DELETE FROM point WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->close();

        // Hapus data dari tabel `detail_transaction` melalui `transaction`
        $stmt = $conn->prepare("DELETE dt FROM detail_transaction dt
                                  JOIN `transaction` t ON dt.transaction_id = t.transaction_id
                                  WHERE t.user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->close();

        // Hapus data dari tabel `transaction`
        $stmt = $conn->prepare("DELETE FROM transaction WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->close();

        // Hapus data dari tabel `users`
        $stmt = $conn->prepare("DELETE FROM users WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->close();

        // Commit transaksi
        $conn->commit();

        header("location:admin.php");
    } catch (Exception $e) {
        // Rollback transaksi jika ada kesalahan
        $conn->rollback();
        echo "Failed to delete user and related data: " . $e->getMessage();
    }
}
// Tutup koneksi
$conn->close();
?>
