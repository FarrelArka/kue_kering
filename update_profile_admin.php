<?php
session_start();
include 'koneksi.php'; // Koneksi ke database

// Pastikan pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Ambil data dari form
$username = $_POST['username'];
$email = $_POST['email'];
$no_hp = $_POST['no_hp'];
$alamat = $_POST['alamat'];
$foto_lama = $_POST['foto_lama'];

// Proses upload foto profil jika ada file yang diupload
if ($_FILES['foto']['name']) {
    $foto = 'uploads/' . basename($_FILES['foto']['name']);
    move_uploaded_file($_FILES['foto']['tmp_name'], $foto);
} else {
    $foto = $foto_lama;
}

// Update data pengguna di database
$query = "UPDATE users SET username = ?, email = ?, no_hp = ?, alamat = ?, foto = ? WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("sssssi", $username, $email, $no_hp, $alamat, $foto, $user_id);

if ($stmt->execute()) {
    // Update session data
    $_SESSION['username'] = $username;

    // Redirect ke halaman profil
    header('Location: profile_admin.php');
    exit();
} else {
    echo "Error updating record: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
