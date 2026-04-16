<?php
session_start();
require_once('db_connection.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ABC Virtual Hospital - <?php echo $pageTitle ?? 'Authentication'; ?></title>
    
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
    <!-- Simple Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <img src="images/main-logo.png" alt="ABC Virtual Hospital" style="height: 55px;">
            </a>
            <a href="index.php" class="btn btn-outline-primary">
                <i class="fas fa-home me-2"></i>Back to Home
            </a>
        </div>
    </nav>