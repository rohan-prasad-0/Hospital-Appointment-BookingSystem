<?php
session_start();
require_once "db_connection.php";

// Check if user is logged in as patient
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Patient') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Get patient name
$p_sql = "SELECT name FROM patient WHERE user_id = $user_id";
$p_rs = $mysqli->query($p_sql);
$p_row = mysqli_fetch_assoc($p_rs);
$patient_name = $p_row['name'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Dashboard - ABC Hospital</title>
    
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
    <div class="dashboard-wrapper">
        <?php include('sidebar_patient.php'); ?>
        
        <div class="main-content">
            <div class="top-bar">
                <div class="d-flex align-items-center">
                    <button class="menu-btn d-lg-none me-3" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarOffcanvas">
                        <i class="bi bi-list"></i>
                    </button>
                    <div class="welcome-section">
                        <h4 class="fw-bold">Welcome, <?php echo $patient_name; ?></h4>
                    </div>
                </div>
            </div>
            
            <!-- Navigation Cards -->
            <h5 class="fw-bold mb-4">Quick Navigation</h5>
            
            <div class="navigation-cards">
                <a href="doctor_schedules.php" class="nav-card">
                    <div class="card-icon">
                        <i class="bi bi-calendar-plus"></i>
                    </div>
                    <h3>Book Appointment</h3>
                    <p>Schedule a new appointment with our specialist doctors at your convenience.</p>
                </a>
                
                <!-- View Appointments Card -->
                <a href="view_appointments.php" class="nav-card">
                    <div class="card-icon" style="background: linear-gradient(135deg, #28a745, #20c997);">
                        <i class="bi bi-calendar-check"></i>
                    </div>
                    <h3>My Appointments</h3>
                    <p>View all your upcoming and past appointments with detailed information.</p>
                </a>
                
                <!-- Doctor Schedules Card -->
                <a href="doctor_schedules.php" class="nav-card">
                    <div class="card-icon" style="background: linear-gradient(135deg, #17a2b8, #0dcaf0);">
                        <i class="bi bi-clock-history"></i>
                    </div>
                    <h3>Doctor Schedules</h3>
                    <p>Check availability and schedules of our doctors to plan your visit.</p>
                  
                </a>
                
            </div>
            
        </div>
    </div>
</body>
</html>