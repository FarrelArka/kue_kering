<?php
session_start(); // Memulai session

// Cek apakah pengguna sudah login dan memiliki role admin
if (isset($_SESSION['username']) && $_SESSION['role'] == 'admin') {
    // Koneksi ke database
    include "koneksi.php";
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Cek apakah form di-submit
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Ambil data dari form
        $userId = intval($_POST['user_id']);
        $username = htmlspecialchars($_POST['username']);
        $email = htmlspecialchars($_POST['email']);
        $alamat = htmlspecialchars($_POST['alamat']);
        $no_hp = htmlspecialchars($_POST['no_hp']);
        $role = htmlspecialchars($_POST['role']);

        // Query untuk update data pengguna
        $sql = "UPDATE users SET username = ?, email = ?, alamat = ?, no_hp = ?, role = ? WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssi", $username, $email, $alamat, $no_hp, $role, $userId);

        // Eksekusi query dan cek hasilnya
        if ($stmt->execute()) {
            echo "Data pengguna berhasil diperbarui.";
            header("Location: admin.php"); // Redirect ke halaman admin setelah berhasil
            exit;
        } else {
            echo "Gagal memperbarui data: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Form tidak di-submit dengan benar.";
    }

    $conn->close();
} else {
    // Jika pengguna belum login atau bukan admin, redirect ke halaman login
    header("Location: login.php");
    exit;
}
?>
