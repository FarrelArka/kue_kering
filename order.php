<?php
include "koneksi.php";
session_start(); // Start session

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "Anda harus login terlebih dahulu.";
    exit();
}

// Get user ID from session
$id = $_SESSION['user_id'];

// Fetch user data based on the logged-in user ID
$query = "SELECT * FROM users WHERE user_id=?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

// Check if user data was found
if ($data = $result->fetch_assoc()) {
    // Fetch orders from the database
    $query_orders = "SELECT t.transaction_id, t.status, p.image, p.name, dt.quantity, dt.total_bayar
                 FROM transaction t
                 JOIN detail_transaction dt ON t.transaction_id = dt.transaction_id
                 JOIN products p ON dt.product_id = p.product_id
                 WHERE t.user_id = ?";

    $stmt_orders = $conn->prepare($query_orders);
    $stmt_orders->bind_param("i", $id);
    $stmt_orders->execute();
    $orders_result = $stmt_orders->get_result();

    // Organize orders by transaction_id
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Status</title>
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
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
                <input type="search" placeholder="Search...">
            </div>
    
            <div class="profile">
                <img src="<?php echo htmlspecialchars($data['foto']); ?>" alt="Profile Picture" width="50" height="50" id="profileImage">
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

<script>
function openPopup(id) {
    document.getElementById(id).style.display = 'flex';
}

function closePopup(id) {
    document.getElementById(id).style.display = 'none';
}
</script>

</body>
</html>

<?php
} else {
    echo "Data user tidak ditemukan.";
}

// Close connection
$conn->close();
?>
