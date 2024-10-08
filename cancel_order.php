<?php
session_start();
include "koneksi.php";

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$transaction_id = intval($_POST['transaction_id']);

// Check if the order belongs to the user
$stmt = $conn->prepare("SELECT * FROM transaction WHERE transaction_id = ? AND user_id = ?");
$stmt->bind_param("ii", $transaction_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $order = $result->fetch_assoc();
    
    // Check the current status of the order
    if ($order['status'] == 'selesai') {
        $_SESSION['error'] = "Pesanan tidak dapat dibatalkan karena sudah selesai.";
    } elseif ($order['status'] == 'cancelled') {
        $_SESSION['error'] = "Pesanan tidak dapat dibatalkan karena sudah dibatalkan sebelumnya.";
    } elseif ($order['status'] == 'proses') {
        $_SESSION['error'] = "Pesanan tidak dapat dibatalkan karena sedang diproses.";
    } else {
        // Update the order status to cancelled if not finished, already cancelled, or in process
        $update_stmt = $conn->prepare("UPDATE transaction SET status = 'cancelled' WHERE transaction_id = ?");
        $update_stmt->bind_param("i", $transaction_id);
        $update_stmt->execute();

        if ($update_stmt->affected_rows > 0) {
            $_SESSION['message'] = "Pesanan berhasil dibatalkan.";
        } else {
            $_SESSION['error'] = "Gagal membatalkan pesanan. Silakan coba lagi.";
        }
    }
} else {
    $_SESSION['error'] = "Pesanan tidak valid atau tidak dapat dibatalkan.";
}

// Debugging messages
if ($result->num_rows == 0) {
    error_log("Tidak ada pesanan ditemukan dengan transaction_id = $transaction_id dan user_id = $user_id");
}

header("Location: order.php");
exit();
?>
