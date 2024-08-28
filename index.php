<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Croquant Cookies</title>

        <!-- swiper link  -->
        <link rel="stylesheet"
            href="https://unpkg.com/swiper/swiper-bundle.min.css" />

        <!-- cdn icon link  -->
        <link rel="stylesheet"
            href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
        <!-- custom css file  -->
        <link rel="stylesheet" href="index.css">

    </head>

    <body>

        <!-- header section start here  -->
        <header class="header">
            <div class="logoContent">
                <a href="#" class="logo"><img src="images/logo kita.png" alt></a>
                <h1 class="logoName">Croquant Cookies</h1>
            </div>

            <nav class="navbar">
                <a href="#home">home</a>
                <a href="#product">product</a>
                <a href="#contact">contact</a>
            </nav>

            <div class="icon">
                <i class="fas fa-search" id="search"></i>
                <i class="fas fa-bars" id="menu-bar"></i>
            </div>

            <div class="search">
                <input type="search" placeholder="search...">
            </div>
        </header>
        <!-- header section end here  -->

        <!-- home section start here  -->
        <section class="home" id="home">
            <div class="homeContent">
                <h2>Kue Enak Untuk Semua</h2>
                <p>Kue kering lezat untuk semua. Nikmati kelezatan dalam setiap gigitan, sempurna untuk momen istimewa.</p>
                <div class="home-btn">
                    <a href="register.html">Daftar</a>
                    <a href="login.html">Masuk</a>
                </div>
            </div>
        </section>

        <!-- home section end here  -->

        <!-- product section start here  -->
        <section class="product" id="product">
    <div class="heading">
        <h2>Our Exclusive Products</h2>
    </div>
    <div class="swiper product-row">
        <div class="swiper-wrapper">
            <?php
            include "koneksi.php";
           $sql = "SELECT * FROM products"; // Ganti dengan nama tabel produk kamu
           $result = $conn->query($sql);
           
           if ($result->num_rows > 0) {
               // Output data untuk setiap baris
               while($row = $result->fetch_assoc()) {
                   echo '<div class="swiper-slide box">';
                   echo '    <div class="img">';
                   echo '        <img src="images/' . $row["image"] . '" alt="' . $row["name"] . '">';
                   echo '    </div>';
                   echo '    <div class="product-content">';
                   echo '        <h3>' . $row["name"] . '</h3>';
                   echo '        <p>' . $row["description"] . '</p>';
                   echo '        <div class="orderNow">';
                   
                   echo '        </div>';
                   echo '    </div>';
                   echo '</div>';
               }
           } else {
               echo "Tidak ada produk yang ditemukan";
           }
           
           $conn->close();
           ?>
        </div>
        <div class="swiper-pagination"></div>
    </div>
</section>


        <!-- product section end here  -->
        <!-- newsletter section start here  -->

        <section class="about">
   <h2>about</h2>
            <h3>Di sini, kami menghadirkan kue kering yang memanjakan lidah dengan kelezatan dan kualitas terbaik. Setiap produk kami dibuat dengan bahan berkualitas dan resep istimewa, sempurna untuk merayakan momen spesial atau sebagai camilan sehari-hari. Rasakan kebahagiaan dan kehangatan di setiap gigitan!</h3>
        </section>
        <!-- newsletter section ends here  -->

        <!-- review section start here  -->
     
        <!-- review section ends here  -->

        <!-- footer section start here  -->

        <footer class="footer" id="contact">
            <div class="box-container">
                <div class="mainBox">
                    <div class="content">
                        <a href="#">
                            <img src="images/logo kita.png" alt>
                        </a>
                        <h1 class="logoName"> Croquant Cookies </h1>
                    </div>

                    <p>Kue kering lezat untuk semua. Nikmati kelezatan dalam setiap gigitan, sempurna untuk momen istimewa.</p>

                </div>
                <div class="box">
                <h3>Link Cepat</h3>
                <a href="#"> <i class="fas fa-arrow-right"></i>Beranda</a>
                <a href="#"> <i class="fas fa-arrow-right"></i>Produk</a>
                <a href="#"> <i class="fas fa-arrow-right"></i>Blog</a>
                <a href="#"> <i class="fas fa-arrow-right"></i>Umpan Balik</a>
                <a href="#"> <i class="fas fa-arrow-right"></i>Kontak</a>
            </div>
            <div class="box">
                <h3>Link Ekstra</h3>
                <a href="#"> <i class="fas fa-arrow-right"></i>Info Akun</a>
                <a href="#"> <i class="fas fa-arrow-right"></i>Pesanan</a>
                <a href="#"> <i class="fas fa-arrow-right"></i>Metode Pembayaran</a>
            </div>
                <div class="box">
                    <h3>Contact Info</h3>
                    <a href="#"> <i class="fas fa-phone"></i>+62 896 0262 3481</a>
                    <a href="#"> <i
                            class="fas fa-envelope"></i>croquant.cookies00@gmail.com</a>

                </div>

            </div>
            <div class="share">
                <a href="#" class="fab fa-facebook-f"></a>
                <a href="#" class="fab fa-twitter"></a>
                <a href="#" class="fab fa-instagram"></a>
                <a href="#" class="fab fa-linkedin"></a>
                <a href="#" class="fab fa-pinterest"></a>
            </div>
            <div class="credit">
                Dibuat Oleh <span>Farrel Arkana</span> | @CroquantCookies
                
            </div>
        </footer>

        <!-- swiper js link  -->
        <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>

        <!-- custom js file  -->
        <script src="index.js"></script>

    </body>

</html>