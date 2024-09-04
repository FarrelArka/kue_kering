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
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Croquant Cookies</title>

    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />

    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="product_user.css">
</head>
<body>

    <!-- Header Section -->
    <nav class="header">
        <div class="logoContent">
            <a href="#" class="logo"><img src="images/logo kita.png" alt="Logo"></a>
            <h1 class="logoName">Croquant Cookies</h1>
        </div>

        <div class="navbar">
        <a href="user.php">Beranda</a>
            <a href="product.php">Produk</a>
            <a href="cart.php">Keranjang</a>
            <a href="order.php">Pesanan</a>
            <a href="#contact">Hubungi</a>
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



   <section class="product" id="product">
    <h1>Produk</h1>
            <br><br>
            <div class="swiper product-row">
                <div class="swiper-wrapper">
                <?php
                $sql = "SELECT * FROM products";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        if (!isset($row["product_id"])) {
                            echo "Error: Product ID not found.";
                            continue;
                        }
                        echo '<div class="swiper-slide box">';
                        echo '    <div class="img">';
                        echo '        <img src="images/' . htmlspecialchars($row["image"]) . '" alt="' . htmlspecialchars($row["name"]) . '">';
                        echo '    </div>';
                        echo '    <div class="product-content">';
                        echo '        <h3>' . htmlspecialchars($row["name"]) . '</h3>';
                        echo '        <p>' . htmlspecialchars($row["description"]) . '</p>';
                        echo '        <div class="orderNow">';
                        echo '            <form method="POST" action="process_addcart.php">';
                        echo '                <input type="hidden" name="product_id" value="' . htmlspecialchars($row["product_id"]) . '">';
                        echo '                <button type="submit" name="add_to_cart">Pesan Sekarang</button>';
                        echo '            </form>';
                        echo '        </div>';
                        echo '    </div>';
                        echo '</div>';
                    }
                } else {
                    echo "Tidak ada produk yang ditemukan";
                }
                ?>

                </div>
                <div class="swiper-pagination"></div>
            </div>
</section>
    <!-- Footer Section -->
    <footer class="footer" id="contact">
        <div class="box-container">
            <div class="mainBox">
                <div class="content">
                    <a href="#">
                        <img src="images/logo kita.png" alt="Logo">
                    </a>
                    <h1 class="logoName">Croquant Cookies</h1>
                </div>
                <p>Kue kering lezat untuk semua. Nikmati kelezatan dalam setiap gigitan, sempurna untuk momen istimewa.</p>
            </div>
            <div class="box">
                <h3>Quick Links</h3>
                <a href="#"> <i class="fas fa-arrow-right"></i>Home</a>
                <a href="#"> <i class="fas fa-arrow-right"></i>Product</a>
                <a href="#"> <i class="fas fa-arrow-right"></i>Blogs</a>
                <a href="#"> <i class="fas fa-arrow-right"></i>Review</a>
                <a href="#"> <i class="fas fa-arrow-right"></i>Contact</a>
            </div>
            <div class="box">
                <h3>Extra Links</h3>
                <a href="#"> <i class="fas fa-arrow-right"></i>Account Info</a>
                <a href="#"> <i class="fas fa-arrow-right"></i>Order Item</a>
                <a href="#"> <i class="fas fa-arrow-right"></i>Privacy Policy</a>
                <a href="#"> <i class="fas fa-arrow-right"></i>Payment Method</a>
                <a href="#"> <i class="fas fa-arrow-right"></i>Our Services</a>
            </div>
            <div class="box">
                <h3>Contact Info</h3>
                <a href="#"> <i class="fas fa-phone"></i>+91 12222 34444</a>
                <a href="#"> <i class="fas fa-envelope"></i>dannydesigner@gmail.com</a>
            </div>
        </div>
        <div class="share">
            <a href="#" class="fab fa-facebook-f"></a>
            <a href="#" class="fab fa-twitter"></a>
            <a href="#" class="fab fa-instagram"></a>
            <a href="#" class="fab fa-linkedin"></a>
            <a href="#" class="fab fa-pinterest"></a>
        </div>
        <div class="credit">
            Created by <span>Mr.Danny Designer</span> | All rights reserved!
        </div>
    </footer>

    <!-- Swiper JS -->
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>

    <!-- Custom JS -->
    <script src="index.js"></script>
</body>
</html>

<?php
} else {
    echo "Data user tidak ditemukan.";
}

// Tutup koneksi
$conn->close();
?>