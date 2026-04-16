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
$doc_rs = $mysqli->query($doc_sql);
$doc_row = mysqli_fetch_assoc($doc_rs);
$doctor_id = $doc_row['doctor_id'];
$doctor_name = $doc_row['name'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Schedule - ABC Hospital</title>
    
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
        <?php include('sidebar_doctor.php'); ?>
        
        <div class="main-content">
            <div class="page-header d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <button class="menu-btn d-lg-none me-3" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarOffcanvas">
                        <i class="bi bi-list"></i>
                    </button>
                    <div>
                        <h4 class="fw-bold">
                            <i class="bi bi-plus-circle text-primary me-2"></i>
                            Add New Schedule
                        </h4>
                    </div>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="form-card">
                        <div class="form-header">
                            <h5><i class="bi bi-calendar-plus me-2"></i>Schedule Details</h5>
                        </div>
                        
                        <!-- Status Messages -->
                        <?php if (isset($_GET['success'])): ?>
                            <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
                                <i class="bi bi-check-circle-fill me-2 fs-4"></i>
                                <div><?= htmlspecialchars($_GET['success']) ?></div>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (isset($_GET['error'])): ?>
                            <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center" role="alert">
                                <i class="bi bi-exclamation-triangle-fill me-2 fs-4"></i>
                                <div><?= htmlspecialchars($_GET['error']) ?></div>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <form method="POST" action="doctor_schedule_add.php">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label for="available_date" class="form-label">
                                        <i class="bi bi-calendar-date me-1 text-primary"></i>Date
                                    </label>
                                    <input type="date" class="form-control" id="available_date" name="available_date" 
                                           min="<?= date('Y-m-d') ?>" required>
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="time_slot" class="form-label">
                                        <i class="bi bi-clock me-1 text-primary"></i>Time Slot
                                    </label>
                                    <input type="time" class="form-control" id="time_slot" name="time_slot" required>
                                </div>
                                
                                <div class="col-md-12">
                                    <label for="max_patient" class="form-label">
                                        <i class="bi bi-people me-1 text-primary"></i>Maximum Patients
                                    </label>
                                    <input type="number" class="form-control" id="max_patient" name="max_patient" 
                                           min="1" max="50" placeholder="Enter maximum number of patients" required>
                                </div>
                                
                                <div class="col-12 text-center mt-4">
                                    <button type="submit" class="btn-submit me-2">
                                        <i class="bi bi-check-circle me-2"></i>Add Time Slot
                                    </button>
                                    <a href="doctor_schedule_view.php" class="btn-back">
                                        <i class="bi bi-arrow-left me-2"></i>View Schedules
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>