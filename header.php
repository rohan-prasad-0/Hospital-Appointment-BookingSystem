<?php
session_start();
require_once('db_connection.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ABC Virtual Hospital - <?php echo $pageTitle ?? 'Healthcare System'; ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="style.css">
    
    <!-- Favicon -->
    <link rel="icon" href="images/favicon.png" type="image/png">
</head>
<body>
    <!-- Main Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <img src="images/main-logo.png" alt="ABC Virtual Hospital" style="height: 55px;">
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto"></ul>
                
                <div class="ms-3 d-flex">
                    <?php if (isset($_SESSION['email'])): ?>
                        <div class="dropdown">
                            <a href="#" class="btn btn-primary dropdown-toggle" id="userDropdown" 
                               data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user-circle me-2"></i><?= htmlspecialchars($_SESSION['name']) ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <li>
                                    <a class="dropdown-item" href="dashboard.php">
                                        <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="profile.php">
                                        <i class="fas fa-user me-2"></i>My Profile
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="appointments.php">
                                        <i class="fas fa-calendar-check me-2"></i>Appointments
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item text-danger" href="logout.php">
                                        <i class="fas fa-sign-out-alt me-2"></i>Logout
                                    </a>
                                </li>
                            </ul>
                        </div>
                    <?php else: ?>
                        <a href="login.php" class="btn btn-outline-primary me-2">
                            <i class="fas fa-sign-in-alt me-2"></i>Login
                        </a>
                        <a href="user_register_1.php" class="btn btn-primary">
                            <i class="fas fa-user-plus me-2"></i>Register
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>