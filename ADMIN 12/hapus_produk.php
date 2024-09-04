<?php
// Koneksi ke database
include "koneksi.php";

// Cek apakah ID produk tersedia
if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    // Query untuk mengambil gambar produk dari database
    $sql = "SELECT image FROM products WHERE product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row) {
        $imagePath = $row['image'];
        
        // Hapus gambar dari folder uploads
        if (file_exists('../uploads/' . $imagePath)) {
            unlink('../uploads/' . $imagePath);
        }

        // Query untuk menghapus produk dari database
        $sql = "DELETE FROM products WHERE product_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $product_id);

        if ($stmt->execute()) {
            echo "<script>alert('Produk berhasil dihapus!'); window.location.href='../produk.php';</script>";
        } else {
            echo "<script>alert('Gagal menghapus produk. Silakan coba lagi.'); window.location.href='../produk.php';</script>";
        }
    } else {
        echo "<script>alert('Produk tidak ditemukan.'); window.location.href='../produk.php';</script>";
    }

    $stmt->close();
} else {
    echo "<script>alert('ID produk tidak valid.'); window.location.href='../produk.php';</script>";
}

// Tutup koneksi
$conn->close();
?>
