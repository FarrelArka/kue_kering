<?php
session_start();
include 'koneksi.php'; // Koneksi ke database

// Ambil data user dari session atau database
$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM users WHERE user_id = $user_id";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="ADMIN 12/css/bootstrap.min.css" rel="stylesheet">
    <link href="ADMIN 12/css/styles.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Edit Profile</h2>
    <form action="update_profile.php" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" id="username" name="username" value="<?= $user['username'] ?>" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="<?= $user['email'] ?>" required>
        </div>
        <div class="mb-3">
            <label for="no_hp" class="form-label">Nomor HP</label>
            <input type="text" class="form-control" id="no_hp" name="no_hp" value="<?= $user['no_hp'] ?>" required>
        </div>
        <div class="mb-3">
            <label for="alamat" class="form-label">Alamat</label>
            <input type="text" class="form-control" id="alamat" name="alamat" value="<?= $user['alamat'] ?>" required>
        </div>
        <div class="mb-3">
            <label for="foto" class="form-label">Foto Profil</label>
            <input type="file" class="form-control" id="foto" name="foto">
            <?php if ($user['foto']): ?>
                <img src="<?= $user['foto'] ?>" alt="Foto Profil" class="img-thumbnail mt-2" width="150">
            <?php endif; ?>
        </div>
        <button type="submit" class="btn btn-primary">Update Profile</button>
        <a href="profile_user.php" class="btn btn-secondary">Kembali ke Profil</a> <!-- Tombol Kembali -->
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
