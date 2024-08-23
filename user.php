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
    <link rel="stylesheet" href="users.css">
</head>
<body>

    <!-- Header Section -->
    <header class="header">
        <div class="logoContent">
            <a href="#" class="logo"><img src="images/logo kita.png" alt="Logo"></a>
            <h1 class="logoName">Croquant Cookies</h1>
        </div>

        <nav class="navbar">
        <a href="user.php">Home</a>
            <a href="product.php">Product</a>
            <a href="cart.php">Cart</a>
            <a href="#">Order</a>
            <a href="#contact">Contact</a>
        </nav>

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
    </header>

    <!-- Home Section -->
    <section class="home" id="home">
        <div class="homeContent">
            <h2>Delicious Cookies for Everyone</h2>
            <p>Kue kering lezat untuk semua. Nikmati kelezatan dalam setiap gigitan, sempurna untuk momen istimewa.</p>
            <div class="home-btn">
                <a href="#"><button>See More</button></a>
            </div>
        </div>
    </section>

    <!-- Product Section -->
    <section class="product" id="product">
            <div class="heading">
                <h2>Our Exclusive Products</h2>
            </div>
            <div class="swiper product-row">
                <div class="swiper-wrapper">
                    <div class="swiper-slide box">
                        <div class="img">
                            <img src="images/6.png" alt>
                        </div>
                        <div class="product-content">
                            <h3>ChocoCookies</h3>
                            <p>Lorem ipsum dolor sit amet consectetur
                                adipisicing elit. Culpa adipisci reiciendis
                                assumenda.
                            </p>
                            <div class="orderNow">
                                <button>Order Now </button>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide box">
                        <div class="img">
                            <img src="images/4.png" alt>
                        </div>
                        <div class="product-content">
                            <h3>Kue Kastengel</h3>
                            <p>Lorem ipsum dolor sit amet consectetur
                                adipisicing elit. Culpa adipisci reiciendis
                                assumenda.
                            </p>
                            <div class="orderNow">
                                <button>Order Now </button>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide box">
                        <div class="img">
                            <img src="images/5.png" alt>
                        </div>
                        <div class="product-content">
                            <h3>Kue Lidah Kucing</h3>
                            <p>Lorem ipsum dolor sit amet consectetur
                                adipisicing elit. Culpa adipisci reiciendis
                                assumenda.
                            </p>
                            <div class="orderNow">
                                <button>Order Now </button>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide box">
                        <div class="img">
                            <img src="images/3.png" alt>
                        </div>
                        <div class="product-content">
                            <h3>Kue Putri Salju</h3>
                            <p>Lorem ipsum dolor sit amet consectetur
                                adipisicing elit. Culpa adipisci reiciendis
                                assumenda.
                            </p>
                            <div class="orderNow">
                                <button>Order Now </button>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="swiper-pagination"></div>
            </div>
            <div class="swiper product-row">
                <div class="swiper-wrapper">
                    <div class="swiper-slide box">
                        <div class="img">
                            <img src="images/3.png" alt>
                        </div>
                        <div class="product-content">
                            <h3>Kue Putri Salju</h3>
                            <p>Lorem ipsum dolor sit amet consectetur
                                adipisicing elit. Culpa adipisci reiciendis
                                assumenda.
                            </p>
                            <div class="orderNow">
                                <button>Order Now </button>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide box">
                        <div class="img">
                            <img src="images/4.png" alt>
                        </div>
                        <div class="product-content">
                            <h3>Kue Kastengel</h3>
                            <p>Lorem ipsum dolor sit amet consectetur
                                adipisicing elit. Culpa adipisci reiciendis
                                assumenda.
                            </p>
                            <div class="orderNow">
                                <button>Order Now </button>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide box">
                        <div class="img">
                            <img src="images/5.png" alt>
                        </div>
                        <div class="product-content">
                            <h3>Kue Lidah Kucing</h3>
                            <p>Lorem ipsum dolor sit amet consectetur
                                adipisicing elit. Culpa adipisci reiciendis
                                assumenda.
                            </p>
                            <div class="orderNow">
                                <button>Order Now </button>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide box">
                        <div class="img">
                            <img src="images/6.png" alt>
                        </div>
                        <div class="product-content">
                            <h3>ChocoCookies</h3>
                            <p>Lorem ipsum dolor sit amet consectetur
                                adipisicing elit. Culpa adipisci reiciendis
                                assumenda.
                            </p>
                            <div class="orderNow">
                                <button>Order Now </button>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="swiper-pagination"></div>
            </div>
        </section>
    <!-- About Section -->
    <section class="about">
        <h2>About</h2>
        <h3>Di sini, kami menghadirkan kue kering yang memanjakan lidah dengan kelezatan dan kualitas terbaik. Setiap produk kami dibuat dengan bahan berkualitas dan resep istimewa, sempurna untuk merayakan momen spesial atau sebagai camilan sehari-hari. Rasakan kebahagiaan dan kehangatan di setiap gigitan!</h3>
    </section>

    <!-- Footer Section -->
    <footer class="footer" id="contact">
        <div class="box-container">
            <div class="mainBox">
                <div class="content">
                    <a href="#">
                        <img src="images/logo kita.png" alt="Logo">
                    </a>
                    <h1 class="logoName">Sweet Cake</h1>
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
