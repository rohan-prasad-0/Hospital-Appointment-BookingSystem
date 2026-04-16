<?php
session_start();
require_once "db_connection.php";

// Check if user is logged in as patient
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Patient') {
    header("Location: login.php");
    exit();
}

$appointment_id = $_GET['appointment_id'] ?? '';

if (empty($appointment_id)) {
    header("Location: view_appointments.php");
    exit();
}

// Update appointment status to Cancelled
$update_sql = "UPDATE appointment SET status = 'Cancelled' WHERE appointment_id = $appointment_id";

if ($mysqli->query($update_sql)) {
    header("Location: view_appointments.php?status=cancel_success");
} else {
    header("Location: view_appointments.php?status=cancel_error");
}
exit();
?>