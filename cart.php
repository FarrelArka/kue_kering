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
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja</title>
    <link rel="stylesheet" href="troli.css">
    <style>
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
</head>
<body>

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
        <img src="<?php echo htmlspecialchars($user_data['foto']); ?>" alt="Profile Picture" width="50" height="50" id="profileImage">
    </div>
</nav>

<div class="container">
    <div class="cart">
        <div class="cart-header">
            <h2>Keranjang Anda</h2>
            <span>Jumlah Item: <?php echo count($cart_data); ?></span>
        </div>
        <?php
        foreach ($cart_data as $item) {
            $item_total = $item['quantity'] * $item['price'];
        ?>
        <div class="cart-item">
            <div class="item-info">
                <img src="images/<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                <div class="item-details">
                    <h3><?php echo htmlspecialchars($item['name']); ?></h3>
                    <p>Deskripsi singkat produk</p>
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
        <?php } ?>
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

        <!-- Modal -->
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
                                <div id="transferOptions" class="transfer-methods" style="display: none;">
                                    <label>
                                        <input type="radio" name="transfer_method" value="ovo">
                                        <img src="images/ovo.png" alt="OVO" class="ovo-logo">
                                    </label>
                                    <label>
                                        <input type="radio" name="transfer_method" value="gopay">
                                        <img src="images/gopay.png" alt="GoPay" class="gopay-logo">
                                    </label>
                                    <label>
                                        <input type="radio" name="transfer_method" value="qris">
                                        <img src="images/logo-qris.png" alt="QRIS" class="transfer-logo">
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="discount-options">
                            <h6>Gunakan Diskon Poin Anda</h6>
                            <p>Poin Anda: <?php echo htmlspecialchars($poin); ?></p>
                            <label><input type="checkbox" name="use_points" id="use_points" value="yes"> <span style="font-weight: bold;">Gunakan poin untuk diskon</span></label>
                        </div>
                        <div class="modal-footer">
                            <p>Total Bayar: Rp. <span id="totalBayar"><?php echo number_format($total, 0, ',', '.'); ?></span></p>
                            <button type="submit" name="checkout" class="btn-popup">Lanjutkan Pembayaran</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script>
            // Get the modal
            var modal = document.getElementById("checkoutModal");

            // Get the button that opens the modal
            var btn = document.getElementById("checkoutButton");

            // Get the <span> element that closes the modal
            var span = document.getElementsByClassName("close")[0];

            // When the user clicks the button, open the modal 
            btn.onclick = function() {
                modal.style.display = "block";
                updateTotal();
            }

            // When the user clicks on <span> (x), close the modal
            span.onclick = function() {
                modal.style.display = "none";
            }

            // When the user clicks anywhere outside of the modal, close it
            window.onclick = function(event) {
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            }

            // Display transfer options only when 'Transfer Bank' is selected
            document.querySelector('input[name="payment_method"][value="transfer"]').addEventListener('change', function() {
                document.getElementById('transferOptions').style.display = 'flex'; // Vertically stacked options
            });

            document.querySelector('input[name="payment_method"][value="cod"]').addEventListener('change', function() {
                document.getElementById('transferOptions').style.display = 'none';
            });

            // Function to update total payable based on points
            function updateTotal() {
                var total = <?php echo json_encode($total); ?>;
                var discount = <?php echo json_encode($discount); ?>;
                var usePoints = document.getElementById('use_points').checked;

                if (usePoints) {
                    total -= discount;
                    if (total < 0) total = 0;
                }

                document.getElementById("totalBayar").innerText = 'Rp. ' + total.toLocaleString();
            }

            // Update total when use_points checkbox is toggled
            document.getElementById('use_points').addEventListener('change', updateTotal);
        </script>

    </div>
</div>

</body>
</html>
