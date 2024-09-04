<?php
// Koneksi ke database
include "koneksi.php";
// Cek apakah formulir telah disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari formulir
    $product_id = $_POST['product_id'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];

    // Proses upload gambar
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $imageTmpName = $_FILES['image']['tmp_name'];
        $imageName = $_FILES['image']['name'];
        $imagePath = '../uploads/' . basename($imageName);
        
        // Pindahkan gambar ke folder uploads
        if (move_uploaded_file($imageTmpName, $imagePath)) {
            // Query untuk memperbarui produk di database
            $sql = "UPDATE products SET name=?, description=?, price=?, stock=?, image=? WHERE product_id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssdssi", $name, $description, $price, $stock, $imagePath, $product_id);

            if ($stmt->execute()) {
                echo "<script>alert('Produk berhasil diperbarui!'); window.location.href='../produk.php';</script>";
            } else {
                echo "<script>alert('Gagal memperbarui produk. Silakan coba lagi.'); window.location.href='edit_produk.php?id=$product_id';</script>";
            }
        } else {
            echo "<script>alert('Gagal meng-upload gambar.'); window.location.href='edit_produk.php?id=$product_id';</script>";
        }
    } else {
        // Jika tidak ada gambar baru, hanya update informasi produk
        $sql = "UPDATE products SET name=?, description=?, price=?, stock=? WHERE product_id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssdsi", $name, $description, $price, $stock, $product_id);

        if ($stmt->execute()) {
            echo "<script>alert('Produk berhasil diperbarui!'); window.location.href='../produk.php';</script>";
        } else {
            echo "<script>alert('Gagal memperbarui produk. Silakan coba lagi.'); window.location.href='edit_produk.php?id=$product_id';</script>";
        }
    }

    $stmt->close();
}


$conn->close();
?>
