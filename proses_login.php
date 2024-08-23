<?php
 // Mulai sesi
if (isset($_POST['submit'])) {

    include "koneksi.php";
    session_start();
    // Ambil data dari formulir
    $username = $_POST['username'];
    $password = $_POST['password']; // Password yang dikirimkan oleh pengguna
// Konfirmasi password tidak diperlukan untuk login
// $confirm_password = $_POST['confirm-password']; // Hapus baris ini jika tidak digunakan

    // Query untuk mencari pengguna dengan username yang sesuai
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Cek apakah pengguna ditemukan
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verifikasi password
        if (password_verify($password, $user['password'])) {
            // Password benar, set session dan redirect
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            header("Location: user.php"); // Ganti dengan halaman tujuan setelah login
            exit();
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "No user found with that username.";
    }
}
// Tutup koneksi
$conn->close();
?>