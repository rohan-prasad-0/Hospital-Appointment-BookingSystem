<?php
session_start();
require_once "db_connection.php";

// Check if user is logged in as patient
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Patient') {
    header("Location: login_1.php");
    exit();
}

$appointment_id = $_GET['appointment_id'] ?? '';
$status = $_GET['status'] ?? '';

if (empty($appointment_id) || $status !== 'success') {
    header("Location: doctor_schedules.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Get patient name
$p_sql = "SELECT name FROM patient WHERE user_id = $user_id";
$p_rs = $mysqli->query($p_sql);
$p_row = mysqli_fetch_assoc($p_rs);
$patient_name = $p_row['name'];

// Get appointment details
$appointment_sql = "SELECT a.*, d.name AS doctor_name, s.sp_name, 
                    ds.available_date, ds.time_slot, ds.max_patient
                    FROM appointment a
                    JOIN doctor d ON a.doctor_id = d.doctor_id
                    JOIN specialization s ON d.sp_id = s.sp_id
                    JOIN doctor_schedule ds ON a.sch_id = ds.sch_id
                    WHERE a.appointment_id = $appointment_id";
$appointment_rs = $mysqli->query($appointment_sql);
$appointment = mysqli_fetch_assoc($appointment_rs);

// Get total appointments 
$queue_sql = "SELECT COUNT(*) as total_in_queue FROM appointment WHERE sch_id = {$appointment['sch_id']} AND status = 'Booked'";
$queue_rs = $mysqli->query($queue_sql);
$queue_info = $queue_rs->fetch_assoc();
$total_in_queue = $queue_info['total_in_queue'];

// Format date and time
$date = new DateTime($appointment['available_date']);
$dayName = $date->format('l');
$monthName = $date->format('F');
$dayNum = $date->format('d');
$year = $date->format('Y');

$start = DateTime::createFromFormat('H:i:s', $appointment['time_slot']);
$time_slot = $start->format('h:i A');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmation - ABC Hospital</title>
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.css">
    <script src="js/bootstrap.bundle.js"></script>
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="style.css">
    
</head>
<body>

<?php include('sidebar_patient.php'); ?>

<div class="main-content">
    <!-- Page Header -->
    <div class="page-header">
        <h4 class="fw-bold mb-0">
            <i class="bi bi-check-circle-fill text-success me-2"></i>
            Booking Confirmation
        </h4>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="confirmation-card">
                
                <h2 class="confirmation-title">Appointment Confirmed!</h2>
                <p class="confirmation-subtitle">Your appointment has been successfully booked. Here are your details:</p>
                
                <!-- Action Buttons -->
                <div class="action-buttons">
                    <a href="view_appointments.php" class="btn btn-confirm-action btn-view">
                        <i class="bi bi-calendar-check me-2"></i>
                        View My Appointments
                    </a>
                    <a href="doctor_schedules.php" class="btn btn-confirm-action btn-new">
                        <i class="bi bi-plus-circle me-2"></i>
                        Book Another
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>