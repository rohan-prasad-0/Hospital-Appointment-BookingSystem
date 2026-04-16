<?php
session_start();
require_once "db_connection.php";

// Check if user is logged in as doctor
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Doctor') {
    header("Location: login_1.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Get doctor details with specialization
$doctor_sql = "SELECT d.*, s.sp_name, u.email 
               FROM doctor d 
               JOIN specialization s ON d.sp_id = s.sp_id 
               JOIN user u ON d.user_id = u.user_id 
               WHERE d.user_id = $user_id";
$doctor_rs = $mysqli->query($doctor_sql);

if (mysqli_num_rows($doctor_rs) > 0) {
    $doctor = mysqli_fetch_assoc($doctor_rs);
} else {
    die("Doctor not found.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - ABC Hospital</title>
    
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
                            <i class="bi bi-person-circle text-primary me-2"></i>
                            My Profile
                        </h4>
                    </div>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="profile-card">
                        <!-- Profile Header -->
                        <div class="profile-header">
                            <div class="doctor-avatar-lg">
                                <i class="bi bi-person-fill"></i>
                            </div>
                            <h2 class="profile-name">Dr. <?= $doctor['name'] ?></h2>
                            <p class="profile-specialization">
                                <i class="bi bi-stethoscope me-2"></i><?= $doctor['sp_name'] ?>
                            </p>
                        </div>

                        <!-- Personal Information -->
                        <div class="info-section">
                            <div class="info-section-title">
                                <i class="bi bi-person-lines-fill"></i>
                                <h6>Personal Information</h6>
                            </div>
                            
                            <div class="info-item">
                                <span class="info-label">
                                    <i class="bi bi-envelope"></i> Email
                                </span>
                                <span class="info-value"><?= $doctor['email'] ?></span>
                            </div>
                            
                            <div class="info-item">
                                <span class="info-label">
                                    <i class="bi bi-telephone"></i> Phone
                                </span>
                                <span class="info-value"><?= $doctor['phone'] ?? 'Not provided' ?></span>
                            </div>
                            
                            <div class="info-item">
                                <span class="info-label">
                                    <i class="bi bi-gender-<?= strtolower($doctor['gender'] ?? 'male') ?>"></i> Gender
                                </span>
                                <span class="info-value"><?= $doctor['gender'] ?? 'Not specified' ?></span>
                            </div>
                        </div>

                        <!-- Professional Information -->
                        <div class="info-section">
                            <div class="info-section-title">
                                <i class="bi bi-briefcase"></i>
                                <h6>Professional Information</h6>
                            </div>
                            
                            <div class="info-item">
                                <span class="info-label">
                                    <i class="bi bi-stethoscope"></i> Specialization
                                </span>
                                <span class="info-value"><?= $doctor['sp_name'] ?></span>
                            </div>
                            
                            <?php if (!empty($doctor['qualification'])): ?>
                            <div class="info-item">
                                <span class="info-label">
                                    <i class="bi bi-award"></i> Qualification
                                </span>
                                <span class="info-value"><?= $doctor['qualification'] ?></span>
                            </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($doctor['experience'])): ?>
                            <div class="info-item">
                                <span class="info-label">
                                    <i class="bi bi-clock-history"></i> Experience
                                </span>
                                <span class="info-value"><?= $doctor['experience'] ?> years</span>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>