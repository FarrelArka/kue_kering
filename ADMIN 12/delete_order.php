<?php
session_start();
include "koneksi.php"; // Ganti dengan path ke file koneksi Anda

// Cek apakah pengguna sudah login
if (!isset($_SESSION['username']) || !isset($_SESSION['role'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['transaction_id'])) {
    $transaction_id = intval($_POST['transaction_id']);

    // Hapus data dari tabel detail_transaction terlebih dahulu
    $sql = "DELETE FROM detail_transaction WHERE transaction_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $transaction_id);

    if ($stmt->execute()) {
        // Hapus data dari tabel transaction setelah detail_transaction dihapus
        $sql = "DELETE FROM transaction WHERE transaction_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $transaction_id);

        if ($stmt->execute()) {
            header("Location: ../manage_order.php");
            exit;
        } else {
            echo "Failed to delete transaction.";
        }
    } else {
        echo "Failed to delete from detail_transaction.";
    }

    $stmt->close();
}
$conn->close();
?>
