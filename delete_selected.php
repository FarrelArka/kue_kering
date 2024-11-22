<?php
session_start();
include "koneksi.php";

// Cek apakah pengguna sudah login
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit;
}

// Cek apakah ada pengguna yang dipilih untuk dihapus
if (isset($_POST['delete_selected']) && !empty($_POST['selected_users'])) {
    $selected_users = $_POST['selected_users'];

    try {
        // Mulai transaksi
        $conn->begin_transaction();

        foreach ($selected_users as $user_id) {
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
        }

        // Commit transaksi
        $conn->commit();

        header("Location: admin.php");
        exit;
    } catch (Exception $e) {
        // Rollback transaksi jika ada kesalahan
        $conn->rollback();
        echo "Failed to delete selected users and related data: " . $e->getMessage();
    }
} else {
    header("Location: admin.php");
    exit;
}

// Tutup koneksi
$conn->close();
?>
