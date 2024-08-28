<?php
include "koneksi.php";
session_start();

if (isset($_POST['checkout'])) {
    $user_id = $_SESSION['user_id'];
    $total_bayar = 0; // Inisialisasi total pembayaran
    $status = "pending"; // Status default transaksi

    // Mulai transaksi
    $conn->begin_transaction();

    try {
        // Ambil semua produk dari cart
        $cart_query = "SELECT product_id, quantity FROM cart WHERE user_id = ?";
        $stmt = $conn->prepare($cart_query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $cart_result = $stmt->get_result();

        // Insert data ke tabel transaction
        $payment_method = "transfer_bank"; // Contoh metode pembayaran
        $discount = 0; // Contoh diskon, bisa diganti sesuai kebutuhan
        $transaction_query = "INSERT INTO `transaction` (user_id, payment_method, total_bayar, status, discount) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($transaction_query);
        $stmt->bind_param("isisi", $user_id, $payment_method, $total_bayar, $status, $discount);
        $stmt->execute();

        // Dapatkan ID transaksi yang baru saja dimasukkan
        $transaction_id = $conn->insert_id;

        // Iterasi untuk setiap item di cart
        while ($item = $cart_result->fetch_assoc()) {
            $product_id = $item['product_id'];
            $quantity = $item['quantity'];

            // Kurangi stok produk
            $update_stock_query = "UPDATE products SET stock = stock - ? WHERE product_id = ?";
            $stmt = $conn->prepare($update_stock_query);
            $stmt->bind_param("ii", $quantity, $product_id);
            $stmt->execute();

            // Ambil harga produk
            $product_query = "SELECT price FROM products WHERE product_id = ?";
            $stmt = $conn->prepare($product_query);
            $stmt->bind_param("i", $product_id);
            $stmt->execute();
            $product_result = $stmt->get_result();
            $product_data = $product_result->fetch_assoc();
            $price = $product_data['price'];

            // Hitung total bayar untuk setiap item
            $total_item = $quantity * $price;
            $total_bayar += $total_item;

            // Masukkan data ke tabel detail_transaction
            $detail_transaction_query = "INSERT INTO detail_transaction (transaction_id, product_id, quantity, total_bayar) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($detail_transaction_query);
            $stmt->bind_param("iiii", $transaction_id, $product_id, $quantity, $total_item);
            $stmt->execute();
        }

        // Update total_bayar di tabel transaction
        $update_transaction_query = "UPDATE `transaction` SET total_bayar = ? WHERE transaction_id = ?";
        $stmt = $conn->prepare($update_transaction_query);
        $stmt->bind_param("ii", $total_bayar, $transaction_id);
        $stmt->execute();

        // Hapus semua item dari cart
        $delete_cart_query = "DELETE FROM cart WHERE user_id = ?";
        $stmt = $conn->prepare($delete_cart_query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();

        // Commit transaksi
        $conn->commit();
        header("location:order.php");
    } catch (Exception $e) {
        // Rollback jika terjadi kesalahan
        $conn->rollback();
        echo "Terjadi kesalahan: " . $e->getMessage();
    }
}

// Tutup koneksi
$conn->close();
?>
