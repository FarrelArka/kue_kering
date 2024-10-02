<?php
session_start(); // Memulai session

// Cek apakah pengguna sudah login
if(isset($_SESSION['username']) && isset($_SESSION['role'])) {
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

// Query untuk menghitung total produk
$totalProdukSql = "SELECT COUNT(*) as total_produk FROM products";
$totalProdukResult = $conn->query($totalProdukSql);
$totalProduk = 0;
if ($totalProdukResult->num_rows > 0) {
    $totalProdukRow = $totalProdukResult->fetch_assoc();
    $totalProduk = $totalProdukRow['total_produk'];
}

// Query untuk menghitung produk dengan stok kosong
$stokKosongSql = "SELECT COUNT(*) as stok_kosong FROM products WHERE stock <= 0";
$stokKosongResult = $conn->query($stokKosongSql);
$stokKosong = 0;
if ($stokKosongResult->num_rows > 0) {
    $stokKosongRow = $stokKosongResult->fetch_assoc();
    $stokKosong = $stokKosongRow['stok_kosong'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Produk - Croquant Cookies</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <link href="img/favicon.ico" rel="icon">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="ADMIN 12/css/bootstrap.min.css" rel="stylesheet">
    <link href="ADMIN 12/css/styles.css" rel="stylesheet">
</head>
<body>
    <div class="container-xxl position-relative bg-white d-flex p-0">
        <!-- Sidebar Start -->
        <div class="sidebar pe-4 pb-3">
            <nav class="navbar bg-light navbar-light">
                <a href="index.php" class="navbar-brand mx-2 mb-3">
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
                    <a href="admin.php" class="nav-item nav-link active"><i class="fa fa-tachometer-alt me-2"></i>Dashboard</a>
                    <a href="produk.php" class="nav-item nav-link"><i class="fa fa-table me-2"></i>Produk</a>
                    <a href="manage_order.php" class="nav-item nav-link"><i class="fa fa-edit me-2"></i>Order</a>
                    <a href="manage_points.php" class="nav-item nav-link"><i class="fa fa-star me-2"></i>Points</a>
                </div>
            </nav>
        </div>
        <!-- Sidebar End -->

        <!-- Content Start -->
        <div class="content">
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
                            <a href="logout.php" class="dropdown-item">Log Out</a>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Sale & Revenue Start -->
            <div class="container-fluid pt-4 px-4">
                <div class="row g-4">
                    <div class="col-sm-6 col-xl-3">
                        <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                            <i class="fa fa-chart-bar fa-3x text-primary"></i>
                            <div class="ms-3">
                                <p class="mb-2">Stok Kosong</p>
                                <h6 class="mb-0"><?php echo $stokKosong; ?> Produk</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-3">
                        <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                            <i class="fa fa-chart-pie fa-3x text-primary"></i>
                            <div class="ms-3">
                                <p class="mb-2">Total Produk</p>
                                <h6 class="mb-0"><?php echo $totalProduk; ?> Produk</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Sale & Revenue End -->

            <!-- Recent Sales Start -->
            <div class="container-fluid pt-4 px-4">
                <div class="bg-light text-center rounded p-4">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <h6 class="mb-0">Recent Sales</h6>
                        <a href="">Show All</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table text-start align-middle table-bordered table-hover mb-0">
                            <thead>
                                <tr class="text-dark">
                                    <th scope="col"><input class="form-check-input" type="checkbox"></th>
                                    <th scope="col">User</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Alamat</th>
                                    <th scope="col">No Hp</th>
                                    <th scope="col">Role</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sql = "SELECT user_id, username, email, alamat, no_hp, role FROM users";
                                $result = $conn->query($sql);

                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        echo "<tr>";
                                        echo '<td><input class="form-check-input" type="checkbox"></td>';
                                        echo "<td>" . htmlspecialchars($row["username"]) . "</td>";
                                        echo "<td>" . htmlspecialchars($row["email"]) . "</td>";
                                        echo "<td>" . htmlspecialchars($row["alamat"]) . "</td>";
                                        echo "<td>" . htmlspecialchars($row["no_hp"]) . "</td>";
                                        echo "<td>" . htmlspecialchars($row["role"]) . "</td>";
                                        echo "<td><a class='btn btn-sm btn-primary' href='edit_user.php?id=" . htmlspecialchars($row["user_id"]) . "'>Edit</a> 
                                              <a class='btn btn-sm btn-danger' href='delete_user.php?id=" . htmlspecialchars($row["user_id"]) . "'>Delete</a></td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='7'>No results found</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- Recent Sales End -->
              <!-- Footer Start -->
            <div class="container-fluid pt-4 px-4">
                <div class="bg-light rounded-top p-4">
                    <div class="row">
                        <div class="col-12 col-sm-6 text-center text-sm-start">
                            &copy; <a href="#">Croquant Cookies</a>, All Right Reserved.
                        </div>
                        <div class="col-12 col-sm-6 text-center text-sm-end">
                            <!-- Credits removed -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Content End -->

        <!-- Back to Top -->
        <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="fa fa-angle-double-up"></i></a>
    </div>

    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="ADMIN 12/lib/chart/chart.min.js"></script>
    <script src="ADMIN 12/lib/easing/easing.min.js"></script>
    <script src="ADMIN 12/lib/waypoints/waypoints.min.js"></script>
    <script src="ADMIN 12/lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="ADMIN 12/lib/tempusdominus/js/moment.min.js"></script>
    <script src="ADMIN 12/lib/tempusdominus/js/moment-timezone.min.js"></script>
    <script src="ADMIN 12/lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>

    <!-- Template Javascript -->
    <script src="ADMIN 12/js/main.js"></script>
    <script>
        // Fungsi untuk menghilangkan spinner saat halaman selesai dimuat
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('spinner').style.display = 'none';
        });
    </script>
</body>
</html>
