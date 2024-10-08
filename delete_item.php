<?php
include "koneksi.php";
session_start();

// Cek apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    echo "Anda harus login terlebih dahulu.";
    exit();
}

// Ambil ID pengguna dari sesi
$user_id = $_SESSION['user_id'];

// Cek apakah product_id diterima dari form
if (isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];

    // Query untuk menghapus item dari keranjang
    $delete_query = "DELETE FROM cart WHERE user_id = ? AND product_id = ?";
    $stmt = $conn->prepare($delete_query);
    $stmt->bind_param("ii", $user_id, $product_id);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Produk berhasil dihapus dari keranjang.";
    } else {
        $_SESSION['message'] = "Gagal menghapus produk dari keranjang.";
    }
} else {
    $_SESSION['message'] = "Produk tidak ditemukan.";
}

// Redirect kembali ke halaman keranjang
header("Location: cart.php");
exit();
?>
