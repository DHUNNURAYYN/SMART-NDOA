<!DOCTYPE html>
<html>
<head>
    <title>Welcome Section</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .welcome-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 50px;
            max-width: 1000px;
            margin: auto;
        }

        .welcome-text {
            flex: 1;
            padding-right: 30px;
        }

        .welcome-text h2 {
            color: #333;
            font-size: 28px;
            margin-bottom: 20px;
        }

        .welcome-text p {
            font-size: 16px;
            line-height: 1.6;
            color: #555;
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

        @media (max-width: 768px) {
            .welcome-section {
                flex-direction: column;
                text-align: center;
            }

            .welcome-text {
                padding-right: 0;
            }

            .welcome-image {
                margin-top: 20px;
            }
        }
    </style>
</head>
<body>

    <section class="welcome-section">
        <div class="welcome-text">
            <h2>Taarifa Rasmi Kuhusu Ofisi ya Mufti Mkuu wa Zanzibar</h2>
            <p>
               Ofisi ya Mufti Mkuu wa Zanzibar ilianzishwa rasmi mnamo mwaka 1992 kwa Amri ya Rais wa Zanzibar na Mwenyekiti wa Baraza la Mapinduzi wa wakati huo, Mheshimiwa Dk. Salmin Amour Juma. Aidha, baadaye ikaimarishwa rasmi kwa mujibu wa <strong>Sheria ya Mufti Na. 9 ya mwaka 2001</strong>.
           </p>
           <p>Kabla ya kuanzishwa kwa ofisi hii, baadhi ya majukumu ya kidini yalitekelezwa na Masheikh maarufu, wakiwemo Sheikh Hassan bin Ameir na Sheikh Fatawi bin Issa, kupitia Msikiti wa Gofu. Walikuwa wakitoa fatwa, kusuluhisha migogoro, na kutoa miongozo ya
                dini kwa mujibu wa mazingira ya wakati huo. Kwa upande wa Serikali, Mahakama ya Kadhi Mkuu ilihusika katika masuala ya kidini.</p>
            <p> وَاعْتَصِمُوا بِحَبْلِ اللَّهِ جَمِيعًا وَلَا تَفَرَّقُوا ۚ وَاذْكُرُوا نِعْمَتَ اللَّهِ عَلَيْكُمْ إِذْ كُنْتُمْ أَعْدَاءً فَأَلَّفَ بَيْنَ قُلُوبِكُمْ...</p>
            <button>More About My Style</button>
        </div>
        <div class="welcome-image">
            <img src="logo/logo.jpg" alt="logo">
        </div>
    </section>

</body>
</html>
