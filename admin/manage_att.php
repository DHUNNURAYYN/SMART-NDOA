<?php
include "connection.php";
include "admin_session.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['attendance'])) {
    foreach ($_POST['attendance'] as $user_id => $dates) {
        foreach ($dates as $date => $status) {
            // Check if record exists
            $stmt = $conn->prepare("SELECT id FROM attendances WHERE user_id = ? AND date = ?");
            $stmt->bind_param("is", $user_id, $date);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                // Update existing
                $update = $conn->prepare("UPDATE attendances SET status = ? WHERE user_id = ? AND date = ?");
                $update->bind_param("sis", $status, $user_id, $date);
                $update->execute();
            } else {
                // Insert new
                $insert = $conn->prepare("INSERT INTO attendances (user_id, date, status) VALUES (?, ?, ?)");
                $insert->bind_param("iss", $user_id, $date, $status);
                $insert->execute();
            }
        }
    }
    header("Location: manage_attendance.php?success=1");
    exit();
} else {
    echo "Invalid access.";
}
?>
