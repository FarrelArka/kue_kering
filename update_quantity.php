<?php
include "koneksi.php";
session_start();

if (isset($_POST['update_quantity'])) {
    $user_id = $_SESSION['user_id'];
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    // Pastikan nilai $quantity valid dan lebih dari 0
    if (isset($user_id) && isset($product_id) && $quantity > 0) {
        $update_query = "UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("iii", $quantity, $user_id, $product_id);
        $stmt->execute();
        header("Location: cart.php"); // Redirect kembali ke halaman cart
    } else {
        echo "Kuantitas tidak valid.";
    }
}
?>
