<?php
session_start(); // Memulai session

// Cek apakah pengguna sudah login dan memiliki role admin
if (isset($_SESSION['username']) && $_SESSION['role'] == 'admin') {
    $name = $_SESSION['username'];
    $role = $_SESSION['role'];
} else {
    // Jika pengguna belum login atau bukan admin, redirect ke halaman login
    header("Location: login.php");
    exit;
}

// Koneksi ke database
include "koneksi.php";
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user = null; // Inisialisasi variabel $user

if (isset($_GET['user_id'])) {
    $userId = $_GET['user_id'];

    // Query untuk mengambil data pengguna yang akan diedit
    $sql = "SELECT user_id, username, email, alamat, no_hp, role FROM users WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
    } else {
        echo "Pengguna tidak ditemukan.";
        exit; // Hentikan eksekusi jika pengguna tidak ditemukan
    }
    
    $stmt->close();
} else {
    echo "ID pengguna tidak diberikan.";
    exit; // Hentikan eksekusi jika user_id tidak ada
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Admin</title>
    <link rel="stylesheet" href="profile.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 20px;
        }
        .btn-custom {
            background-color: #000;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .btn-custom:hover {
            background-color: #808080;
            color: #fff;
        }
        .btn-custom-file {
            outline: 10px;
            border-color: #000;
            color: #000000;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .btn-custom-file:hover {
            background-color: #808080;
            color: #fff;
        }
        .btn-custom:focus {
            outline: none;
        }
        .btn-custom-cancel {
            background-color: transparent;
            color: #000;
        }
        .btn-custom-cancel:hover {
            color: #808080;
        }
    </style>
</head>
<body>
    <div class="container light-style flex-grow-1 container-p-y">
        <h4 class="font-weight-bold py-3 mb-4">
            Edit Profil Pengguna
        </h4>
        <div class="card overflow-hidden">
            <div class="row no-gutters row-bordered row-border-light">
                <div class="col-md-3 pt-0">
                    <div class="list-group list-group-flush account-settings-links">
                        <a class="list-group-item list-group-item-action active" data-toggle="list" href="#account-general">Utama</a>
                        
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="tab-content">
                        <div class="tab-pane fade active show" id="account-general">
                            <form action="proses_edit_user.php" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="user_id" value="<?= htmlspecialchars($user['user_id']) ?>">
                                <hr class="border-light m-0">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label class="form-label">Username</label>
                                        <input type="text" class="form-control mb-1" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">E-mail</label>
                                        <input type="text" class="form-control mb-1" name="email" value="<?= htmlspecialchars($user['email']) ?>" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Alamat</label>
                                        <input type="text" class="form-control mb-1" name="alamat" value="<?= htmlspecialchars($user['alamat']) ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Phone</label>
                                        <input type="text" class="form-control" name="no_hp" value="<?= htmlspecialchars($user['no_hp']) ?>" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Role</label>
                                        <select class="form-control mb-1" id="role" name="role" required>
                                            <option value="admin" <?= $user['role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
                                            <option value="user" <?= $user['role'] == 'user' ? 'selected' : '' ?>>User</option>
                                        </select>
                                    </div>
                                    <div class="text-right mt-3">
                                        <button type="submit" class="btn btn-custom">Save changes</button>&nbsp;
                                        <a href="admin.php" class="btn btn-custom-cancel">Cancel</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                        
                        <div class="tab-pane fade" id="account-info">
                            <div class="card-body pb-2">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-DzZdmsXrAwzP6M7zAd0IazKlPPLjHD4iF4x7jLME6tkeRJfFZQGK2vM9G5tBR1p" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>
</html>
