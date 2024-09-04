<?php
// Koneksi ke database
include "koneksi.php";
// Ambil data produk berdasarkan ID
$product_id = $_GET['id'];
$sql = "SELECT * FROM products WHERE product_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Edit Produk - Croquant Cookies</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        /* Custom Styles */
        .card-header {
            background-color: #343a40;
            color: #ffffff;
        }
        .form-control {
            border-radius: 0.5rem;
        }
        .form-group label {
            font-weight: bold;
        }
        .btn-primary {
            background-color: #343a40;
            border-color: #343a40;
            border-radius: 0.5rem;
        }
        .btn-primary:hover {
            background-color: #495057;
            border-color: #495057;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header text-center">
                        <h3>Edit Produk</h3>
                    </div>
                    <div class="card-body">
                        <form action="proses_edit_produk.php" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                            <div class="form-group mb-3">
                                <label for="name">Nama Produk</label>
                                <input type="text" name="name" id="name" class="form-control" value="<?php echo $product['name']; ?>" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="description">Deskripsi</label>
                                <textarea name="description" id="description" class="form-control" rows="4" required><?php echo $product['description']; ?></textarea>
                            </div>
                            <div class="form-group mb-3">
                                <label for="price">Harga</label>
                                <input type="number" name="price" id="price" class="form-control" value="<?php echo $product['price']; ?>" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="stock">Stok</label>
                                <input type="number" name="stock" id="stock" class="form-control" value="<?php echo $product['stock']; ?>" required>
                            </div>
                            <div class="form-group mb-4">
                                <label for="image">Gambar Produk</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-upload"></i></span>
                                    <input type="file" name="image" id="image" class="form-control-file">
                                </div>
                                <img src="../uploads/<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" style="width: 100px; height: 100px; margin-top: 10px;">
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
