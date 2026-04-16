<?php
session_start();
require_once "db_connection.php";

// Check if user is logged in as receptionist
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Receptionist') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Get receptionist details
$rec_sql = "SELECT recep_id, name FROM receptionist WHERE user_id = $user_id";
$rec_rs = $mysqli->query($rec_sql);
$rec_row = mysqli_fetch_assoc($rec_rs);
$recep_id = $rec_row['recep_id'];
$receptionist_name = $rec_row['name'];

// Get statistics for dashboard
$stats_sql = "SELECT 
    (SELECT COUNT(*) FROM appointment WHERE status = 'Booked') as total_appointments,
    (SELECT COUNT(*) FROM doctor) as total_doctors,
    (SELECT COUNT(*) FROM patient) as total_patients,
    (SELECT COUNT(*) FROM appointment WHERE status = 'Booked' AND DATE(created_at) = CURDATE()) as today_appointments";
$stats_rs = $mysqli->query($stats_sql);
$stats = mysqli_fetch_assoc($stats_rs);

// Get today's appointments
$today_sql = "SELECT a.*, d.name as doctor_name, p.name as patient_name, ds.time_slot
              FROM appointment a
              JOIN doctor d ON a.doctor_id = d.doctor_id
              JOIN patient p ON a.patient_id = p.patient_id
              JOIN doctor_schedule ds ON a.sch_id = ds.sch_id
              WHERE ds.available_date = CURDATE() AND a.status = 'Booked'
              ORDER BY ds.time_slot ASC
              LIMIT 5";
$today_rs = $mysqli->query($today_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receptionist Dashboard - ABC Hospital</title>
    
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
        <?php include('sidebar_receptionist.php'); ?>
        
        <div class="main-content">
            <div class="page-header d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <button class="menu-btn d-lg-none me-3" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarOffcanvas">
                        <i class="bi bi-list"></i>
                    </button>
                    <div>
                        <h4 class="fw-bold">
                            <i class="bi bi-grid-fill text-primary me-2"></i>
                            Receptionist Dashboard
                        </h4>
                    </div>
                </div>
                <div>
                </div>
            </div>

            <!-- Stat Cards -->
            <div class="row g-4 mb-4">
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="stat-value"><?= $stats['total_appointments'] ?></div>
                                <div class="stat-label">Total Appointments</div>
                            </div>
                            <div class="stat-icon">
                                <i class="bi bi-calendar-check"></i>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="stat-value"><?= $stats['today_appointments'] ?></div>
                                <div class="stat-label">Today's Appointments</div>
                            </div>
                            <div class="stat-icon" style="background: linear-gradient(135deg, #28a745, #20c997);">
                                <i class="bi bi-calendar-day"></i>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="stat-value"><?= $stats['total_doctors'] ?></div>
                                <div class="stat-label">Total Doctors</div>
                            </div>
                            <div class="stat-icon" style="background: linear-gradient(135deg, #17a2b8, #0dcaf0);">
                                <i class="bi bi-person-badge"></i>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="stat-value"><?= $stats['total_patients'] ?></div>
                                <div class="stat-label">Total Patients</div>
                            </div>
                            <div class="stat-icon" style="background: linear-gradient(135deg, #ffc107, #fd7e14);">
                                <i class="bi bi-people"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <h5 class="fw-bold mb-4">Quick Actions</h5>
            <div class="row g-4 mb-4">
                <div class="col-md-4">
                    <a href="receptionist_doctor_view.php" class="quick-action-card">
                        <div class="quick-action-icon">
                            <i class="bi bi-search"></i>
                        </div>
                        <h6 class="fw-bold mb-2">Search Doctors</h6>
                        <p class="text-muted small mb-0">Find doctors and view their schedules</p>
                    </a>
                </div>
                
                <div class="col-md-4">
                    <a href="receptionist_view_appointments.php?filter=today" class="quick-action-card">
                        <div class="quick-action-icon">
                            <i class="bi bi-calendar-check"></i>
                        </div>
                        <h6 class="fw-bold mb-2">Today's Appointments</h6>
                        <p class="text-muted small mb-0">View all appointments for today</p>
                    </a>
                </div>
                
                <div class="col-md-4">
                    <a href="receptionist_view_appointments.php" class="quick-action-card">
                        <div class="quick-action-icon">
                            <i class="bi bi-clock-history"></i>
                        </div>
                        <h6 class="fw-bold mb-2">All Appointments</h6>
                        <p class="text-muted small mb-0">View and manage all appointments</p>
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>