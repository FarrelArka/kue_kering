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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Status</title>
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />

<!-- Font Awesome CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <link rel="stylesheet" href="order.css">
</head>
<body>
<header class="header">
        <div class="logoContent">
            <a href="#" class="logo"><img src="images/logo kita.png" alt="Logo"></a>
            <h1 class="logoName">Croquant Cookies</h1>
        </div>

        <nav class="navbar">
        <a href="user.php">Berada</a>
            <a href="product.php">Produk</a>
            <a href="cart.php">Keranjang</a>
            <a href="order.php">Pesan</a>
            <a href="#contact">Hubungi</a>
        </nav>

        
        <div class="nav-right">
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
        </div>
    </header>

    <!-- Home Section -->
    <section class="home" id="home">
        <div class="homeContent">
            <h2>Kue Enak Untuk Semua</h2>
            <p>Kue kering lezat untuk semua. Nikmati kelezatan dalam setiap gigitan, sempurna untuk momen istimewa.</p>
            <div class="home-btn">
                <a href="product.php"><button>Lihat Selengkapnya</button></a>
            </div>
        </div>
    </section>
    <div class="container">
        <h1>Order Status</h1>
        <div class="order-list">
            <div class="order-item" onclick="openPopup('order1')">
                <img src="images/3.png" alt="Product 1">
                <div class="order-details">
                    <h2>Order 2</h2>
                    <p>Status: In Progress</p>
                </div>
            </div>
            <div class="order-item" onclick="openPopup('order2')">
                <img src="images/4.png" alt="Product 2">
                <div class="order-details">
                    <h2>Order1</h2>
                    <p>Status: Shipped</p>
                </div>
            </div>
            <!-- Tambahkan lebih banyak order-item sesuai kebutuhan -->
        </div>
    </div>

    <!-- Pop-up Detail Order -->
    <div id="order1" class="popup">
        <div class="popup-content">
            <span class="close" onclick="closePopup('order1')">&times;</span>
            <h2>Order 2</h2>
            <img src="images/3.png" alt="Product 1" class="popup-img">
            <p>Product: Putri Salju</p>
            <p>Quantity: 2</p>
            <p>Status: in Process</p>
            <p>Expected Delivery: 3 days</p>
        </div>
    </div>

    <div id="order2" class="popup">
        <div class="popup-content">
            <span class="close" onclick="closePopup('order2')">&times;</span>
            <h2>Order 2</h2>
            <img src="images/4.png" alt="Product 2" class="popup-img">
            <p>Product: Kastengel</p>
            <p>Quantity: 1</p>
            <p>Status: Complete</p>
            <p>Expected Delivery: 5 days</p>
        </div>
    </div>

    <script src="script.js"></script>
</body>
</html>

<?php
} else {
    echo "Data user tidak ditemukan.";
}

// Tutup koneksi
$conn->close();
?>
