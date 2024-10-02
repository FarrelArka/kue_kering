<?php
include "koneksi.php";
// Ambil data dari formulir
$username = $_POST['username'];
$email = $_POST['email'];
$address = $_POST['address'];
$phone = $_POST['phone'];
$password = $_POST['password'];
$confirm_password = $_POST['confirm-password'];

// Validasi password
if ($password !== $confirm_password) {
    echo "Passwords do not match.";
    exit();
}

// Enkripsi password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Tangani unggahan file
$profile_picture = $_FILES['profile-picture'];
$upload_dir = 'uploads/'; // Pastikan direktori ini ada dan dapat ditulis
$upload_file = $upload_dir . basename($profile_picture['name']);

// Validasi dan pindahkan file
if ($profile_picture['error'] == UPLOAD_ERR_OK) {
    if (move_uploaded_file($profile_picture['tmp_name'], $upload_file)) {
        echo "File is valid, and was successfully uploaded.\n";
    } else {
        echo "Possible file upload attack!\n";
        exit();
    }
} else {
    echo "File upload error: " . $profile_picture['error'];
    exit();
}

// Query untuk memasukkan data ke tabel users
$sql = "INSERT INTO users (username, email, alamat, no_hp, password, foto, role)
VALUES ('$username', '$email', '$address', '$phone', '$hashed_password', '$upload_file', 'user')";

if ($conn->query($sql) === TRUE) {
    // Ambil user_id dari pengguna yang baru saja dimasukkan
    $user_id = $conn->insert_id;

    // Masukkan user_id dan point = 0 ke tabel point
    $sql_point = "INSERT INTO point (user_id, point) VALUES ('$user_id', 0)";
    if ($conn->query($sql_point) === TRUE) {
        header("location:login.html");
    } else {
        echo "Error: " . $sql_point . "<br>" . $conn->error;
    }
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

// Tutup koneksi
$conn->close();
?>
