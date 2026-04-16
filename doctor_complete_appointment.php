<?php
session_start();
require_once "db_connection.php";

// Check if user is logged in as doctor
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Doctor') {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $appointment_id = intval($_GET['id']);
    $user_id = $_SESSION['user_id'];
    
    // Verify the appointment belongs to the doctor
    $verify_sql = "SELECT a.* FROM appointment a 
                   JOIN doctor d ON a.doctor_id = d.doctor_id 
                   WHERE a.appointment_id = $appointment_id AND d.user_id = $user_id";
    $verify_rs = $mysqli->query($verify_sql);
    
    if (mysqli_num_rows($verify_rs) > 0) {
        // Update appointment status to completed
        $complete_sql = "UPDATE appointment SET status = 'Completed' WHERE appointment_id = $appointment_id";
        
        if ($mysqli->query($complete_sql)) {
            header("Location: doctor_appointments.php?status=completed");
        } else {
            header("Location: doctor_appointments.php?status=error");
        }
    } else {
        header("Location: doctor_appointments.php?status=invalid");
    }
} else {
    header("Location: doctor_appointments.php");
}
exit();
?>