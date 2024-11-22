<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Produk</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../produk.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

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
            border: none;
            color: #010101;
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
        .file-input::-webkit-file-upload-button {
    display: none;
}
.error-message {
            color: red;
            font-size: 0.9em;
            display: none;
        }
    </style>

</head>

<body>
    <div class="container">
        <h3 style="font-weight:bolder;">Tambah Produk</h3>
        <form action="process_tambah_produk.php" method="POST" enctype="multipart/form-data">
            <div class="form-grid">
                <div class="field full-width">
                    <label style="font-weight: bold;">Nama Produk</label>
                    <input type="text" name="name" id="name" required>
                </div>

                <div class="field ">
                    <label style="font-weight: bold;">Harga</label>
                    <input type="number" name="price" id="price" class="form-control" required>
                </div>


                <div class="field ">
                    <label style="font-weight: bold;" for="stock">Stok</label>
                    <input type="number" name="stock" id="stock" class="form-control" required>

                </div>

                <!-- <div class="field">
                    <label for="image">Gambar Produk</label>
                    <div class="input-group">
                        <label class="input-group-text"><i class="fas fa-upload icon-upload"></i></label>
                        <input type="file" name="image" id="image" class="form-control-file" required>
                    </div>
                </div> -->
                <div class="field full-width">
                    <label style="font-weight: bold;">Deskripsi</label>
                    <textarea name="description" id="description" class="form-control" rows="4" required></textarea>
                    <p class="error-message" id="error-message">Deskripsi tidak boleh lebih dari 30 kata.</p>
                </div>
                <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            const textarea = document.getElementById('description');
            const errorMessage = document.getElementById('error-message');

            textarea.addEventListener('input', () => {
                const words = textarea.value.split(/\s+/);
                if (words.length > 30) {
                    errorMessage.style.display = 'block';
                    const trimmedWords = words.slice(0, 150);
                    textarea.value = trimmedWords.join(' ');
                } else {
                    errorMessage.style.display = 'none';
                }   
            });
        });
    </script>
                <div class="field full-width">
                    <label style="font-weight: bold;" for="image">Gambar Produk</label>
                    <div class="input-group mb-3">
                        <label class="input-group-text" for="inputGroupFile01"><i class="fas fa-upload icon-upload"></i></label>
                        <input type="file" name="image" id="image"  class="form-control file-input">
                    </div>
                </div>

            </div>
            <div class= "d-flex justify-content-between mt-4">
                <button type="submit" name="submit" class="btn btn-dark" style="border-radius: 7px; font-weight: bolder; width: 372px;">Tambah Produk</button>
                <a href="../produk.php" class="btn btn-dark" style="border-radius: 7px; font-weight: bolder; width: 372px;">Cancel</a>
            </div>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>