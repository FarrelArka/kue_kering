<?php
// Koneksi ke database
include "koneksi.php";

// Cek apakah formulir telah disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari formulir
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];

    // Proses upload gambar
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $imageTmpName = $_FILES['image']['tmp_name'];
        $imageName = $_FILES['image']['name'];
        
        // Path tujuan untuk menyimpan gambar, pastikan folder "uploads" berada di luar folder "ADMIN 12"
        $uploadDir = '../uploads/'; 
        $imagePath = $uploadDir . basename($imageName);

        // Pindahkan gambar ke folder uploads
        if (move_uploaded_file($imageTmpName, $imagePath)) {
            // Query untuk menambahkan produk ke database
            $stmt = $conn->prepare("INSERT INTO products (name, description, price, stock, image) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("ssdss", $name, $description, $price, $stock, $imagePath);

            // Eksekusi query
            if ($stmt->execute()) {
                echo "<script>alert('Produk berhasil ditambahkan!'); window.location.href='../produk.php';</script>";
            } else {
                echo "<script>alert('Gagal menambahkan produk. Silakan coba lagi.'); window.location.href='process_tambah_produk.php';</script>";
            }

            $stmt->close();
        } else {
            echo "<script>alert('Gagal meng-upload gambar.'); window.location.href='tambah_produk.php';</script>";
        }
    } else {
        echo "<script>alert('Gambar tidak ditemukan atau terjadi kesalahan saat upload.'); window.location.href='tambah_produk.php';</script>";
    }
}

// Tutup koneksi
$conn->close();
?>
