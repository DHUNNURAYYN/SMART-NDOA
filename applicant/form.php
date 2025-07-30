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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Dashboard - Smart Ndoa 🎓</title>
    <link rel="stylesheet" href="../student/student_dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        .form-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 30px;
            margin-bottom: 30px;
        }

        .form-container {
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(5px);
    padding: 40px;
    border-radius: 15px;
    box-shadow: 0 0 30px rgba(0, 128, 0, 0.2);
    width: 100%;
    height:auto;
    max-width: 1000px;
    text-align: center;
    box-sizing: border-box;

    /* ADD THESE LINES  */
    max-height: 80vh; /* Control height of form */
    overflow-y: auto; /* Enables vertical scroll */
    scrollbar-width: thin; /* For Firefox */
}


 

        .form-container img {
            width: 100px;
            margin-bottom: 20px;
        }

        h1, h2, h3 {
            color: #228B22;
            margin-bottom: 10px;
            font-family: 'Merriweather', serif;
        }

        #title {
            text-align: center;
        }

        label {
            display: block;
            text-align: left;
            margin-top: 15px;
            font-weight: bold;
            color: #006400;
        }

      input[type="text"],
    input[type="date"],
    select {
    width: 100%;
    padding: 10px;
    margin-top: 5px;
    border: 1px solid #ccc;
    border-radius: 5px;
    background-color: #f9f9f9;
    box-sizing: border-box;
    font-size: 16px;
}


        input[type="radio"] {
            margin-right: 5px;
        }

        .section {
            text-align: left;
            margin-top: 30px;
        }

        .buttons {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 20px;
        }

        .submit-btn,
        .reset-btn {
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
        }

        .submit-btn {
            background-color: #2E8B57;
            color: #fff;
        }

        .submit-btn:hover {
            background-color: #228B22;
        }

        .reset-btn {
            background-color: #8B0000;
            color: #fff;
        }

        .reset-btn:hover {
            background-color: #a80000;
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
            <h1>Welcome : <b><?=($name) ?></b> </h1>
        </header>

        <!-- Attendance Summary -->
       
            <h3>Tafadhali jaza Form ya Maombi</h3>
        

        <!-- Application Form -->

        <div class="form-wrapper">
            <form class="form-container" action="submit_application.php" method="POST" enctype="multipart/form-data">
                <img src="../Logo/logo.JPG" alt="Mufti Logo">
                <h2>OFISI YA MUFTI MKUU</h2>
                <h2>BISMILLAHI RAHMANI RAHIIM</h2>

                <div class="section">
                    <h3 id="title">FOMU YA KUJIUNGA NA MAFUNZO YA MAADILI YA NDOA MKUPUO WA 26 KATIKA OFISI YA MUFTI MKUU WA ZANZIBAR</h3>
                    <h3 id="title"><b>JAZA FOMU HII KWA USAHIHI</b></h3>
                </div>

                <div class="section">
                    <h3>MAELEZO BINAFSI</h3>
                    <label>Jina kamili la muombaji</label>
                    <input type="text" name="name" required>

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

<script>
  const shehiaWilayaMap = {
    Mazizini: "Magharibi B",
    Kijichi: "Magharibi A",
    Mombasa: "Mjini",
    Shaurimoyo: "Mjini",
    Mwembemakumbi: "Mjini",
    Kikwajuni: "Mjini",
    Kilimahewa: "Magharibi B",
    Chuini: "Magharibi A",
    Fuoni: "Magharibi B",
    Amani: "Mjini",
    Chumbuni: "Mjini",
    Makadara: "Mjini",
    Kinuni: "Magharibi A",
    Bububu: "Magharibi A",
    Mtoni: "Magharibi B",
    Kisauni: "Magharibi A",
    Dunga: "Kati",
    Mwera: "Magharibi B"
  };

  function updateWilaya() {
    const shehia = document.getElementById("shehia").value;
    const wilayaSelect = document.getElementById("wilaya");

    // Clear existing options
    wilayaSelect.innerHTML = '<option value="">--Chagua Wilaya--</option>';

    // Check if shehia exists
    if (shehia && shehiaWilayaMap[shehia]) {
      const wilaya = shehiaWilayaMap[shehia];
      const option = document.createElement("option");
      option.value = wilaya;
      option.text = wilaya;
      wilayaSelect.appendChild(option);
    }
  }
</script>


                    <label>Namba ya Simu</label>
                    <input type="text" name="phone" required>

                    <label>Umeajiriwa?</label>
                    <input type="radio" name="umeajiriwa" value="ndio"> Ndio
                    <input type="radio" name="umeajiriwa" value="hapana"> Hapana

                    <label>Kama Ndio <br> Sehemu</label>
                    <input type="radio" name="sehemu" value="serekalini"> Serikali
                    <input type="radio" name="sehemu" value="binafsi"> Binafsi

                    <label>Hali ya Ndoa</label>
                    <input type="radio" name="ndoa" value="umeoa"> Umeoa/Nimeolewa
                    <input type="radio" name="ndoa" value="sijaoa"> Sijaoa/Sijaolewa
                    <input type="radio" name="ndoa" value="mjane"> Mjane/Mgane
                    <input type="radio" name="ndoa" value="ninamchumba"> Ninayo mchumba
                    <input type="radio" name="ndoa" value="sinamchumba"> Sina mchumba

                    <label>Andika hali ya ulemavu uliokuwa nao</label>
                    <input type="text" name="disability">

                    <label for="zanid">Ingiza picha ya kitambulisho chako cha Mzanzibari</label>
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


                    <p><b>Masomo yanayofundishwa:</b> Ndoa kwa ukamilifu wake.</p>
                    <p><b>Ada ya Masomo:</b> Bure, muda wa wiki kumi.</p>
                    <p><b>Mafunzo:</b> Jumamosi na Jumapili, kuanzia saa 2:00 Asubuhi hadi 6:00 Mchana, Msikiti wa Jamii Zanzibar, Mazizini.</p>
                    <p><b>Mavazi:</b> Yazinge taratibu za Kiislamu.</p>
                </div>

                <div class="buttons">
                    <button type="submit" class="submit-btn">Tuma Maombi</button>
                    <button type="reset" class="reset-btn">Futa Maombi</button>
                </div>
            </form>
        </div>
    </div>
</div>


</body>
</html>
