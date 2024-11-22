<?php
require_once dirname(__FILE__) . '/vendor/autoload.php';
include "koneksi.php";

// Konfigurasi Midtrans
\Midtrans\Config::$serverKey = 'SB-Mid-server-x4QiK3PuTSAM5JZ62QH-W-n8';
\Midtrans\Config::$isProduction = false;
\Midtrans\Config::$isSanitized = true;
\Midtrans\Config::$is3ds = true;

// Mendapatkan notifikasi dari Midtrans
$notif = new \Midtrans\Notification();

// Ambil order_id dan status pembayaran dari notifikasi
$order_id = $notif->order_id;
$transaction_status = $notif->transaction_status;
$fraud_status = $notif->fraud_status;

// Proses notifikasi berdasarkan status transaksi
if ($transaction_status == 'capture') {
    if ($fraud_status == 'accept') {
        // Transaksi berhasil
        $status = 'Sudah Bayar';
    }
} elseif ($transaction_status == 'settlement') {
    // Transaksi berhasil
    $status = 'Sudah Bayar';
} elseif ($transaction_status == 'deny' || $transaction_status == 'expire' || $transaction_status == 'cancel') {
    // Transaksi gagal
    $status = 'gagal';
} else {
    // Transaksi pending atau status lainnya
    $status = 'belum bayar';
}

// Update status transaksi di database
$update_transaction_query = "UPDATE transaction SET status = ? WHERE transaction_id = ?";
$stmt = $conn->prepare($update_transaction_query);
$stmt->bind_param("si", $status, $order_id);
$stmt->execute();
?>
