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
$rec_sql = "SELECT r.*, u.email
           FROM receptionist r 
           JOIN user u ON r.user_id = u.user_id 
           WHERE r.user_id = $user_id";
$rec_rs = $mysqli->query($rec_sql);

if (mysqli_num_rows($rec_rs) > 0) {
    $receptionist = mysqli_fetch_assoc($rec_rs);
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
        <?php include('sidebar_receptionist.php'); ?>
        
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
                            <div class="profile-avatar">
                                <i class="bi bi-person-fill"></i>
                            </div>
                            <h2 class="profile-name"><?= $receptionist['name'] ?></h2>
                            <p class="profile-role">Receptionist</p>
                        </div>

                        <!-- Personal Information -->
                        <div class="info-section">
                            <div class="info-title">
                                <h6>Personal Information</h6>
                            </div>
                            
                            <div class="info-item">
                                <span class="info-label">
                                    <i class="bi bi-person"></i> Full Name
                                </span>
                                <span class="info-value"><?= $receptionist['name'] ?></span>
                            </div>
                            
                            <div class="info-item">
                                <span class="info-label">
                                    <i class="bi bi-envelope"></i> Email
                                </span>
                                <span class="info-value"><?= $receptionist['email'] ?></span>
                            </div>
                            
                            <div class="info-item">
                                <span class="info-label">
                                    <i class="bi bi-telephone"></i> Phone
                                </span>
                                <span class="info-value"><?= $receptionist['phone'] ?? 'Not provided' ?></span>
                            </div>
                            
                            <div class="info-item mb-0">
                                <span class="info-label">
                                    <i class="bi bi-gender-<?= strtolower($receptionist['gender'] ?? 'male') ?>"></i> Gender
                                </span>
                                <span class="info-value"><?= $receptionist['gender'] ?? 'Not specified' ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>