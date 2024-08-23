<?php
include "koneksi.php";
session_start(); // Mulai sesi

// Cek apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    echo "Anda harus login terlebih dahulu.";
    exit();
}

// Ambil ID pengguna dari sesi
$id = $_SESSION['user_id'];

// Mengambil data dari database berdasarkan ID user yang login
$query = "SELECT * FROM users WHERE user_id=?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

// Cek apakah data pengguna ditemukan
if ($data = $result->fetch_assoc()) {
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja</title>
    <link rel="stylesheet" href="cart.css">
</head>
<body>
    
<nav class="header">
        <div class="logoContent">
            <a href="#" class="logo"><img src="images/logo kita.png" alt="Logo"></a>
            <h1 class="logoName">Croquant Cookies</h1>
        </div>

        <div class="navbar">
            <a href="user.php">Home</a>
            <a href="product.html">Product</a>
            <a href="cart.html">Cart</a>
            <a href="#">Order</a>
            <a href="#contact">Contact</a>
        </div>

        <div class="icon">
            <i class="fas fa-search" id="search"></i>
            <i class="fas fa-bars" id="menu-bar"></i>
        </div>

        <div class="search">
            <input type="search" placeholder="Search...">
        </div>

        <div class="profile">
            <img src="<?php echo htmlspecialchars($data['foto']); ?>" alt="Profile Picture" width="50" height="50" id="profileImage">
        </div>
    </nav>
    <div class="container">
        <div class="cart">
            <div class="cart-header">
                <h2>Keranjang Anda</h2>
                <span>Jumlah Item: 3</span>
            </div>
            <div class="cart-item">
                <div class="item-info">
                    <img src="images/3.png" alt="Nama Produk">
                    <div class="item-details">
                        <h3>Kue Putri Salju</h3>
                        <p>Deskripsi singkat produk</p>
                    </div>
                </div>
                <div class="item-price">Rp. 48.000</div>
            </div>
            <!-- Tambahkan lebih banyak item di sini -->
            <div class="cart-total">
                Total: Rp. 144.000
            </div>
            <div class="cart-actions">
                <button class="btn btn-secondary">Kembali Berbelanja</button>
                <button class="btn">Checkout</button>
            </div>
        </div>
    </div>
</body>
</html>

<?php
} else {
    echo "Data user tidak ditemukan.";
}

// Tutup koneksi
$conn->close();
?>
