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

// Mengambil data pengguna berdasarkan ID pengguna yang login
$query = "SELECT * FROM users WHERE user_id=?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

// Cek apakah data pengguna ditemukan
if ($data = $result->fetch_assoc()) {
    // Mengambil data order dari database
    $query_orders = "SELECT t.transaction_id, t.status, p.image, p.name, dt.quantity, dt.total_bayar
                     FROM transaction t
                     JOIN detail_transaction dt ON t.transaction_id = dt.transaction_id
                     JOIN products p ON dt.product_id = p.product_id
                     WHERE t.user_id = ?";

    $stmt_orders = $conn->prepare($query_orders);
    $stmt_orders->bind_param("i", $id);
    $stmt_orders->execute();
    $orders_result = $stmt_orders->get_result();

    // Mengatur order berdasarkan transaction_id
    $orders = [];
    while ($row = $orders_result->fetch_assoc()) {
        $transaction_id = $row['transaction_id'];
        if (!isset($orders[$transaction_id])) {
            $orders[$transaction_id] = [
                'status' => $row['status'],
                'items' => []
            ];
        }
        $orders[$transaction_id]['items'][] = [
            'image' => $row['image'],
            'name' => $row['name'],
            'quantity' => $row['quantity'],
            'total_bayar' => $row['total_bayar']
        ];
    }
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
    <link rel="stylesheet" href="pesenan.css">
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
            <a href="product.php">Produk</a>
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
            <form action="search.php" method="GET">
                <input type="search" name="query" placeholder="Search..." required>
                <input type="hidden"><i class="fas fa-search"></i>
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
    
    <div class="container">
        <h1>Order Status</h1>
        <div class="order-list">
            <?php foreach ($orders as $transaction_id => $order) { ?>
            <div class="order-item" onclick="openPopup('<?php echo $transaction_id; ?>')">
                <h2>Order <?php echo htmlspecialchars($transaction_id); ?></h2>
                <p>Status: <?php echo htmlspecialchars($order['status']); ?></p>
                <div class="order-images">
                    <?php foreach ($order['items'] as $item) { ?>
                        <img src="images/<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" class="order-image">
                    <?php } ?>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>

    <!-- Pop-up Detail Order -->
    <?php foreach ($orders as $transaction_id => $order) { ?>
    <div id="<?php echo $transaction_id; ?>" class="popup">
        <div class="popup-content">
            <span class="close" onclick="closePopup('<?php echo $transaction_id; ?>')">&times;</span>
            <h2>Order <?php echo htmlspecialchars($transaction_id); ?></h2>
            <?php foreach ($order['items'] as $item) { ?>
            <div class="popup-item">
                <img src="images/<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" class="order-image">
                <div class="pop-up-anjay">
                    <p>Product: <?php echo htmlspecialchars($item['name']); ?></p>
                    <p>Quantity: <?php echo htmlspecialchars($item['quantity']); ?></p>
                    <p>Total Bayar: <?php echo htmlspecialchars($item['total_bayar']); ?></p>
                </div>
            </div>
            <?php } ?>
            <p>Status: <?php echo htmlspecialchars($order['status']); ?></p>
        </div>
    </div>


    <?php } ?>  
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
    <script>
    function openPopup(id) {
        document.getElementById(id).style.display = 'flex';
    }

    function closePopup(id) {
        document.getElementById(id).style.display = 'none';
    }
    </script>
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
    <script src="aktif.js"></script>
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
