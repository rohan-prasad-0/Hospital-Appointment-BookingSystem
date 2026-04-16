<?php
session_start();
require_once "db_connection.php";

// Check admin login
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header("Location: login.php");
    exit();
}

if (isset($_GET['doctor_id'])) {
    $doctor_id = intval($_GET['doctor_id']);

    // Check if doctor has appointments
    $check_appointments = $mysqli->query("SELECT COUNT(*) AS total FROM appointment WHERE doctor_id = $doctor_id");
    $appointments_count = $check_appointments->fetch_assoc()['total'];

    // Check if doctor has schedules
    $check_schedules = $mysqli->query("SELECT COUNT(*) AS total FROM doctor_schedule WHERE doctor_id = $doctor_id");
    $schedules_count = $check_schedules->fetch_assoc()['total'];

    // If doctor has appointments OR schedules, prevent deletion
    if ($appointments_count > 0 || $schedules_count > 0) {
        $error_message = "";
        if ($appointments_count > 0 && $schedules_count > 0) {
            $error_message = "Cannot delete doctor, they have $appointments_count appointment(s) and $schedules_count schedule(s)";
        } elseif ($appointments_count > 0) {
            $error_message = "Cannot delete doctor, they have $appointments_count appointment(s)";
        } else {
            $error_message = "Cannot delete doctor, they have $schedules_count schedule(s)";
        }
        header("Location: admin_view_doctors.php?error=" . urlencode($error_message));
        exit();
    }

    // Get user_id
    $user_result = $mysqli->query("SELECT user_id FROM doctor WHERE doctor_id = $doctor_id");
    if ($user_result->num_rows == 0) {
        header("Location: admin_view_doctors.php?error=Doctor+not+found");
        exit();
    }

    $user_id = $user_result->fetch_assoc()['user_id'];

    // Start transaction for data integrity
    $mysqli->begin_transaction();
    
    try {
        // Delete doctor and user (cascade should handle related records if set up properly)
        $delete_doctor = $mysqli->query("DELETE FROM doctor WHERE doctor_id = $doctor_id");
        $delete_user = $mysqli->query("DELETE FROM user WHERE user_id = $user_id");
        
        if ($delete_doctor && $delete_user) {
            $mysqli->commit();
            header("Location: admin_view_doctors.php?success=Doctor+deleted+successfully");
            exit();
        } else {
            throw new Exception("Error deleting doctor records");
        }
    } catch (Exception $e) {
        $mysqli->rollback();
        header("Location: admin_view_doctors.php?error=Error+deleting+doctor");
        exit();
    }

} else {
    header("Location: admin_view_doctors.php");
    exit();
}
?>