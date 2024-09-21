<?php
session_start();
include 'koneksi.php'; // Include your database connection file

// Assuming user ID is stored in the session
$userId = $_SESSION['user_id'];

// Fetch user profile data from the database
$query = "SELECT username, email, no_hp, foto FROM users WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $userId);
$stmt->execute();
$stmt->bind_result($username, $email, $hp, $profilePicture);
$stmt->fetch();
$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="ADMIN 12/css/bootstrap.min.css" rel="stylesheet">
    <link href="ADMIN 12/css/styles.css" rel="stylesheet"> <!-- Template Stylesheet -->
    <style>
        .card {
            width: 350px; /* Kecilkan lebar card */
            margin: 0 auto; /* Untuk membuat card berada di tengah */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Tambahkan bayangan */
            border-radius: 15px; /* Sudut yang lebih halus */
            overflow: hidden; /* Pastikan elemen dalam kartu tidak keluar */
            background-color: #f8f9fa; /* Warna latar belakang yang lebih cerah */
        }
        .card-body {
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }   
        .info-row {
            display: flex;
            justify-content: space-between;
            width: 100%;
            margin-bottom: 10px;
        }
        .info-label {
            font-weight: bold;
        }
        .card-body img {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 50%; /* Membuat gambar profil berbentuk lingkaran */
            border: 5px solid #fff; /* Tambahkan border putih di sekitar gambar */
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* Bayangan di gambar */
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">User Profile</h2>
        <form action="user.php" method="post">
        <div class="card">
            <div class="card-body">
                <!-- Profile Picture -->
                <img src="<?php echo htmlspecialchars($profilePicture); ?>" alt="Profile Picture" class="rounded-circle mb-3">
                
                <h5 class="card-title text-center">Profile Information</h5>
                
                <div class="info-row">
                    <span class="info-label">Username:</span>
                    <span><?php echo htmlspecialchars($username); ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Nomer Hp:</span>
                    <span><?php echo htmlspecialchars($hp); ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Email:</span>
                    <span><?php echo htmlspecialchars($email); ?></span>
                </div>

                        
            <a href="user.php"class="btn btn-secondary mt-2">Back</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
