<?php
session_start();
include 'koneksi.php'; // Koneksi ke database

// Ambil data dari form
$user_id = $_SESSION['user_id'];
$username = $_POST['username'];
$email = $_POST['email'];
$no_hp = $_POST['no_hp'];
$alamat = $_POST['alamat'];

// Upload foto baru jika ada
if ($_FILES['foto']['name']) {
    $foto = 'uploads/' . basename($_FILES['foto']['name']);
    move_uploaded_file($_FILES['foto']['tmp_name'], $foto);
} else {
    $foto = $_POST['foto_lama'];
}

// Update data user di database
$query = "UPDATE users SET username = '$username', email = '$email', no_hp = '$no_hp', alamat = '$alamat', foto = '$foto' WHERE user_id = $user_id";
mysqli_query($conn, $query);

// Redirect ke halaman profil
header('Location: profile_user.php');
exit();
?>
