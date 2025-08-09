<?php
include '../session_check.php';
include '../connection.php';

// Get the logged-in student ID
$user_id = $_SESSION['user'];

// Get student's full name from database
$name = "applicant"; // default
$name_query = "SELECT full_name FROM users WHERE user_id = ?";
$name_stmt = $conn->prepare($name_query);
$name_stmt->bind_param("i", $user_id);
$name_stmt->execute();
$name_result = $name_stmt->get_result();
if ($name_row = $name_result->fetch_assoc()) {
    $name = $name_row['full_name'];
}

$batchNumber = "XX"; // Default if nothing is found

// Fetch the latest batch_number from training_schedule
$sql = "SELECT batch_number FROM training_schedule ORDER BY id DESC LIMIT 1";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $batchNumber = $row['batch_number'];
}



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Dashboard - Smart Ndoa</title>
    <link rel="stylesheet" href="../student/student_dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        /* Existing and form styles combined */
        .form-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 30px;
            margin-bottom: 30px;
        }

        .form-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(5px);
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 0 30px rgba(0, 128, 0, 0.2);
            width: 90%;
            max-width: 1000px;
            text-align: center;
            box-sizing: border-box;
            margin: auto;
            overflow-y: visible; /*  No scroll */
        }


        .form-container img {
            width: 100px;
            margin-bottom: 20px;
        }

        h1, h2, h3 {
          
            margin-bottom: 10px;
            font-family: 'Merriweather', serif;
        }

        label {
            display: block;
            text-align: left;
            margin-top: 15px;
            font-weight: bold;
            
        }

        input[type="text"],
        input[type="date"],
        input[type="email"],
        input[type="file"],
        select {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
            font-size: 16px;
            box-sizing: border-box;
        }

        input[type="radio"] {
            margin-right: 5px;
        }

        .section {
            text-align: left;
            margin-top: 30px;
        }
        #title {
            text-align: center;
        }

        .buttons {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 20px;
        }

        .submit-btn {
            background-color: #2E8B57;
            color: #fff;
            padding: 10px 20px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
        }

        .submit-btn:hover {
            background-color: #228B22;
        }

        .reset-btn {
            background-color: #8B0000;
            color: #fff;
            padding: 10px 20px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
        }

        .reset-btn:hover {
            background-color: #a80000;
        }
        @media (max-width: 768px) {
    .form-container {
        padding: 20px;
    }

    label {
        font-size: 14px;
    }

    input[type="text"],
    input[type="date"],
    input[type="email"],
    input[type="file"],
    select {
        font-size: 14px;
        padding: 8px;
    }

    .buttons {
        flex-direction: column;
        gap: 10px;
    }

    .submit-btn,
    .reset-btn {
        width: 100%;
    }
}

    </style>
</head>
<body>
<div class="dashboard-container">


    <!-- Sidebar -->
    <?php include "../sidebar.php"; ?>

    <!-- Main Content -->
    <div class="main-content">
        <header>
            
        </header>

        <div class="info-box">
            <h3>Karibu Ndugu  <b><?= htmlspecialchars($name) ?></b> Katika Mfumo Wetu Wa SMART NDOA Unaojikita Zaidi Ktika Kutoa Elimu Ya Ndoa.  Tafadhali Jaza Formu Hioo Hapo Chini</h3>
        </div>

        <!--  Application Form Starts -->
        <div class="form-wrapper">
            <form class="form-container" action="submit_application.php" method="POST" enctype="multipart/form-data">
                <img src="../Logo/logo.JPG" alt="Mufti Logo">
                <h2>OFISI YA MUFTI MKUU</h2>
                <h2>BISMILLAHI RAHMANI RAHIIM</h2>

                <div class="section">
            <h3 id="title">FOMU YA KUJIUNGA NA MAFUNZO YA MAADILI YA NDOA MKUPUO WA <?php echo $batchNumber; ?></br> KATIKA OFISI YA MUFTI MKUU WA ZANZIBAR</h3>
             <h3 id="title"><b>JAZA FOMU HII KWA USAHIHI</b></h3>
        </div>

                <div class="section">
                    <h3>MAELEZO BINAFSI</h3>
                    <label>Jina kamili la muombaji</label>
                    <input type="text" name="name" required>

                    <label>Barua Pepe</label>
                    <input type="email" name="email" required>

                    <label>Jinsia</label>
                    <input type="radio" name="gender" value="male"> Me
                    <input type="radio" name="gender" value="female"> Ke

                    <label>Tarehe ya Kuzaliwa</label>
                    <input type="date" name="dob" required>

                    <label>Uraia</label>
                    <select name="uraia" required>
                        <option value="">--Chagua Uraia--</option>
                        <option value="Mzanzibari">Mtanzania</option>
                        <option value="Raia wa Kigeni">Raia wa Kigeni</option>
                    </select>

                    <label>Shehia</label>
                    <select name="shehia" id="shehia" required onchange="updateWilaya()">
                        <option value="">--Chagua Shehia--</option>
                        <option value="Mazizini">Mazizini</option>
                        <option value="Kijichi">Kijichi</option>
                        <option value="Mombasa">Mombasa</option>
                        <option value="Shaurimoyo">Shaurimoyo</option>
                        <option value="Mwembemakumbi">Mwembemakumbi</option>
                        <option value="Kikwajuni">Kikwajuni</option>
                        <option value="Kilimahewa">Kilimahewa</option>
                        <option value="Chuini">Chuini</option>
                        <option value="Fuoni">Fuoni</option>
                        <option value="Amani">Amani</option>
                        <option value="Chumbuni">Chumbuni</option>
                        <option value="Makadara">Makadara</option>
                        <option value="Kinuni">Kinuni</option>
                        <option value="Bububu">Bububu</option>
                        <option value="Mtoni">Mtoni</option>
                        <option value="Kisauni">Kisauni</option>
                        <option value="Dunga">Dunga</option>
                        <option value="Mwera">Mwera</option>
                    </select>

                    <label>Wilaya</label>
                    <select name="wilaya" id="wilaya" required>
                        <option value="">--Chagua Wilaya--</option>
                    </select>

                    <label>Namba ya Simu</label>
                    <input type="text" name="phone" required>

                    <label>Umeajiriwa?</label>
                    <input type="radio" name="umeajiriwa" value="ndio"> Ndio
                    <input type="radio" name="umeajiriwa" value="hapana"> Hapana

                    <label>Kama Ndio - Sehemu</label>
                    <input type="radio" name="sehemu" value="serekalini"> Serikali
                    <input type="radio" name="sehemu" value="binafsi"> Binafsi

                    <label>Hali ya Ndoa</label>
                    <input type="radio" name="ndoa" value="umeoa"> Nimeoa/Nimeolewa
                    <input type="radio" name="ndoa" value="sijaoa"> Sijaoa/Sijaolewa
                    <input type="radio" name="ndoa" value="mjane"> Mjane/Mgane
                    <input type="radio" name="ndoa" value="ninamchumba"> Ninayo mchumba
                    <input type="radio" name="ndoa" value="sinamchumba"> Sina mchumba

                    <label>Andika hali ya ulemavu uliokuwa nao</label>
                    <input type="text" name="disability">

                    <label for="zanid">Ingiza picha ya kitambulisho cha Mzanzibari/Leseni/Pasport</label>
                    <input type="file" name="zanid" required>
                </div>

                <div class="section">
                    <h3>TAARIFA ZA MASOMO</h3>
                    <label>Kiwango cha elimu ulichonacho</label>
                    <select name="elimu" required>
                        <option value="">--Chagua Kiwango cha Elimu--</option>
                        <option value="Elimu ya Msingi">Elimu ya Msingi</option>
                        <option value="Elimu ya Sekondari">Elimu ya Sekondari</option>
                        <option value="Cheti (Certificate)">Cheti (Certificate)</option>
                        <option value="Stashahada (Diploma)">Stashahada (Diploma)</option>
                        <option value="Shahada ya Kwanza (Bachelor Degree)">Shahada ya Kwanza</option>
                        <option value="Shahada ya Pili (Masters)">Shahada ya Pili</option>
                        <option value="Shahada ya Uzamivu (PhD)">Shahada ya Uzamivu (PhD)</option>
                        <option value="Hakuna">Hakuna</option>
                    </select>

                    <p><b>Masomo:</b> Ndoa kwa ukamilifu wake.</p>
                    <p><b>Ada:</b> Bure (wiki 10)</p>
                    <p><b>Mafunzo:</b> Jumamosi & Jumapili, saa 2:00 - 6:00</p>
                </div>

                <div class="buttons">
                    <button type="submit" class="submit-btn">Tuma Maombi</button>
                    <button type="reset" class="reset-btn">Futa Maombi</button>
                </div>
            </form>
        </div>

    </div>
</div>

<script>
  const shehiaWilayaMap = {
    Mazizini: "Magharibi A",
    Kijichi: "Magharibi A",
    Mombasa: "Mjini",
    Shaurimoyo: "Mjini",
    Mwembemakumbi: "Mjini",
    Kikwajuni: "Mjini",
    Kilimahewa: "Magharibi A",
    Chuini: "Magharibi A",
    Fuoni: "Magharibi B",
    Amani: "Mjini",
    Chumbuni: "Maghmarib A",
    Makadara: "Mjini",
    Kinuni: "Magharibi B",
    Bububu: "Magharibi A",
    Mtoni: "Magharibi A",
    Kisauni: "Mjini Magharib",
    Dunga: "Kati",
    Mwera: "Magharibi A"
  };

  function updateWilaya() {
    const shehia = document.getElementById("shehia").value;
    const wilayaSelect = document.getElementById("wilaya");
    wilayaSelect.innerHTML = '<option value="">--Chagua Wilaya--</option>';

    if (shehia && shehiaWilayaMap[shehia]) {
      const wilaya = shehiaWilayaMap[shehia];
      const option = document.createElement("option");
      option.value = wilaya;
      option.text = wilaya;
      wilayaSelect.appendChild(option);
    }
  }
</script>
</body>
</html>
