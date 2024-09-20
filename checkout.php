<?php
require_once dirname(__FILE__) . '/vendor/autoload.php';
include "koneksi.php";
session_start(); // Mulai sesi

if (!isset($_SESSION['user_id'])) {
    echo "Anda harus login terlebih dahulu.";
    exit();
}

// Ambil ID pengguna dari sesi
$user_id = $_SESSION['user_id'];

// Mengambil data keranjang belanja
$cart_query = "SELECT cart.product_id, cart.quantity, products.name, products.price 
              FROM cart 
              JOIN products ON cart.product_id = products.product_id
              WHERE cart.user_id = ?";
$stmt = $conn->prepare($cart_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$cart_result = $stmt->get_result();
$cart_data = $cart_result->fetch_all(MYSQLI_ASSOC);

// Mengambil data poin pengguna
$poin_query = "SELECT point FROM point WHERE user_id=?";
$stmt = $conn->prepare($poin_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$poin_result = $stmt->get_result();
$poin_data = $poin_result->fetch_assoc();

$poin = $poin_data['point'] ?? 0; // Jika null, setel poin menjadi 0

$total = 0;
foreach ($cart_data as $item) {
    $item_total = $item['quantity'] * $item['price'];
    $total += $item_total;
}

// Cek apakah ada request untuk proceed_payment
if (isset($_POST['proceed_payment'])) {
    $payment_method = $_POST['payment_method'] ?? 'cod';
    $transfer_method = $_POST['transfer_method'] ?? null;

    $discount = min($total, $poin);
    $remaining_poin = max(0, $poin - $total);
    $total_final = $total - $discount;

    // Mulai transaksi
    $conn->begin_transaction();

    try {
        // Insert data ke tabel transaction
        $status = 'pending'; // Status awal transaksi
        $transaction_query = "INSERT INTO `transaction` (user_id, payment_method, total_bayar, status, discount) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($transaction_query);
        $stmt->bind_param("isisi", $user_id, $payment_method, $total_final, $status, $discount);
        $stmt->execute();

        // Dapatkan ID transaksi yang baru saja dimasukkan
        $transaction_id = $conn->insert_id;

        // Iterasi untuk setiap item di cart
        foreach ($cart_data as $item) {
            $product_id = $item['product_id'];
            $quantity = $item['quantity'];
            $price = $item['price'];
            $total_item = $quantity * $price;

            // Kurangi stok produk
            $update_stock_query = "UPDATE products SET stock = stock - ? WHERE product_id = ?";
            $stmt = $conn->prepare($update_stock_query);
            $stmt->bind_param("ii", $quantity, $product_id);
            $stmt->execute();

            // Masukkan detail transaksi ke tabel detail_transaction
            $detail_query = "INSERT INTO `detail_transaction` (transaction_id, product_id, quantity, total_bayar) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($detail_query);
            $stmt->bind_param("iiii", $transaction_id, $product_id, $quantity, $total_item);
            $stmt->execute();
        }

        // Update poin pengguna
        $update_poin_query = "UPDATE point SET point = ? WHERE user_id = ?";
        $stmt = $conn->prepare($update_poin_query);
        $stmt->bind_param("ii", $remaining_poin, $user_id);
        $stmt->execute();

        // Berikan poin tambahan sebesar 10% dari total bayar
        $new_points = intval($total_final * 0.10);
        $add_poin_query = "UPDATE point SET point = point + ? WHERE user_id = ?";
        $stmt = $conn->prepare($add_poin_query);
        $stmt->bind_param("ii", $new_points, $user_id);
        $stmt->execute();

        // Commit transaksi
        $conn->commit();

        // Hapus semua item dari keranjang pengguna
        $delete_cart_query = "DELETE FROM cart WHERE user_id = ?";
        $stmt = $conn->prepare($delete_cart_query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();

        // Jika menggunakan metode pembayaran selain COD, buat token pembayaran Midtrans
        if ($payment_method !== 'cod') {
            \Midtrans\Config::$serverKey = 'SB-Mid-server-x4QiK3PuTSAM5JZ62QH-W-n8';
            \Midtrans\Config::$isProduction = false;
            \Midtrans\Config::$isSanitized = true;
            \Midtrans\Config::$is3ds = true;

            $params = [
                'transaction_details' => [
                    'order_id' => $transaction_id,
                    'gross_amount' => $total_final,
                ],
                'customer_details' => [
                    'user_id' => $user_id,
                    // Tambahkan data lain seperti nama, email, dll.
                ],
            ];

            $snapToken = \Midtrans\Snap::getSnapToken($params);
            $_SESSION['snapToken'] = $snapToken;
            header("Location: cart.php");
            exit();
        } else {
            // Jika COD, langsung arahkan ke halaman pesanan
            $_SESSION['message'] = "Checkout berhasil. Pesanan Anda sedang diproses.";
            header("Location: order.php");
            exit();
        }

    } catch (Exception $e) {
        $conn->rollback();
        echo "Checkout gagal: " . $e->getMessage();
    }
}
?>
