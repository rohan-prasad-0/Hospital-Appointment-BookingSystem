<?php
session_start();
require_once "db_connection.php";

// Check if user is logged in as admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header("Location: login_1.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Get admin details
$admin_sql = "SELECT a.*, u.email FROM admin a JOIN user u ON a.user_id = u.user_id WHERE a.user_id = $user_id";
$admin_rs = $mysqli->query($admin_sql);

if (mysqli_num_rows($admin_rs) > 0) {
    $admin = mysqli_fetch_assoc($admin_rs);
} else {
    die("Admin not found.");
}

// Handle form submission
$success = $error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $access_code = trim($_POST['access_code']);

    if (empty($name) || empty($email)) {
        $error = "Please fill all required fields.";
    } else {
        // Check if email already exists
        $check_email = $mysqli->query("SELECT user_id FROM user WHERE email = '$email' AND user_id != $user_id");

        if ($check_email->num_rows > 0) {
            $error = "Email already exists!";
        } else {
            // Update admin table
            $update_admin = "UPDATE admin SET name = '$name', phone = '$phone' WHERE user_id = $user_id";

            if ($mysqli->query($update_admin)) {
                // Update user table
                $update_user = "UPDATE user SET email = '$email'";
                if (!empty($access_code)) {
                    $hashed_access_code = password_hash($access_code, PASSWORD_DEFAULT);
                    $update_user .= ", access_code = '$hashed_access_code'";
                }
                $update_user .= " WHERE user_id = $user_id";

                if ($mysqli->query($update_user)) {
                    $success = "Profile updated successfully!";
                    // Refresh admin data
                    $admin_rs = $mysqli->query($admin_sql);
                    $admin = $admin_rs->fetch_assoc();
                } else {
                    $error = "Error updating user info: " . $mysqli->error;
                }
            } else {
                $error = "Error updating admin details: " . $mysqli->error;
            }
        }
    }
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
        <?php include('sidebar_admin.php'); ?>
        
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
                            <div class="admin-avatar-lg">
                                <i class="bi bi-person-fill"></i>
                            </div>
                            <h2 class="profile-name"><?= $admin['name'] ?></h2>
                            <p class="profile-role">System Administrator</p>
                        </div>

                        <!-- Status Messages -->
                        <?php if ($success): ?>
                            <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
                                <i class="bi bi-check-circle-fill me-2 fs-4"></i>
                                <div><?= $success ?></div>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($error): ?>
                            <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center" role="alert">
                                <i class="bi bi-exclamation-triangle-fill me-2 fs-4"></i>
                                <div><?= $error ?></div>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <form method="POST" action="">
                            <!-- Personal Information -->
                            <div class="form-section">
                                <div class="form-section-title">
                                    <i class="bi bi-person-lines-fill"></i>
                                    <h6>Personal Information</h6>
                                </div>
                                
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <label class="form-label">
                                            <i class="bi bi-person text-primary me-1"></i>Full Name <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" class="form-control" name="name" value="<?= $admin['name'] ?>" required>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label class="form-label">
                                            <i class="bi bi-envelope text-primary me-1"></i>Email <span class="text-danger">*</span>
                                        </label>
                                        <input type="email" class="form-control" name="email" value="<?= $admin['email'] ?>" required>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label class="form-label">
                                            <i class="bi bi-telephone text-primary me-1"></i>Phone Number
                                        </label>
                                        <input type="text" class="form-control" name="phone" value="<?= $admin['phone'] ?? '' ?>">
                                    </div>
                                </div>
                            </div>

                            <div class="form-section">
                                <div class="form-section-title">
                                    <i class="bi bi-shield-lock"></i>
                                    <h6>Security</h6>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="form-label">
                                            <i class="bi bi-key text-primary me-1"></i>New Password
                                        </label>
                                        <input type="password" class="form-control" name="access_code" 
                                               placeholder="Leave blank to keep current password">
                                    </div>
                                </div>
                            </div>

                            <div class="text-center mt-4">
                                <button type="submit" class="btn-update me-2">
                                    <i class="bi bi-check-circle me-2"></i>Update Profile
                                </button>
                                <a href="admin_dashboard.php" class="btn-back">
                                    <i class="bi bi-arrow-left me-2"></i>Back to Dashboard
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>