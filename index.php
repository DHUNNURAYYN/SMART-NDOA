
<?php
include 'connection.php'; // Make sure this is correct

$query = "SELECT * FROM news ORDER BY date_posted DESC LIMIT 4";
$result = mysqli_query($conn, $query);
?>

    <!DOCTYPE html>
    <html lang="sw">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Smart Ndoa - Mufti Mkuu wa Zanzibar</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
       <style>
        /* ====== Global Styles ====== */
body {
    margin: 0;
    font-family: 'Roboto', sans-serif;
    background: #f9f9f9;
    display: flex;
    flex-direction: column;
    min-height: 100vh;
}

.content {
    flex: 1;
    padding: 20px;
}

/* ====== Navbar ====== */
.navbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background-color: #fff;
    padding: 10px 20px;
    flex-wrap: wrap;
}

.nav-left img {
    width: 100px;
}

.nav-center {
    display: flex;
    justify-content: center;
    flex: 1;
    flex-wrap: wrap;
}

.nav-item {
    color: #32CD32;
    margin: 10px;
    font-size: 16px;
    text-decoration: none;
    font-family: 'Poppins', sans-serif;
    transition: 0.3s;
}

.nav-item:hover {
    color: #205B40;
    border-bottom: 4px solid #32CD32;
}

.nav-right {
    display: flex;
    align-items: center;
}

/* ====== Slider ====== */
.slider {
    position: relative;
    width: 100%;
    height: 500px;
    overflow: hidden;
    border-radius: 12px;
    margin-bottom: 40px;
}

.slider img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 12px;
}

.slider-text {
    position: absolute;
    left: 60px;
    top: 50%;
    transform: translateY(-50%);
    max-width: 600px;
    color: #205B40;
}

.slider-text h1 {
    font-size: 45px;
    font-weight: 700;
    margin-bottom: 20px;
    font-family: 'Poppins', sans-serif;
}

.slider-text p {
    font-size: 18px;
    margin-bottom: 30px;
    font-family: 'Poppins', sans-serif;
}

.join-btn {
    font-family: 'Poppins', sans-serif;
    padding: 12px 25px;
    background: #205B40;
    color: #fff;
    border-radius: 25px;
    text-decoration: none;
    font-size: 16px;
    font-weight: 500;
    
}

.join-btn:hover {
    background: #32CD32;
}

/* ====== Sections (About, Welcome) ====== */
.about-section {
    background: #f8f9fa;
    padding: 60px 20px;
    text-align: center;
    box-shadow: inset 0 4px 10px rgba(0, 0, 0, 0.05);
}

.about-container {
    max-width: 1000px;
    margin: auto;
}

.about-section h2,
.welcome-text h2 {
    font-family: 'Poppins', sans-serif;
    color: #205B40;
    border-bottom: 4px solid #32CD32;
    padding-bottom: 10px;
    margin-bottom: 20px;
}

.about-section p,
.welcome-text p {
    font-size: 16px;
    color: #444;
    margin-bottom: 10px;
    line-height: 1.6;
}

.welcome-section {
    display: flex;
    justify-content: center;
    align-items: center;
    flex-wrap: wrap;
    gap: 30px;
    padding: 50px 20px;
}

.welcome-text {
    flex: 1;
    min-width: 280px;
}

.welcome-text button {
    margin-top: 20px;
    background-color: #2c5d77;
    color: #fff;
    border: none;
    padding: 10px 20px;
    font-size: 14px;
    cursor: pointer;
    border-radius: 4px;
}

.welcome-image {
    flex: 0 0 200px;
}

.welcome-image img {
    width: 180px;
    height: 180px;
    object-fit: cover;
    border-radius: 50%;
    border: 4px solid #fff;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
}

/* ====== News Section ====== */
.news-section {
    margin-top: 40px;
    text-align: center;
}

.news-section h2 {
    font-family: 'Poppins', sans-serif;
    color: #205B40;
    border-bottom: 4px solid #32CD32;
    padding-bottom: 10px;
}

.news-row {
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
    gap: 40px;
    padding: 20px;
}

.news-item {
    width: 350px;
    margin-bottom: 30px;
    font-family: 'Poppins', sans-serif;
    border-radius: 10px;
    box-shadow: 0 0 30px #205B40;
    text-align: left;
    transition: transform 0.5s;
    background: #fff;
    overflow: hidden;
}

.news-item:hover {
    transform: translateX(-10px);
}

.news-item img {
    width: 100%;
    height: 200px;
    object-fit: cover;
}

.news-content h4 {
    background: black;
    color: white;
    padding: 10px;
    border-radius:4px;
}

.news-content p {
    padding: 10px;
}

/* ====== Footer ====== */
.footer {
    background-color: #000;
    color: #fff;
    font-size: 14px;
}

.footer-container {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-around;
    padding: 20px 10px;
}

.footer-column {
    flex: 1;
    min-width: 250px;
    margin: 5px;
}

.footer-column h3 {
    font-family: 'Poppins', sans-serif;
    border-bottom: 2px solid #32CD32;
    padding-bottom: 10px;
    margin-bottom: 20px;
    font-size: 18px;
}

.footer-bottom {
    background-color: #111;
    text-align: center;
    padding: 8px;
    color: #aaa;
    font-size: 13px;
    border-top: 1px solid #333;
}

.footer-column i {
    margin-right: 8px;
    color: #32CD32;
}



/* ====== Responsive Design ====== */
@media (max-width: 768px) {
    .slider {
        height: 300px;
    }

    .slider-text h1 {
        font-size: 30px;
    }

    .slider-text p {
        font-size: 16px;
    }

    .nav-center {
        justify-content: center;
        margin-top: 10px;
    }

    .welcome-section {
        flex-direction: column;
    }
}

</style>

</head>

    <body>

        <div class="navbar">
            <div class="nav-left">
                <img src="Logo/logo.JPG" alt="Mufti Logo">
            </div>
            <div class="nav-center">
                <a class="nav-item" href="index.php">Nyumbani</a>
                <a class="nav-item" href="#kuhusu">Kuhusu Sisi</a>
                <a class="nav-item" href="#habari"> Habari Mpya</a>
            </div>
            <div class="nav-right">
                <a class="nav-item" href="auth/login.php">
                    <img src="logo/login.png" alt="Jisajili" style="width: 50px; vertical-align: middle;"> Jisajili
                </a>
            </div>
        </div>
        
        <div class="content">
            <div class="slider">
                <img src="Image slide/b57a3e276833e2352cae8b1994fab8f5(480P).jpg" alt="Hero Image">
                <div class="slider-text">
                    <h1>Ofisi ya Mufti Mkuu wa Zanzibar,<br>Smart Ndoa.</h1>
                    <p>Taswira za mafunzo ya ndoa yanayoendeshwa na Waalimu mahiri walioandaliwa kutoa elimu ya ndoa.</p>
                    <a href="auth/login.php" class="join-btn">Omba sasa</a>
                </div>
            </div>

            <section id="kuhusu" class="about-section">
                <div class="about-container">
                    <section class="welcome-section">
                        <div class="welcome-text">
                            <h2>Historia Fupi Kuhusu Ofisi ya Mufti Mkuu wa Zanzibar</h2>
                            <p>
                                Ofisi ya Mufti Mkuu wa Zanzibar ilianzishwa rasmi mnamo mwaka 1992 kwa Amri ya Rais wa Zanzibar na Mwenyekiti wa Baraza la Mapinduzi wa wakati huo, Mheshimiwa Dk. Salmin Amour Juma. Aidha, baadaye ikaimarishwa rasmi kwa mujibu wa <strong>Sheria ya Mufti Na. 9 ya mwaka 2001</strong>.
                            </p>
                            <p>Kabla ya kuanzishwa kwa ofisi hii, baadhi ya majukumu ya kidini yalitekelezwa na Masheikh maarufu, wakiwemo Sheikh Hassan bin Ameir na Sheikh Fatawi bin Issa, kupitia Msikiti wa Gofu. Walikuwa wakitoa fatwa, kusuluhisha migogoro,
                                na kutoa miongozo ya dini kwa mujibu wa mazingira ya wakati huo. Kwa upande wa Serikali, Mahakama ya Kadhi Mkuu ilihusika katika masuala ya kidini.</p>
                            <p> وَاعْتَصِمُوا بِحَبْلِ اللَّهِ جَمِيعًا وَلَا تَفَرَّقُوا ۚ وَاذْكُرُوا نِعْمَتَ اللَّهِ عَلَيْكُمْ إِذْ كُنْتُمْ أَعْدَاءً فَأَلَّفَ بَيْنَ قُلُوبِكُمْ...</p>

                            <a href="ask_question.php" class="join-btn">Uliza swali</a>
                        </div>
                    </section>
                </div>
            </section>

            <div class="news-section" id ="habari">
                <h2>Habari Mpya</h2>
                <div class="news-row">
                    <!-- News 1 -->
                <?php
                                if ($result && mysqli_num_rows($result) > 0):
                    while ($row = mysqli_fetch_assoc($result)):
                        $imagePath = 'admin/' . $row['image'];
                ?>
                    <div class="news-item">
                        <img src="<?php echo $imagePath; ?>" alt="News Image" class="news-img">
                        <div class="news-content">
                            <h4 style="color:white;"><?php echo htmlspecialchars($row['title']); ?></h4>
                            <p><?php echo htmlspecialchars($row['content']); ?></p>
                        </div>
                    </div>
                <?php
                    endwhile;
                else:
                ?>
                    <p>Hakuna habari mpya kwa sasa.</p>
                <?php endif; ?>
            </div>
            </div>
        </div>

        <div class="footer">
            <div class="footer-container">
                <div class="footer-column">
                    <h3>Wasiliana Nasi</h3>
                    <div class="text-icon">
                        <p><i class="fas fa-map-marker-alt"></i> P.O.Box 2479, Unguja Zanzibar</p>
                        <p><i class="fas fa-phone"></i> +255 777 483 627</p>
                        <p><i class="fas fa-envelope"></i> info@muftizanzibar.go.tz</p>
                        <p><i class="fas fa-globe"></i> www.muftizanzibar.go.tz</p>
                    </div>
                </div>

                <div class="footer-column">
                    <h3>Wasiliana Nasi Pemba</h3>
                    <div class="text-icon">
                        <p><i class="fas fa-map-marker-alt"></i> P.O.Box 132, Gombani Mpya Zanzibar</p>
                        <p><i class="fas fa-phone"></i> +255 777 849 650</p>
                    </div>
                </div>
            </div>

            <div class="footer-bottom">
                &copy; 2025 - Haki zote zimehifadhiwa
            </div>
        </div>

    </body>

    </html>