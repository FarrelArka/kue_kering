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
    <style>
                .profile {
            position: relative;
            display: inline-block;
        }

        .profile img {
            cursor: pointer;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            right: 0;
            background-color: #f9f9f9;
            min-width: 150px;
            border-radius: 10px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
        }

        .dropdown-content a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            border-radius: 10px;    
            font-size: 15px;
            display: block;
        }

        .dropdown-content a:hover {
            background-color: #f1f1f1;
            border-radius: 10px;
        }

        .profile:hover .dropdown-content {
            display: block;
        }
    </style>
    <!-- Custom CSS -->
    <link rel="stylesheet" href="beli.css">
</head>
<body>

    <!-- Header Section -->
    <header class="header">
        <div class="logoContent">
            <a href="#" class="logo"><img src="images/logo kita.png" alt="Logo"></a>
            <h1 class="logoName">Croquant Cookies</h1>
        </div>

        <nav class="navbar">
            <a href="user.php">Beranda</a>
            <a href="product.php" active>Produk</a>
            <a href="cart.php">Keranjang</a>
            <a href="order.php">Pesanan</a>
            <a href="#contact">Hubungi</a>
        </nav>

        <div class="nav-right">
            <div class="icon">
                <i class="fas fa-search" id="search"></i>
                <i class="fas fa-bars" id="menu-bar"></i>
            </div>
            <div class="search">
                <form method="GET" action="product.php">
                    <input type="search" name="search_query" placeholder="Search...">
                    <button type="submit"><i class="fas fa-search"></i></button>
                </form>
            </div>
    
            <div class="profile">
                <img src="<?php echo htmlspecialchars($data['foto']); ?>" alt="Profile Picture" width="50" height="50" id="profileImage">
                <div class="dropdown-content">
                    <a href="profile_user.php">Profil</a>
                    <a href="logout.php">Logout</a>
                </div>
            </div>
        </div>
    </header>



   <section class="product" id="product">
    <h1>Produk</h1>
            <br><br>
            <div class="swiper product-row">
                <div class="swiper-wrapper">
                <?php
                // Periksa apakah ada query pencarian
                if (isset($_GET['search_query'])) {
                    $search_query = "%" . $_GET['search_query'] . "%";
                    $sql = "SELECT * FROM products WHERE name LIKE ? OR description LIKE ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("ss", $search_query, $search_query);
                    $stmt->execute();
                    $result = $stmt->get_result();
                } else {
                    $sql = "SELECT * FROM products";
                    $result = $conn->query($sql);
                }

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
                <h3>Link Cepat</h3>
                <a href="user.php"> <i class="fas fa-arrow-right"></i>Beranda</a>
                <a href="product.php"> <i class="fas fa-arrow-right"></i>Produk</a>
                <a href="#"> <i class="fas fa-arrow-right"></i>Blog</a>
                <a href="#"> <i class="fas fa-arrow-right"></i>Umpan Balik</a>
                <a href="#"> <i class="fas fa-arrow-right"></i>Kontak</a>
            </div>
            <div class="box">
                <h3>Link Ekstra</h3>
                <a href="#"> <i class="fas fa-arrow-right"></i>Info Akun</a>
                <a href="#"> <i class="fas fa-arrow-right"></i>Pesanan</a>
                <a href="#"> <i class="fas fa-arrow-right"></i>Metode Pembayaran</a>
            </div>
            <div class="box">
                    <h3>Contact Info</h3>
                    <a href="#"> <i class="fas fa-phone"></i>+62 896 0262 3481</a>
                    <a href="#"> <i
                            class="fas fa-envelope"></i>croquant.cookies00@gmail.com</a>

                </div>
        </div>
        <div class="credit">
            Created by <span>Farrel Arkana</span> | @CroquantCookies        
        </div>
    </footer>
    <!-- Swiper JS -->
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script>
        var swiper = new Swiper('.swiper', {
            slidesPerView: 3,
            spaceBetween: 20,
            loop: true,
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            breakpoints: {
                640: {
                    slidesPerView: 1,
                    spaceBetween: 20,
                },
                768: {
                    slidesPerView: 2,
                    spaceBetween: 40,
                },
                1024: {
                    slidesPerView: 3,
                    spaceBetween: 50,
                },
            },
        });

        // JavaScript untuk toggle dropdown
        document.addEventListener('DOMContentLoaded', function() {
            var profileImage = document.getElementById('profileImage');
            var dropdownContent = document.querySelector('.dropdown-content');

            profileImage.addEventListener('click', function(event) {
                dropdownContent.style.display = dropdownContent.style.display === 'block' ? 'none' : 'block';
            });

            // Menutup dropdown jika mengklik di luar area dropdown
            window.addEventListener('click', function(event) {
                if (!event.target.matches('#profileImage')) {
                    if (dropdownContent.style.display === 'block') {
                        dropdownContent.style.display = 'none';
                    }
                }
            });
        });
    </script>
    <!-- Custom JS -->
    <script src="index.js"></script>
    <script src="aktif.js"> </script>
</body>
</html>

<?php
} else {
    echo "Data user tidak ditemukan.";
}

// Tutup koneksi
$conn->close();
?>
