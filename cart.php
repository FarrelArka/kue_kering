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

// Mengambil data pengguna berdasarkan ID
$user_query = "SELECT foto FROM users WHERE user_id=?";
$stmt = $conn->prepare($user_query);
$stmt->bind_param("i", $id);
$stmt->execute();
$user_result = $stmt->get_result();
$user_data = $user_result->fetch_assoc();

// Mengambil data keranjang belanja
$cart_query = "SELECT cart.product_id, cart.quantity, products.name, products.image, products.price 
              FROM cart 
              JOIN products ON cart.product_id = products.product_id
              WHERE cart.user_id = ?";
$stmt = $conn->prepare($cart_query);
$stmt->bind_param("i", $id);
$stmt->execute();
$cart_result = $stmt->get_result();
$cart_data = $cart_result->fetch_all(MYSQLI_ASSOC);

// Mengambil data poin pengguna
$poin_query = "SELECT point FROM point WHERE user_id=?";
$stmt = $conn->prepare($poin_query);
$stmt->bind_param("i", $id);
$stmt->execute();
$poin_result = $stmt->get_result();
$poin_data = $poin_result->fetch_assoc();

$poin = $poin_data['point'] ?? 0; // Jika null, setel poin menjadi 0

$total = 0;
foreach ($cart_data as $item) {
    $item_total = $item['quantity'] * $item['price'];
    $total += $item_total;
}

// Menghitung diskon langsung dengan nilai poin
$discount = min($total, $poin);
$remaining_poin = max(0, $poin - $total);
$total_final = $total - $discount;

// Cek apakah ada pesan di sesi
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']); // Hapus pesan dari sesi setelah diambil
} else {
    $message = '';
}

// Cek apakah ada Snap Token di sesi
if (isset($_SESSION['snapToken'])) {
    $snapToken = $_SESSION['snapToken'];
    unset($_SESSION['snapToken']); // Hapus Snap Token dari sesi setelah diambil
} else {
    $snapToken = '';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Croquant Cookies</title>
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
        /* Modal Background */
        .modal {
            display: none; /* Hidden by default */
            position: fixed; /* Stay in place */
            z-index: 1; /* Sit on top */
            left: 0;
            top: 0;
            width: 100%; /* Full width */
            height: 100%; /* Full height */
            overflow: auto; /* Enable scroll if needed */
            background-color: rgb(0,0,0); /* Fallback color */
            background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
        }

        /* Modal Content */
        .modal-content {
            background-color: #fefefe;
            margin: 15% auto; /* 15% from the top and centered */
            padding: 20px;
            border: 1px solid #888;
            width: 80%; /* Could be more or less, depending on screen size */
        }

        /* Close Button */
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />

    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="cart.css">

    <!-- Midtrans Snap.js -->
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="YOUR_CLIENT_KEY"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
            <a href="cart.php" active>Keranjang</a>
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
                <img src="<?php echo htmlspecialchars($user_data['foto']); ?>" alt="Profile Picture" width="50" height="50" id="profileImage">
                <div class="dropdown-content">
                    <a href="profile_user.php">Profil</a>
                    <a href="logout.php">Logout</a>
                </div>
            </div>
        </div>
    </header>

    <div class="container">
        <div class="cart">
            <div class="cart-header">
                <h2>Keranjang Anda</h2>
                <span>Jumlah Item: <?php echo count($cart_data); ?></span>
            </div>
            <?php foreach ($cart_data as $item): ?>
                <div class="cart-item">
                    <div class="item-info">
                        <img src="images/<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                        <div class="item-details">
                            <h3><?php echo htmlspecialchars($item['name']); ?></h3>
                        </div>
                    </div>
                    <div class="item-quantity">
                        <form method="POST" action="update_quantity.php">
                            <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($item['product_id']); ?>">
                            <input type="number" name="quantity" value="<?php echo htmlspecialchars($item['quantity']); ?>" min="1">
                            <button type="submit" name="update_quantity">Update</button>
                        </form>
                    </div>
                    <div class="item-price">Rp. <?php echo number_format($item['price'], 0, ',', '.'); ?></div>
                </div>
            <?php endforeach; ?>
            <div class="cart-total">
                <h3>Total Harga: Rp. <?php echo number_format($total, 0, ',', '.'); ?></h3>
            </div>
            <!-- Tombol di luar pop-up -->
            <div class="cart-actions">
                <button class="btn-outside" onclick="window.location.href='product.php'">Kembali Berbelanja</button>
                <!-- Button trigger modal -->
                <button type="button" class="btn-outside" id="checkoutButton">
                    Checkout
                </button>
            </div>
<div id="checkoutModal" class="modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h5>Pilih Metode Pembayaran</h5>
                    <span class="close">&times;</span>
                </div>
                <div class="modal-body">
                    <form method="POST" action="checkout.php">
                        <div class="payment-options">
                            <div class="payment-method">
                                <label>
                                    <input type="radio" name="payment_method" value="cod" required>
                                    <span style="font-weight: bold;">Cash on Delivery (COD)</span>
                                </label>
                            </div>
                            <div class="payment-method">
                                <label>
                                    <input type="radio" name="payment_method" value="transfer" required>
                                    <span style="font-weight: bold;">E-Wallet</span>
                                </label>
                               
                            </div>
                        </div>
                        <div class="discount-options">
                            <h6>Gunakan Diskon Poin Anda</h6>
                            <p>Poin Anda: <?php echo htmlspecialchars($poin); ?></p>
                            <label><input type="checkbox" name="use_points" id="use_points" value="yes"> <span style="font-weight: bold;">Gunakan poin untuk diskon</span></label>
                        </div>
                        <div class="modal-footer">
                            <p>Total Bayar: Rp. <span id="totalBayar"><?php echo number_format($total, 0, ',', '.'); ?></span></p>
                            <button type="submit" name="proceed_payment" class="btn-popup">Lanjutkan Pembayaran</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        </div>
    </div>
    

    <?php if ($message): ?>
        <script>
            $(document).ready(function() {
                alert("<?php echo $message; ?>"); // Atau gunakan modal Bootstrap untuk pesan lebih bagus
            });
        </script>
    <?php endif; ?>

    <?php if ($snapToken): ?>
        <script>
            $(document).ready(function() {
                snap.pay('<?php echo $snapToken; ?>', {
                    // Optional
                    onSuccess: function(result) {
                        alert("Pembayaran berhasil!");
                        // Lakukan sesuatu setelah pembayaran berhasil
                    },
                    onPending: function(result) {
                        alert("Menunggu pembayaran!");
                        // Lakukan sesuatu setelah pembayaran tertunda
                    },
                    onError: function(result) {
                        alert("Pembayaran gagal!");
                        // Lakukan sesuatu setelah pembayaran gagal
                    },
                    onClose: function() {
                        alert('Anda menutup pop-up tanpa menyelesaikan pembayaran');
                        // Lakukan sesuatu jika pengguna menutup pop-up
                    }
                });
            });
        </script>
    <?php endif; ?>

    <script>
        // Tampilkan atau sembunyikan opsi transfer
        document.querySelector('input[name="payment_method"][value="transfer"]').addEventListener('change', function() {
            document.getElementById('transferOptions').style.display = 'block';
        });

        document.querySelector('input[name="payment_method"][value="cod"]').addEventListener('change', function() {
            document.getElementById('transferOptions').style.display = 'none';
        });

        // Modal logic
        var modal = document.getElementById("checkoutModal");
        var btn = document.getElementById("checkoutButton");
        var span = document.getElementsByClassName("close")[0];

        btn.onclick = function() {
            modal.style.display = "block";
        }

        span.onclick = function() {
            modal.style.display = "none";
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
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
