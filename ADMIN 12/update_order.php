<?php
session_start();
include "koneksi.php"; // Ganti dengan path ke file koneksi Anda

// Cek apakah pengguna sudah login
if(!isset($_SESSION['username']) || !isset($_SESSION['role'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['transaction_id']) && isset($_POST['status'])) {
    $transaction_id = intval($_POST['transaction_id']);
    $status = $_POST['status'];

    // Update status transaksi
    $sql = "UPDATE transaction SET status = ? WHERE transaction_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('si', $status, $transaction_id);

    if ($stmt->execute()) {
        header("Location: ../manage_order.php");
        exit;
    } else {
        echo "Failed to update transaction status.";
    }

    $stmt->close();
}
$conn->close();
?>
