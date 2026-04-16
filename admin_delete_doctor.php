<?php
session_start();
require_once "db_connection.php";

// Check admin login
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header("Location: login_1.php");
    exit();
}

if (isset($_GET['doctor_id'])) {
    $doctor_id = intval($_GET['doctor_id']);

    // Check if doctor has appointments
    $check = $mysqli->query("SELECT COUNT(*) AS total FROM appointment WHERE doctor_id = $doctor_id");
    $appointments = $check->fetch_assoc()['total'];

    if ($appointments > 0) {
        header("Location: admin_view_doctors.php?error=Cannot+delete+doctor,+appointments+exist");
        exit();
    }

    // Get user_id
    $user_result = $mysqli->query("SELECT user_id FROM doctor WHERE doctor_id = $doctor_id");
    if ($user_result->num_rows == 0) {
        header("Location: admin_view_doctors.php?error=Doctor+not+found");
        exit();
    }

    $user_id = $user_result->fetch_assoc()['user_id'];

    // Delete doctor and user
    if ($mysqli->query("DELETE FROM doctor WHERE doctor_id = $doctor_id") &&
        $mysqli->query("DELETE FROM user WHERE user_id = $user_id")) {
        header("Location: admin_view_doctors.php?success=Doctor+deleted+successfully");
        exit();
    } else {
        header("Location: admin_view_doctors.php?error=Error+deleting+doctor");
        exit();
    }

} else {
    header("Location: admin_view_doctors.php");
    exit();
}
?>