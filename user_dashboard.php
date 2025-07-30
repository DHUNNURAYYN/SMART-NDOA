<!DOCTYPE html>
<html lang="sw">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SMART NDOA - Homepage</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            margin: 0;
            font-family: 'Roboto', sans-serif;
            background: #f9f9f9;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        
        .navbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background-color: #fff;
            padding: 10px 20px;
        }
        
        .nav-left img {
            width: 100px;
        }
        
        .nav-center {
            display: flex;
            flex: 1;
            justify-content: center;
        }
        
        .nav-item {
            color: #32CD32;
            margin: 0 15px;
            font-size: 16px;
            text-decoration: none;
            transition: 0.3s;
            font-family: 'Poppins', sans-serif;
        }
        
        .nav-item:hover {
            color: #205B40;
            border-bottom: 4px solid #32CD32;
        }
        
        .nav-right {
            margin-left: auto;
        }
        
        .content {
            flex: 1;
            padding: 40px;
        }
        
        .main-content {
            background-color: #fff;
            padding: 0.5rem;
            border-radius: 12px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
            color: #333;
            line-height: 1.8;
            margin-top: 10px;
        }
        
        .slider {
            position: relative;
            width: 100%;
            height: 500px;
            overflow: hidden;
            border-radius: 12px;
            margin-bottom: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
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
            /* Pushes it to the left side with space */
            top: 50%;
            transform: translateY(-50%);
            max-width: 600px;
            text-align: left;
            color: #fff;
            padding: 20px;
        }
        
        .slider-text h1 {
            color: #205B40;
            font-size: 45px;
            font-weight: 700;
            line-height: 1.2;
            margin-bottom: 20px;
            font-family: 'Poppins', sans-serif;
        }
        
        .slider-text p {
            color: #205B40;
            font-size: 18px;
            margin-bottom: 30px;
            font-family: 'Poppins', sans-serif;
        }
        
        .join-btn {
            font-family: 'Poppins', sans-serif;
            display: inline-block;
            padding: 12px 25px;
            background: #205B40;
            color: #fff;
            border-radius: 25px;
            text-decoration: none;
            font-size: 16px;
            font-weight: 500;
            transition: background 0.3s;
        }
        
        .join-btn:hover {
            background: #32CD32;
        }
        
        .news-section {
            margin-top: 40px;
            text-align: center;
        }
        
        .news-section h2 {
            text-shadow: 2px solid #32CD32;
            font-family: 'Poppins', sans-serif;
            color: #205B40;
            border-bottom: 4px solid #32CD32;
            padding-bottom: 10px;
        }
        
        .news-row {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 5px;
        }
        
        .news-item {
            background: #fff;
            border-radius: 10px;
            cursor: pointer;
            transition: transform 1.3s;
            box-shadow: 0 0 30px #205B40;
            text-align: left;
            width: 300px;
            overflow: hidden;
        }
        
        .news-item:hover {
            transform: translateX(-20px);
        }
        
        .news-item img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        
        .news-content {
            padding: 10px;
        }
        
        .news-date {
            font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
            color: #888;
            font-size: 14px;
        }
        
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
        
        .footer-column p {
            margin: 8px 0;
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
        
        .text-icon {
            font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
        }
    </style>
</head>

<body>

    <div class="navbar">
        <div class="nav-left">
            <img src="Logo/logo.JPG" alt="Mufti Logo">
        </div>
        <div class="nav-center">
            <a class="nav-item" href="#">Nyumbani</a>
            <a class="nav-item" href="#">Angalia Masomo</a>
            <a class="nav-item" href="#">Kuhusu Sisi </a>
            <a class="nav-item" href="#">Wasiliana Nasi</a>

        </div>
        <div class="nav-right">
            <a class="nav-item" href="#">
                <img src="logo/login.png" alt="Jisajili" style="width: 50px; vertical-align: middle;"> Jisajili
            </a>
        </div>

    </div>

    <div class="content">

        <div class="slider">
            <img src="Image slide/b57a3e276833e2352cae8b1994fab8f5(480P).jpg" alt="Hero Image">
            <div class="slider-text">
                <h1>Ofisi ya Mufti Mkuu wa Zanzibar,<br>Smart Ndoa.</h1>
                <p>Taswira za mafunzo ya ndoa yanayoendeshwa na Waalimu mahiri walioandaliwa kutoa elimu ya ndoa <br>kwa vijana wetu</p>
                <a href="#" class="join-btn">Omba sasa </a>
            </div>
        </div>


        <div class="news-section">
            <h2>Habari Mpya</h2>
            <div class="news-row">

                <div class="news-item">
                    <img src="Image slide/Muslim-women-in-college.jpg" alt="News 1">
                    <div class="news-content">
                        <h4 style="color:#205B40;border-bottom: 2px solid #32CD32;    font-family: 'Poppins', sans-serif;">Tangazo la Mafunzo ya Ndoa</h3>
                            <p class="news-date">Mei 10, 2025</p>
                            <p style="font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;">Ofisi ya Mufti Mkuu wa Zanzibar inawatangazia Waislamu wote kushiriki mafunzo ya ndoa yatakayofanyika Jumamosi ijayo katika ukumbi mkuu wa ofisi hiyo.</p>
                    </div>
                </div>
                <div class="news-item">
                    <img src="Image slide/74fcbb95c75ab2bf4c550d457423af5f(480P).jpg" alt="News 1">
                    <div class="news-content">
                        <h4 style="color:#205B40;border-bottom: 2px solid #32CD32;    font-family: 'Poppins', sans-serif;">Tangazo la Mafunzo ya Ndoa</h3>
                            <p class="news-date">Mei 10, 2025</p>
                            <p style="font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;">Ofisi ya Mufti Mkuu wa Zanzibar inawatangazia Waislamu wote kushiriki mafunzo ya ndoa yatakayofanyika Jumamosi ijayo katika ukumbi mkuu wa ofisi hiyo.</p>
                    </div>
                </div>

                <div class="news-item">
                    <img src="Image slide/af296bf12097910368515ec804d2085b(480P).jpg" alt="News 2">
                    <div class="news-content">
                        <h4 style="color:#205B40;border-bottom: 2px solid #32CD32;    font-family: 'Poppins', sans-serif;">Vyeti Kupatikana Kidigitali</h3>
                            <p class="news-date">Aprili 25, 2025</p>
                            <p style="font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;">Kuanzia sasa, wahitimu wa mafunzo ya ndoa wataweza kupakua vyeti vyao moja kwa moja kupitia mfumo rasmi wa SMART NDOA.</p>
                    </div>
                </div>

                <div class="news-item">
                    <img src="Image slide/Niqab.jpg" alt="News 3">
                    <div class="news-content">
                        <h4 style="color:#205B40;border-bottom: 2px solid #32CD32;    font-family: 'Poppins', sans-serif;">Semina Maalum kwa Masheikh</h3>
                            <p class="news-date">Aprili 20, 2025</p>
                            <p style="font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;">Semina ya kuwajengea uwezo Masheikh wa wilaya zote inatarajiwa kufanyika mwishoni mwa mwezi huu ikiwa ni sehemu ya mpango wa kuwajengea uwezo viongozi wa dini.</p>
                    </div>
                </div>

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
                    <p><i class="fas fa-user-shield"></i> Barua Pepe ya Watumishi</p>
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