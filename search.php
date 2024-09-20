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

    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="search.css">
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
    
           <a href="profile_user.php"> <div class="profile">
                
                <img src="<?php echo htmlspecialchars($data['foto']); ?>" alt="Profile Picture" width="50" height="50" id="profileImage">
            </div></a>
        </div>
    </header>

    <section class="product" id="product">
    <h1>Produk</h1>
    <br><br>
    <div class="product-container">
        <?php
        // Memeriksa apakah ada query pencarian
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
                    echo "Error: Product ID tidak ditemukan.";
                    continue;
                }
                echo '<div class="card">';
                echo '    <div class="image-container">';
                echo '        <img src="images/' . htmlspecialchars($row["image"]) . '" alt="' . htmlspecialchars($row["name"]) . '">';
                echo '    </div>';
                echo '    <div class="content">';
                echo '        <h3>' . htmlspecialchars($row["name"]) . '</h3>';
                echo '        <p>' . htmlspecialchars($row["description"]) . '</p>';
                echo '        <form method="POST" action="process_addcart.php">';
                echo '            <input type="hidden" name="product_id" value="' . htmlspecialchars($row["product_id"]) . '">';
                echo '            <button type="submit" name="add_to_cart">Pesan Sekarang</button>';
                echo '        </form>';
                echo '    </div>';
                echo '</div>';
            }
        } else {
            echo "Tidak ada produk yang ditemukan";
        }
        ?>
    </div>
</section>


    <!-- Footer Section -->
    <footer class="footer">
        <!-- Footer content here -->
    </footer>

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
