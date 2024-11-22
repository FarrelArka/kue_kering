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
    // Check if the form is for profile update or password change
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
                    header('Location: profile_user.php');
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
    } else {
        // Handle profile update
        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $no_hp = mysqli_real_escape_string($conn, $_POST['no_hp']);
        $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
        $foto_lama = mysqli_real_escape_string($conn, $_POST['foto_lama']);
        $foto = $foto_lama;

        // Handle file upload
        if ($_FILES['foto']['name']) {
            $target_dir = "uploads/";
            $target_file = $target_dir . basename($_FILES['foto']['name']);
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            $uploadOk = 1;

            // Check if image file is an actual image or fake image
            $check = getimagesize($_FILES['foto']['tmp_name']);
            if ($check !== false) {
                $uploadOk = 1;
            } else {
                echo "File is not an image.";
                $uploadOk = 0;
            }

            // Check file size
            if ($_FILES['foto']['size'] > 800000) {
                echo "Sorry, your file is too large.";
                $uploadOk = 0;
            }

            // Allow certain file formats
            if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
                echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                $uploadOk = 0;
            }

            // Check if $uploadOk is set to 0 by an error
            if ($uploadOk == 0) {
                echo "Sorry, your file was not uploaded.";
            // if everything is ok, try to upload file
            } else {
                if (move_uploaded_file($_FILES['foto']['tmp_name'], $target_file)) {
                    $foto = $target_file;
                } else {
                    echo "Sorry, there was an error uploading your file.";
                }
            }
        }

        // Update data user di database
        $update_query = "UPDATE users SET 
                            username = '$username', 
                            email = '$email', 
                            no_hp = '$no_hp', 
                            alamat = '$alamat', 
                            foto = '$foto' 
                         WHERE user_id = $user_id";
        if (mysqli_query($conn, $update_query)) {
            $_SESSION['message'] = "Profile updated successfully!";
            header('Location: profile_user.php');
            exit;
        } else {
            echo "Error updating record: " . mysqli_error($conn);
        }
    }
}
?>
