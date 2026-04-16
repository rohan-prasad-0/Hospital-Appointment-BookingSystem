<?php
session_start();
require_once "db_connection.php";

// Check if user is logged in as doctor
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Doctor') {
    header("Location: login_1.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Get doctor details
$doc_sql = "SELECT d.doctor_id, d.name, d.gender, d.phone, s.sp_name 
            FROM doctor d 
            JOIN specialization s ON d.sp_id = s.sp_id 
            WHERE d.user_id = $user_id";
$doc_rs = $mysqli->query($doc_sql);
$doc_row = mysqli_fetch_assoc($doc_rs);
$doctor_id = $doc_row['doctor_id'];
$doctor_name = $doc_row['name'];
$doctor_gender = $doc_row['gender'];
$doctor_phone = $doc_row['phone'];
$specialization = $doc_row['sp_name'];

// Get stats
$today_appointments = $mysqli->query("
    SELECT COUNT(*) as total FROM appointment a 
    JOIN doctor_schedule ds ON a.sch_id = ds.sch_id 
    WHERE a.doctor_id = $doctor_id 
    AND a.status = 'Booked' 
    AND ds.available_date = CURDATE()
")->fetch_assoc()['total'];

$upcoming_appointments = $mysqli->query("
    SELECT COUNT(*) as total FROM appointment a 
    JOIN doctor_schedule ds ON a.sch_id = ds.sch_id 
    WHERE a.doctor_id = $doctor_id 
    AND a.status = 'Booked' 
    AND ds.available_date >= CURDATE()
")->fetch_assoc()['total'];

$active_slots = $mysqli->query("
    SELECT COUNT(*) as total FROM doctor_schedule 
    WHERE doctor_id = $doctor_id 
    AND status = 'Available' 
    AND available_date >= CURDATE()
")->fetch_assoc()['total'];

$completed_appointments = $mysqli->query("
    SELECT COUNT(*) as total FROM appointment 
    WHERE doctor_id = $doctor_id 
    AND status = 'Completed'
")->fetch_assoc()['total'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Dashboard - ABC Hospital</title>
    
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
        <!-- Include Sidebar -->
        <?php include('sidebar_doctor.php'); ?>
        
        <!-- Main Content -->
        <div class="main-content">
            <div class="top-bar">
                <div class="d-flex align-items-center">
                    <button class="menu-btn d-lg-none me-3" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarOffcanvas">
                        <i class="bi bi-list"></i>
                    </button>
                    <div class="welcome-section">
                        <h4 class="fw-bold">Welcome, Dr. <?php echo $doctor_name; ?></h4>
                    </div>
                </div>
            </div>
            
            <!-- Stats Cards -->
            <div class="row g-4 mb-4">
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h2 class="fw-bold mb-1"><?= $today_appointments ?></h2>
                                <p class="text-muted mb-0">Today's Appointments</p>
                            </div>
                            <div class="stat-icon" style="background: linear-gradient(135deg, #8d31ab, #ee96d6);">
                                <i class="bi bi-calendar-check"></i>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h2 class="fw-bold mb-1"><?= $upcoming_appointments ?></h2>
                                <p class="text-muted mb-0">Upcoming</p>
                            </div>
                            <div class="stat-icon" style="background: linear-gradient(135deg, #28a745, #20c997);">
                                <i class="bi bi-clock"></i>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h2 class="fw-bold mb-1"><?= $active_slots ?></h2>
                                <p class="text-muted mb-0">Active Slots</p>
                            </div>
                            <div class="stat-icon" style="background: linear-gradient(135deg, #17a2b8, #0dcaf0);">
                                <i class="bi bi-calendar-week"></i>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h2 class="fw-bold mb-1"><?= $completed_appointments ?></h2>
                                <p class="text-muted mb-0">Completed</p>
                            </div>
                            <div class="stat-icon" style="background: linear-gradient(135deg, #6f42c1, #8540f5);">
                                <i class="bi bi-check-circle"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Navigation Cards -->
            <h5 class="fw-bold mb-4">Quick Actions</h5>
            
            <div class="navigation-cards">
                <!-- Add Schedule Card -->
                <a href="doctor_schedule_management.php" class="nav-card">
                    <div class="card-icon">
                        <i class="bi bi-plus-circle"></i>
                    </div>
                    <h3>Add Schedule</h3>
                    <p>Create new time slots for patients to book appointments.</p>
                </a>
                
                <!-- My Schedules Card -->
                <a href="doctor_schedule_view.php" class="nav-card">
                    <div class="card-icon" style="background: linear-gradient(135deg, #17a2b8, #0dcaf0);">
                        <i class="bi bi-calendar-week"></i>
                    </div>
                    <h3>My Schedules</h3>
                    <p>View and manage your existing schedule and availability.</p>
                </a>
                
                <!-- Appointments Card -->
                <a href="doctor_appointments.php" class="nav-card">
                    <div class="card-icon" style="background: linear-gradient(135deg, #28a745, #20c997);">
                        <i class="bi bi-calendar-check"></i>
                    </div>
                    <h3>Appointments</h3>
                    <p>View and manage all your patient appointments.</p>
                </a>
                
                <!-- My Profile Card -->
                <a href="doctor_profile_view.php" class="nav-card">
                    <div class="card-icon" style="background: linear-gradient(135deg, #6f42c1, #8540f5);">
                        <i class="bi bi-person-circle"></i>
                    </div>
                    <h3>My Profile</h3>
                    <p>View and update your professional information.</p>
                </a>
            </div>
            
        </div>
    </div>
</body>
</html>