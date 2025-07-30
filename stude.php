<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Dashboard - SMART NDOA</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<style>
    body {
        margin: 0;
        font-family: 'Segoe UI', sans-serif;
        background: #f4f6f8;
    }

    .dashboard-container {
        display: flex;
        min-height: 100vh;
    }

    .sidebar {
        width: 250px;
        background-color: #001f3f;
        color: white;
        padding: 20px;
    }

    .sidebar h2.logo {
        font-size: 20px;
        margin-bottom: 30px;
    }

    .sidebar ul {
        list-style: none;
        padding: 0;
    }

    .sidebar ul li {
        margin-bottom: 20px;
    }

    .sidebar ul li a {
        color: white;
        text-decoration: none;
        display: flex;
        align-items: center;
    }

    .sidebar ul li a i {
        margin-right: 10px;
    }

    .main-content {
        flex: 1;
        padding: 30px;
    }

    header h1 {
        font-size: 24px;
        margin-bottom: 20px;
    }

    .info-box {
        background: white;
        border-radius: 8px;
        box-shadow: 0 0 10px #ccc;
        margin-bottom: 30px;
        padding: 20px;
    }

    .info-box h3 {
        margin-bottom: 15px;
        color: #001f3f;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }

    table, th, td {
        border: 1px solid #ddd;
    }

    th, td {
        padding: 10px;
        text-align: left;
    }

    .present {
        color: green;
        font-weight: bold;
    }

    .absent {
        color: red;
        font-weight: bold;
    }

    button {
        padding: 6px 12px;
        background-color: #004085;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    .percentage {
        font-weight: bold;
        color: #004085;
    }
</style>
<body>
    <div class="dashboard-container">

        <!-- Sidebar -->
        <div class="sidebar">
            <h2 class="logo">SMART NDOA 🎓</h2>
            <ul>
                <li><a href="#"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                <li><a href="#"><i class="fas fa-user"></i> My Profile</a></li>
                <li><a href="#"><i class="fas fa-book-open"></i> View Notes</a></li>
                <li><a href="#"><i class="fas fa-calendar-check"></i> Attendance</a></li>
                <li><a href="#"><i class="fas fa-certificate"></i> Certificate</a></li>
                <li><a href="#"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <header>
                <h1>Welcome, <b>Student Name</b> to SMART NDOA Dashboard 🎓</h1>
            </header>

            <!-- Attendance Summary -->
            
            <!-- Percentage Calculation -->
            <div class="info-box">
                <h3>📊 Attendance Percentage</h3>
                <p>You have attended <span class="percentage">85%</span> of your classes this semester.</p>
            </div>

            <!-- Notes Download -->
            <div class="info-box">
                <h3>📚 Course Notes</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Download</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Marriage Ethics - Week 1</td>
                            <td><button>Download PDF</button></td>
                        </tr>
                        <tr>
                            <td>Family Responsibilities - Week 2</td>
                            <td><button>Download PDF</button></td>
                        </tr>
                        <!-- Add more rows -->
                    </tbody>
                </table>
            </div>

            <!-- Certificate Section -->
            <div class="info-box">
                <h3>🎖️ Certificate</h3>
                <p>Your course certificate is ready.</p>
                <button>Download Certificate</button>
            </div>

        </div>
    </div>
</body>
</html>
