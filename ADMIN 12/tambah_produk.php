<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Tambah Produk - Croquant Cookies</title>
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

    <!-- Template Stylesheet -->
    <link href="css/styles.css" rel="stylesheet">
    
    <style>
        body {
            background-color: #f8f9fa;
        }
        .card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .card-header {
            background-color: #343a40;
            color: #ffffff;
            border-radius: 1rem 1rem 0 0;
        }
        .form-control {
            border-radius: 0.5rem;
        }
        .form-group label {
            font-weight: bold;
        }
        .btn-primary {
            background-color: #010101;
            border: none;
            transition: background-color 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #f3f3f3;
            border:none;
            color:#010101;
        }
        .icon-upload {
            color: #6c757d;
            margin-right: 8px;
        }
        .form-control-file {
            border: 1px solid #ced4da;
            padding: 8px;
            border-radius: 0.5rem;
            transition: border-color 0.3s ease;
        }
        .form-control-file:hover {
            border-color: #343a40;
        }
        .text-center button {
            font-size: 1.2rem;
            padding: 10px 20px;
        }
        input:focus,
        textarea:focus,
        select:focus {
            outline: none !important; 
            box-shadow: none !important; 
            border-color: #ced4da !important; 
        }

        h3 {
            margin-top: .5rem !important;
        }

    </style>
</head>

<body>
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card">
                <div class="card-header text-center" style="background-color: #f8f9fa; color: #333;">
    <h3>Tambah Produk Baru</h3>
</div>

                    <div class="card-body p-4">
                        <form action="process_tambah_produk.php" method="POST" enctype="multipart/form-data">
                            <div class="form-group mb-3">
                                <label for="name">Nama Produk</label>
                                <input type="text" name="name" id="name" class="form-control" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="description">Deskripsi</label>
                                <textarea name="description" id="description" class="form-control" rows="4" required></textarea>
                            </div>
                            <div class="form-group mb-3">
                                <label for="price">Harga</label>
                                <input type="number" name="price" id="price" class="form-control" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="stock">Stok</label>
                                <input type="number" name="stock" id="stock" class="form-control" required>
                            </div>
                            <div class="form-group mb-4">
                                <label for="image">Gambar Produk</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-upload icon-upload"></i></span>
                                    <input type="file" name="image" id="image" class="form-control-file" required>
                                </div>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary">Tambah Produk</button>
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
