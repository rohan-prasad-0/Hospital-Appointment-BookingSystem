<?php
session_start();
require_once "db_connection.php";

// Check if user is logged in as admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Get admin details
$admin_sql = "SELECT name FROM admin WHERE user_id = $user_id";
$admin_rs = $mysqli->query($admin_sql);
$admin = mysqli_fetch_assoc($admin_rs);
$admin_name = $admin['name'];

// Get statistics
$stats_sql = "SELECT 
    (SELECT COUNT(*) FROM doctor) as total_doctors,
    (SELECT COUNT(*) FROM receptionist) as total_receptionists,
    (SELECT COUNT(*) FROM patient) as total_patients,
    (SELECT COUNT(*) FROM appointment WHERE status = 'Booked') as total_appointments,
    (SELECT COUNT(*) FROM appointment WHERE status = 'Completed') as completed_appointments,
    (SELECT COUNT(*) FROM appointment WHERE status = 'Cancelled') as cancelled_appointments,
    (SELECT COUNT(*) FROM user) as total_users";
$stats_rs = $mysqli->query($stats_sql);
$stats = mysqli_fetch_assoc($stats_rs);

// Get today's appointments
$today_sql = "SELECT a.*, d.name as doctor_name, p.name as patient_name, ds.available_date, ds.time_slot
              FROM appointment a
              JOIN doctor d ON a.doctor_id = d.doctor_id
              JOIN patient p ON a.patient_id = p.patient_id
              JOIN doctor_schedule ds ON a.sch_id = ds.sch_id
              WHERE ds.available_date = CURDATE()
              ORDER BY ds.time_slot ASC";
$today_rs = $mysqli->query($today_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - ABC Hospital</title>
    
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
        <?php include('sidebar_admin.php'); ?>
        
        <div class="main-content">
            <div class="page-header d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <button class="menu-btn d-lg-none me-3" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarOffcanvas">
                        <i class="bi bi-list"></i>
                    </button>
                    <div>
                        <h4 class="fw-bold">
                            Admin Dashboard
                        </h4>
                    </div>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="row g-4 mb-4">
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="stat-value"><?= $stats['total_doctors'] ?></div>
                                <div class="stat-label">Total Doctors</div>
                            </div>
                            <div class="stat-icon">
                                <i class="bi bi-person-badge"></i>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="stat-value"><?= $stats['total_receptionists'] ?></div>
                                <div class="stat-label">Receptionists</div>
                            </div>
                            <div class="stat-icon" style="background: linear-gradient(135deg, #28a745, #20c997);">
                                <i class="bi bi-person-check"></i>
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
                            <div class="stat-icon" style="background: linear-gradient(135deg, #17a2b8, #0dcaf0);">
                                <i class="bi bi-people"></i>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="stat-value"><?= $stats['total_appointments'] ?></div>
                                <div class="stat-label">Active Appointments</div>
                            </div>
                            <div class="stat-icon" style="background: linear-gradient(135deg, #ffc107, #fd7e14);">
                                <i class="bi bi-calendar-check"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4 mb-4">
                <div class="col-md-4">
                    <div class="stat-card">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="stat-value text-success"><?= $stats['completed_appointments'] ?></div>
                                <div class="stat-label">Completed Appointments</div>
                            </div>
                            <div class="stat-icon" style="background: linear-gradient(135deg, #28a745, #20c997);">
                                <i class="bi bi-check-circle"></i>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="stat-card">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="stat-value text-danger"><?= $stats['cancelled_appointments'] ?></div>
                                <div class="stat-label">Cancelled Appointments</div>
                            </div>
                            <div class="stat-icon" style="background: linear-gradient(135deg, #dc3545, #c82333);">
                                <i class="bi bi-x-circle"></i>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="stat-card">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="stat-value"><?= $stats['total_users'] ?></div>
                                <div class="stat-label">System Users</div>
                            </div>
                            <div class="stat-icon" style="background: linear-gradient(135deg, #6f42c1, #8540f5);">
                                <i class="bi bi-people-fill"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row g-4 mb-4">
                <div class="col-md-3 col-6">
                    <a href="admin_add_user.php" class="quick-action-card">
                        <div class="quick-action-icon">
                            <i class="bi bi-person-plus"></i>
                        </div>
                        <h6 class="quick-action-title">Add User</h6>
                        <p class="quick-action-desc">Create new staff account</p>
                    </a>
                </div>
                
                <div class="col-md-3 col-6">
                    <a href="admin_view_doctors.php" class="quick-action-card">
                        <div class="quick-action-icon">
                            <i class="bi bi-person-badge"></i>
                        </div>
                        <h6 class="quick-action-title">Manage Doctors</h6>
                        <p class="quick-action-desc">View & edit doctors</p>
                    </a>
                </div>
                
                <div class="col-md-3 col-6">
                    <a href="admin_view_receptionists.php" class="quick-action-card">
                        <div class="quick-action-icon">
                            <i class="bi bi-person-check"></i>
                        </div>
                        <h6 class="quick-action-title">Receptionists</h6>
                        <p class="quick-action-desc">Manage reception staff</p>
                    </a>
                </div>
                
                <div class="col-md-3 col-6">
                    <a href="admin_view_appointments.php" class="quick-action-card">
                        <div class="quick-action-icon">
                            <i class="bi bi-calendar-check"></i>
                        </div>
                        <h6 class="quick-action-title">Appointments</h6>
                        <p class="quick-action-desc">View all appointments</p>
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>