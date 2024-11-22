<?php
include "koneksi.php";
session_start();

if (isset($_POST['add_to_cart'])) {
    $user_id = $_SESSION['user_id'];
    $product_id = $_POST['product_id'];

    // Pastikan nilai $user_id dan $product_id valid sebelum melanjutkan query
    if (isset($user_id) && isset($product_id)) {
        $check_query = "SELECT * FROM cart WHERE user_id = ? AND product_id = ?";
        $stmt = $conn->prepare($check_query);
        $stmt->bind_param("ii", $user_id, $product_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Jika produk sudah ada di cart, tambahkan jumlahnya
            $update_query = "UPDATE cart SET quantity = quantity + 1 WHERE user_id = ? AND product_id = ?";
            $stmt = $conn->prepare($update_query);
            $stmt->bind_param("ii", $user_id, $product_id);
            $stmt->execute();
            $_SESSION['alert_message'] = "Produk berhasil ditambahkan ke keranjang.";
        } else {
            // Jika produk belum ada di cart, tambahkan ke cart dengan kuantitas 1
            $insert_query = "INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, 1)";
            $stmt = $conn->prepare($insert_query);
            $stmt->bind_param("ii", $user_id, $product_id);
            $stmt->execute();
            $_SESSION['alert_message'] = "Produk baru berhasil ditambahkan ke keranjang.";
        }
        header('location:user.php');
    } else {
        echo "Terjadi kesalahan: user_id atau product_id tidak ditemukan.";
    }
}
?>
