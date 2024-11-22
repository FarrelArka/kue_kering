<?php
include "koneksi.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];

    $update_transaction_query = "UPDATE transaction SET status = ? WHERE transaction_id = ?";
    $stmt = $conn->prepare($update_transaction_query);
    $stmt->bind_param("si", $status, $order_id);

    if ($stmt->execute()) {
        echo "Status pembayaran berhasil diupdate.";
    } else {
        echo "Gagal mengupdate status pembayaran: " . $stmt->error;
    }
}
?>
