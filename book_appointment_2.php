<?php
session_start();
include "db_connection.php";

// Check if user is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Patient') {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $doctor_id = intval($_POST['doctor_id']);
    $patient_id = intval($_POST['patient_id']);
    $sch_id = intval($_POST['sch_id']);
    $note = mysqli_real_escape_string($mysqli, trim($_POST['note']));

    if (empty($doctor_id) || empty($patient_id) || empty($sch_id)) {
        header("Location: book_appointment_1.php?doctor_id=$doctor_id&status=error");
        exit();
    }

    // Check schedule availability
    $check = $mysqli->query("SELECT max_patient FROM doctor_schedule WHERE sch_id = $sch_id AND status = 'Available'");
    if ($check->num_rows == 0) {
        header("Location: book_appointment_1.php?doctor_id=$doctor_id&status=schedule_not_found");
        exit();
    }

    $row = $check->fetch_assoc();
    $max_patient = $row['max_patient'];

    // Count booked patients for this schedule
    $booked = $mysqli->query("SELECT COUNT(*) AS total FROM appointment WHERE sch_id = $sch_id AND status = 'Booked'");
    $booked_count = $booked->fetch_assoc()['total'];

    if ($booked_count >= $max_patient) {
        header("Location: book_appointment_1.php?doctor_id=$doctor_id&status=slot_full");
        exit();
    }

    // Generate correct appointment number based on booked order
    $appointment_number = $booked_count + 1;

    // Insert appointment
    $insert = "INSERT INTO appointment (doctor_id, patient_id, sch_id, appointment_number, note, status, created_at)
               VALUES ($doctor_id, $patient_id, $sch_id, $appointment_number, '$note', 'Booked', NOW())";

    if ($mysqli->query($insert)) {
        $new_id = $mysqli->insert_id;
        header("Location: booking_confirmation.php?appointment_id=$new_id&status=success");
    } else {
        header("Location: book_appointment_1.php?doctor_id=$doctor_id&status=booking_error");
    }

} else {
    header("Location: doctor_schedules.php");
    exit();
}
?>