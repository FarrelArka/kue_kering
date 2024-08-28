<?php
// Include koneksi database
include '../koneksi.php';

// Cek jika form telah disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $name = $_POST['name'];
    $desk = $_POST['desk'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    
    // Inisialisasi variabel untuk file gambar
    $image_name = '';
    
    // Cek jika file gambar diupload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        // Nama file gambar
        $image_name = basename($_FILES['image']['name']);
        
        // Path direktori upload
        $upload_dir = '../uploads/';  // Path relatif ke direktori yang sesuai
        $upload_file = $upload_dir . $image_name;
        
        // Pastikan direktori upload ada
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        // Pindahkan file gambar ke direktori upload
        if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_file)) {
            echo "Gambar berhasil diupload.<br>";
        } else {
            echo "Gagal mengupload gambar.<br>";
            $image_name = 'default.jpg'; // Gunakan gambar default jika gagal upload
        }
    } else {
        $image_name = 'default.jpg'; // Nama default jika tidak ada gambar
    }
    
    // Query untuk menyimpan data ke database
    $sql = "INSERT INTO products (name, description, price, stock, image) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssiis", $name, $desk, $price, $stock, $image_name);
    
    if ($stmt->execute()) {
        echo "Produk berhasil ditambahkan!";
    } else {
        echo "Gagal menambahkan produk: " . $stmt->error;
    }
    
    // Tutup statement dan koneksi
    $stmt->close();
    $conn->close();
} else {
    echo "Permintaan tidak valid.";
}
?>
