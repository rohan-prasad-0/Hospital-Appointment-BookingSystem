<?php
session_start();
require_once "db_connection.php";

// Check if user is logged in as doctor
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Doctor') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Get doctor details
$doc_sql = "SELECT doctor_id, name FROM doctor WHERE user_id = $user_id";
$doc_rs = mysqli_query($mysqli, $doc_sql);
$doc_row = mysqli_fetch_assoc($doc_rs);
$doctor_id = $doc_row['doctor_id'];

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $available_date = trim($_POST['available_date']);
    $time_slot = trim($_POST['time_slot']);
    $max_patient = intval($_POST['max_patient']);
    
    $errors = [];

    if (empty($available_date)) {
        $errors[] = "Date is required.";
    }
    if (empty($time_slot)) {
        $errors[] = "Time slot is required.";
    }
    if ($max_patient < 1 || $max_patient > 10) {
        $errors[] = "Enter valid number of maximum patients.";
    }
    if (strtotime($available_date) < strtotime(date('Y-m-d'))) {
        $errors[] = "Cannot create schedule for past dates.";
    }

    if (empty($errors)) {
        // Check if slot already exists
        $check_sql = "SELECT * FROM doctor_schedule 
                      WHERE doctor_id = '$doctor_id' 
                      AND available_date = '$available_date' 
                      AND time_slot = '$time_slot'";
        $check_rs = mysqli_query($mysqli, $check_sql);

        if (mysqli_num_rows($check_rs) > 0) {
            $errors[] = "A schedule already exists for this date and time.";
        } else {
            // Insert new schedule
            $insert_sql = "INSERT INTO doctor_schedule (doctor_id, available_date, time_slot, max_patient, status) VALUES ('$doctor_id', '$available_date', '$time_slot', '$max_patient', 'Available')";

            if (mysqli_query($mysqli, $insert_sql)) {
                header("Location: doctor_schedule_management.php?success=Time+slot+added+successfully!");
                exit();
            } else {
                $errors[] = "Error adding time slot: " . mysqli_error($mysqli);
            }
        }
    }

    // If errors found, redirect back with messages
    if (!empty($errors)) {
        $error_string = implode("+", array_map('urlencode', $errors));
        header("Location: doctor_schedule_management.php?error=" . $error_string);
        exit();
    }
} else {
    header("Location: doctor_schedule_management.php");
    exit();
}
?>
