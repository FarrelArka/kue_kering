<?php
include "koneksi.php";
session_start();

// Cek apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    echo "Anda harus login terlebih dahulu.";
    exit();
}

// Cek jika ada request POST untuk menghapus order
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['transaction_id'])) {
    $transaction_id = $_POST['transaction_id'];

    // Mulai transaksi
    $conn->begin_transaction();

    try {
        // Hapus dari tabel detail_transaction
        $query_detail = "DELETE FROM detail_transaction WHERE transaction_id=?";
        $stmt_detail = $conn->prepare($query_detail);
        $stmt_detail->bind_param("i", $transaction_id);
        $stmt_detail->execute();

        // Hapus dari tabel transaction
        $query_transaction = "DELETE FROM transaction WHERE transaction_id=?";
        $stmt_transaction = $conn->prepare($query_transaction);
        $stmt_transaction->bind_param("i", $transaction_id);
        $stmt_transaction->execute();

        // Commit transaksi
        $conn->commit();
        $_SESSION['message'] = "Pesanan berhasil dihapus.";
    } catch (Exception $e) {
        // Rollback jika terjadi kesalahan
        $conn->rollback();
        $_SESSION['error'] = "Gagal menghapus pesanan.";
    }
}

// Redirect kembali ke halaman order
header("Location: order.php");
exit();
?>
