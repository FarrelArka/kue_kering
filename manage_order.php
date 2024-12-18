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

$sql = "SELECT transaction_id, user_id, transaction_date, payment_method, total_bayar, status, discount FROM transaction";
$result = $conn->query($sql);

// Cek jika koneksi berhasil
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
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
        <!-- Spinner Start -->
        
        <!-- Spinner End -->

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
                    <a href="admin.php" class="nav-item nav-link "><i class="fa fa-tachometer-alt me-2"></i>Dashboard</a>
                    <a href="produk.php" class="nav-item nav-link"><i class="fa fa-table me-2"></i>Produk</a>
                    <a href="manage_order.php" class="nav-item nav-link active"><i class="fa fa-edit me-2"></i>Order</a>
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
                            <a href="logout.php" class="dropdown-item">Log Out</a>
                        </div>
                    </div>
                </div>
            </nav>


            <!-- Manage Transactions Start -->
            <div class="container-fluid pt-4 px-4">
                <div class="bg-light text-center rounded p-4">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <h6 class="mb-0">Daftar Transaksi</h6>
                    </div>
                    <div class="table-responsive">
                        <table class="table text-start align-middle table-bordered table-hover mb-0">
                            <thead>
                                <tr class="text-dark">
                                    <th scope="col">ID Transaksi</th>
                                    <th scope="col">ID Pengguna</th>
                                    <th scope="col">Tanggal</th>
                                    <th scope="col">Metode Pembayaran</th>
                                    <th scope="col">Total Pembayaran</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Diskon</th>
                                    <th scope="col">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($result->num_rows > 0) {
                                    // Output data untuk setiap baris
                                    while($row = $result->fetch_assoc()) {
                                        echo "<tr>";
                                        echo "<td>".$row['transaction_id']."</td>";
                                        echo "<td>".$row['user_id']."</td>";
                                        echo "<td>".$row['transaction_date']."</td>";
                                        echo "<td>".$row['payment_method']."</td>";
                                        echo "<td>Rp".$row['total_bayar']."</td>";
                                        echo "<td>
                                                <form action='ADMIN 12/update_order.php' method='POST'>
                                                    <input type='hidden' name='transaction_id' value='".$row['transaction_id']."'>
                                                    <select class='form-select' name='status' onchange='this.form.submit()'>
                                                        <option value='pending' ".($row['status'] === 'Sudah Bayar' ? 'selected' : '').">Sudah Bayar</option>
                                                        <option value='completed' ".($row['status'] === 'Belum Bayar' ? 'selected' : '').">Belum Bayar</option>
                                                        <option value='cancelled' ".($row['status'] === 'Proses' ? 'selected' : '').">Proses</option>
                                                        <option value='cancelled' ".($row['status'] === 'Selesai' ? 'selected' : '').">Selesai</option>
                                                        <option value='cancelled' ".($row['status'] === 'Cancelled' ? 'selected' : '').">Cancelled</option>
                                                    </select>
                                                </form>
                                              </td>";
                                        echo "<td>".$row['discount']."</td>";
                                        echo "<td>
                                                <form action='ADMIN 12/delete_order.php' method='POST' onsubmit='return confirm(\"Are you sure you want to delete this transaction?\");'>
                                                    <input type='hidden' name='transaction_id' value='".$row['transaction_id']."'>
                                                    <button type='submit' class='btn btn-sm btn-primary'>Delete</button>
                                                </form>
                                              </td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='8' class='text-center'>Tidak ada data</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- Manage Transactions End -->

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
            <!-- Footer End -->
        </div>
        <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>
        <!-- Content End -->
    </div>

    <!-- JavaScript Libraries -->
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
    <script>
        // Fungsi untuk menghilangkan spinner saat halaman selesai dimuat
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('spinner').style.display = 'none';
        });
    </script>
</body>

</html>

<?php
$conn->close();
?>
