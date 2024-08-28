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

if (!empty($cart_data)) {
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja</title>
    <link rel="stylesheet" href="cart.css">
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
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
        $total = 0;
        foreach ($cart_data as $item) {
            $item_total = $item['quantity'] * $item['price'];
            $total += $item_total;
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
            Total: Rp. <?php echo number_format($total, 0, ',', '.'); ?>
        </div>
        <div class="cart-actions">
            <button class="btn btn-secondary" onclick="window.location.href='product.php'">Kembali Berbelanja</button>
            <form method="POST" action="checkout.php">
                <button class="btn" type="submit" name="checkout">Checkout</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>

<?php
} else {
    echo "Cart kosong.";
}

// Tutup koneksi
$conn->close();
?>
