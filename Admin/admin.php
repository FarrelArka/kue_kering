<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Produk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</head>
<body>
<div class="row g-3 align-items-center">
    <form action="process_create.php" method="post" enctype="multipart/form-data">
        <div class="px-5">
            <div class="col-auto px-5 px-5">
                <input type="text" class="form-control" name="name" placeholder="Nama" required>
            </div>
        </div>
        <div class="col-auto px-5 px-5">
                <input type="text" class="form-control" name="desk" placeholder="deskripsi" required>
            </div>
        </div>
        <div class="col-auto">
            <input type="text" class="form-control" name="price" placeholder="Harga" required>
        </div>
        <div class="col-auto">
            <input type="text" class="form-control" name="stock" placeholder="Stok" required>
        </div>
        <div class="mb-3">
            <label for="formFile" class="form-label">Masukkan Gambar</label>
            <input class="form-control" type="file" id="formFile" name="image">
        </div>
        <div class="input-group mb-3">
            <button class="btn btn-outline-secondary" type="submit">Kirim</button>
        </div>
    </form>
</div>
</body>
</html>
