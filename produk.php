<?php
session_start(); // Memulai session

// Cek apakah pengguna sudah login
if (isset($_SESSION['username']) && isset($_SESSION['role'])) {
    $name = $_SESSION['username'];
    $role = $_SESSION['role'];
} else {
    // Jika pengguna belum login, redirect ke halaman login atau tampilkan pesan error
    header("Location: login.php");
    exit;
}

// Koneksi ke database
include "koneksi.php";

// Query untuk mengambil data pengguna yang sedang login
$sql = "SELECT user_id, username, email, alamat, no_hp, foto, role FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $name);
$stmt->execute();
$result = $stmt->get_result();

// Ambil data pengguna
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
} else {
    echo "Pengguna tidak ditemukan";
    exit;
}

// Query untuk mengambil data dari tabel produk
$sql = "SELECT product_id, name, description, price, stock, image FROM products";
$resultProduk = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Produk - Croquant Cookies</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />

    <!-- Customized Bootstrap Stylesheet -->
    <link href="ADMIN 12/css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="ADMIN 12/css/styles.css" rel="stylesheet">
    
</head>

<body>
    <div class="container-xxl position-relative bg-white d-flex p-0">
        <!-- Sidebar Start -->
        <div class="sidebar pe-4 pb-3">
            <nav class="navbar bg-light navbar-light">
                <a href="index.php" class="navbar-brand mx-4 mb-3">
                    <h3>Croquant Cookies</h3>
                </a>
                <div class="d-flex align-items-center ms-4 mb-4">
                    <div class="position-relative">
                    <img class="rounded-circle me-lg-2" src="<?php echo htmlspecialchars($row['foto']); ?>" alt="Profile Picture" style="width: 40px; height: 40px;">
                        <div class="bg-success rounded-circle border border-2 border-white position-absolute end-0 bottom-0 p-1"></div>
                    </div>
                    <div class="ms-3">
                        <h6 class="mb-0"><?php echo htmlspecialchars($name); ?></h6>
                        <span><?php echo htmlspecialchars($role); ?></span>
                    </div>
                </div>
                <div class="navbar-nav w-100">
                    <a href="admin.php" class="nav-item nav-link"><i class="fa fa-tachometer-alt me-2"></i>Dashboard</a>
                    <a href="produk.php" class="nav-item nav-link active"><i class="fa fa-table me-2"></i>Produk</a>
                    <a href="manage_order.php" class="nav-item nav-link"><i class="fa fa-edit me-2"></i>Order</a>
                    <a href="manage_points.php" class="nav-item nav-link"><i class="fa fa-star me-2"></i>Points</a>
                </div>
            </nav>
        </div>
        <!-- Sidebar End -->

        <!-- Content Start -->
        <div class="content">
            <!-- Navbar Start -->
            <nav class="navbar navbar-expand bg-light navbar-light sticky-top px-4 py-0">
                <a href="#" class="sidebar-toggler flex-shrink-0">
                    <i class="fa fa-bars"></i>
                </a>
                <div class="navbar-nav align-items-center ms-auto">
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                        <img class="rounded-circle me-lg-2" src="<?php echo htmlspecialchars($row['foto']); ?>" alt="Profile Picture" style="width: 40px; height: 40px;">
                            <span class="d-none d-lg-inline-flex"><?php echo htmlspecialchars($name); ?></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end bg-light border-0 rounded-0 rounded-bottom m-0">
                            <a href="profile_admin.php" class="dropdown-item">My Profile</a>
                            <a href="#" class="dropdown-item">Settings</a>
                            <a href="logout.php" class="dropdown-item">Log Out</a>
                        </div>
                    </div>
                </div>
            </nav>
            <!-- Navbar End -->

            <!-- Produk Section Start -->
            <div class="container-fluid pt-4 px-4">
                <div class="bg-light text-center rounded p-4">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <h6 class="mb-0">Daftar Produk</h6>
                        <a class='btn btn-sm btn-primary' href='ADMIN 12/tambah_produk.php'>Tambah</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr class="text-dark">
                                    <th scope="col">Nama</th>
                                    <th scope="col">Deskripsi</th>
                                    <th scope="col">Harga</th>
                                    <th scope="col">Stok</th>
                                    <th scope="col">Gambar</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($resultProduk->num_rows > 0) {
                                    // Output data untuk setiap baris
                                    while($rowProduk = $resultProduk->fetch_assoc()) {
                                        echo "<tr>";
                                        echo "<td>" . $rowProduk["name"] . "</td>";
                                        echo "<td>" . $rowProduk["description"] . "</td>";
                                        echo "<td>Rp " . number_format($rowProduk["price"], 0, ',', '.') . "</td>";
                                        echo "<td>" . $rowProduk["stock"] . "</td>";
                                        echo "<td><img src='uploads/" . $rowProduk["image"] . "' alt='" . $rowProduk["name"] . "' style='width: 40px; height: 40px;'></td>";
                                        echo "<td>";
                                        echo "<a class='btn btn-sm btn-primary' href='ADMIN 12/edit_produk.php?id=" . $rowProduk["product_id"] . "'>Edit</a>";
                                        echo "<a class='btn btn-sm btn-danger' style='margin-top: 10px;' href='ADMIN 12/hapus_produk.php?id=" . $rowProduk["product_id"] . "' onclick=\"return confirm('Yakin ingin menghapus produk ini?');\">Hapus</a>";
                                        echo "</td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='7'>Tidak ada produk</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- Produk Section End -->

            <!-- Footer Start -->
            <div class="container-fluid pt-4 px-4">
                <div class="bg-light rounded-top p-4">
                    <div class="row">
                        <div class="col-12 col-sm-6 text-center text-sm-start">
                            &copy; <a href="#">Croquant Cookies</a>, All Right Reserved. 
                        </div>
                    </div>
                </div>
            </div>
            <!-- Footer End -->
        </div>
        <!-- Content End -->

        <!-- Back to Top -->
        <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/chart/chart.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="lib/tempusdominus/js/moment.min.js"></script>
    <script src="lib/tempusdominus/js/moment-timezone.min.js"></script>
    <script src="lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>

    <!-- Template Javascript -->
    <script src="ADMIN 12/js/main.js"></script>
</body>

</html>
