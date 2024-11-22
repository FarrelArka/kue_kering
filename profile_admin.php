<?php
session_start();
include 'koneksi.php'; // Koneksi ke database

// Pastikan pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Ambil data user dari database
$query = "SELECT * FROM users WHERE user_id = $user_id";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);

// Proses pembaruan profil
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['change_password'])) {
        // Handle password change
        $current_password = mysqli_real_escape_string($conn, $_POST['current_password']);
        $new_password = mysqli_real_escape_string($conn, $_POST['new_password']);
        $repeat_new_password = mysqli_real_escape_string($conn, $_POST['repeat_new_password']);

        // Check if current password matches the one in the database
        if (password_verify($current_password, $user['password'])) {
            // Check if new passwords match
            if ($new_password === $repeat_new_password) {
                // Hash the new password
                $new_password_hashed = password_hash($new_password, PASSWORD_BCRYPT);

                // Update the password in the database
                $update_password_query = "UPDATE users SET password = '$new_password_hashed' WHERE user_id = $user_id";
                if (mysqli_query($conn, $update_password_query)) {
                    $_SESSION['message'] = "Password changed successfully!";
                    header('Location: profile_admin.php');
                    exit;
                } else {
                    echo "Error updating record: " . mysqli_error($conn);
                }
            } else {
                echo "New passwords do not match!";
            }
        } else {
            echo "Current password is incorrect!";
        }
    }
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
            Account settings
        </h4>
        <div class="card overflow-hidden">
            <div class="row no-gutters row-bordered row-border-light">
                <div class="col-md-3 pt-0">
                    <div class="list-group list-group-flush account-settings-links">
                        <a class="list-group-item list-group-item-action active" data-toggle="list" href="#account-general">General</a>
                        <a class="list-group-item list-group-item-action" data-toggle="list" href="#account-change-password">Change password</a>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="tab-content">
                        <div class="tab-pane fade active show" id="account-general">
                            <form action="update_profile_admin.php" method="post" enctype="multipart/form-data">
                                <div class="card-body media align-items-center">
                                    <?php if ($user['foto']): ?>
                                        <img src="<?= htmlspecialchars($user['foto']) ?>" alt="Foto Profil" class="img-thumbnail mt-2" width="150">
                                    <?php endif; ?>
                                    <div class="media-body ml-4">
                                        <label class="btn btn-outline-dark">
                                            Upload new photo
                                            <input type="file" class="account-settings-fileinput" id="foto" name="foto">
                                        </label> &nbsp;
                                        <button type="button" class="btn btn-custom-cancel">Reset</button>
                                        <div class="text-dark small mt-1">Allowed JPG, GIF or PNG. Max size of 800K</div>
                                    </div>
                                </div>
                                <hr class="border-light m-0">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label class="form-label">Username</label>
                                        <input type="text" class="form-control mb-1" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">E-mail</label>
                                        <input type="text" class="form-control mb-1" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Alamat</label>
                                        <input type="text" class="form-control mb-1" name="alamat" value="<?= htmlspecialchars($user['alamat']) ?>" required>
                                    </div>
                                    <h6 class="mb-4">Contacts</h6>
                                    <div class="form-group">
                                        <label class="form-label">Phone</label>
                                        <input type="text" class="form-control" name="no_hp" value="<?= htmlspecialchars($user['no_hp']) ?>" required>
                                    </div>
                                    <input type="hidden" name="foto_lama" value="<?= htmlspecialchars($user['foto']) ?>">
                                    <div class="text-right mt-3">
                                        <button type="submit" class="btn btn-custom">Simpan Perubahan</button>&nbsp;
                                        <a href="admin.php" class="btn btn-custom-cancel">Kembali</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="tab-pane fade" id="account-change-password">
                            <form action="profile_admin.php" method="post">
                                <div class="card-body pb-2">
                                    <div class="form-group">
                                        <label class="form-label">Current password</label>
                                        <input type="password" class="form-control" name="current_password" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">New password</label>
                                        <input type="password" class="form-control" name="new_password" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Repeat new password</label>
                                        <input type="password" class="form-control" name="repeat_new_password" required>
                                    </div>
                                    <div class="text-right mt-3">
                                        <button type="submit" name="change_password" class="btn btn-custom">Ganti Password</button>&nbsp;
                                        <a href="admin.php" class="btn btn-custom-cancel">Kembali</a>
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
