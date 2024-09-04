<?php
include "koneksi.php";
session_start(); // Mulai sesi

// Cek apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    echo "Anda harus login terlebih dahulu.";
    exit();
}

// Ambil ID pengguna dari sesi
$user_id = $_SESSION['user_id'];

// Mengambil data keranjang belanja
$cart_query = "SELECT cart.product_id, cart.quantity, products.name, products.image, products.price 
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

if (isset($_POST['checkout'])) {
    $payment_method = $_POST['payment_method'] ?? 'cod';
    $use_points = isset($_POST['use_points']);
    $discount = 0;

    // Hitung diskon berdasarkan poin jika checkbox dipilih
    if ($use_points) {
        $discount = $poin * 1000; // Setel diskon dari poin
        $total -= $discount;
        if ($total < 0) {
            $total = 0; // Pastikan total tidak kurang dari 0
        }

        // Update poin pengguna menjadi 0 jika digunakan
        $update_poin_query = "UPDATE point SET point = 0 WHERE user_id = ?";
        $stmt = $conn->prepare($update_poin_query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
    }

    // Mulai transaksi
    $conn->begin_transaction();

    try {
        // Insert data ke tabel transaction
        $status = "pending"; // Status default transaksi
        $transaction_query = "INSERT INTO `transaction` (user_id, payment_method, total_bayar, status, discount) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($transaction_query);
        $stmt->bind_param("isisi", $user_id, $payment_method, $total, $status, $discount);
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

            // Masukkan data ke tabel detail_transaction
            $detail_transaction_query = "INSERT INTO detail_transaction (transaction_id, product_id, quantity, total_bayar) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($detail_transaction_query);
            $stmt->bind_param("iiii", $transaction_id, $product_id, $quantity, $total_item);
            $stmt->execute();
        }

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
