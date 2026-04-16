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
$admin_sql = "SELECT name FROM admin WHERE user_id = $user_id";
$admin_rs = $mysqli->query($admin_sql);
$admin = mysqli_fetch_assoc($admin_rs);
$admin_name = $admin['name'];

// Get receptionist ID
$recep_id = $_GET['id'] ?? '';
if (empty($recep_id)) {
    header("Location: admin_view_receptionists.php");
    exit();
}

// Get receptionist details
$receptionist_sql = "SELECT r.*, u.email FROM receptionist r JOIN user u ON r.user_id = u.user_id WHERE r.recep_id = $recep_id";
$receptionist_rs = $mysqli->query($receptionist_sql);

if (mysqli_num_rows($receptionist_rs) === 0) {
    header("Location: admin_view_receptionists.php");
    exit();
}

$receptionist = mysqli_fetch_assoc($receptionist_rs);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Receptionist - ABC Hospital</title>
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.css">
    <script src="js/bootstrap.bundle.js"></script>
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="style.css">
    
    <style>
        .form-card {
            background: white;
            border-radius: 20px;
            padding: 30px;
            box-shadow: var(--shadow-sm);
        }
        
        .form-header {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            padding: 20px 30px;
            border-radius: 15px 15px 0 0;
            margin: -30px -30px 30px -30px;
        }
        
        .receptionist-avatar {
            width: 80px;
            height: 80px;
            background: var(--primary-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            border: 3px solid white;
        }
        
        .receptionist-avatar i {
            font-size: 40px;
            color: white;
        }
        
        .form-label {
            font-weight: 500;
            color: var(--dark-color);
            margin-bottom: 8px;
        }
        
        .form-control, .form-select {
            border: 2px solid #eef2f6;
            border-radius: 12px;
            padding: 12px 15px;
            transition: all 0.3s ease;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(42, 92, 143, 0.1);
        }
        
        .btn-update {
            background: var(--success-color);
            color: white;
            padding: 12px 30px;
            border-radius: 12px;
            font-weight: 500;
            border: none;
            transition: all 0.3s ease;
        }
        
        .btn-update:hover {
            background: #218838;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.3);
        }
        
        .btn-back {
            background: var(--gray-light);
            color: var(--dark-color);
            padding: 12px 30px;
            border-radius: 12px;
            font-weight: 500;
            text-decoration: none;
            border: 1px solid #dee2e6;
            transition: all 0.3s ease;
        }
        
        .btn-back:hover {
            background: #e9ecef;
            transform: translateY(-2px);
        }
        
        /* Mobile menu button */
        .menu-btn {
            display: none;
            width: 45px;
            height: 45px;
            background: var(--gray-light);
            border: none;
            border-radius: 12px;
            color: var(--primary-color);
            font-size: 1.5rem;
            margin-right: 15px;
        }
        
        .menu-btn:hover {
            background: var(--primary-color);
            color: white;
        }
        
        @media (max-width: 992px) {
            .menu-btn {
                display: block;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard-wrapper">
        <?php include('sidebar_admin.php'); ?>
        
        <div class="main-content">
            <!-- Page Header with Mobile Menu Button -->
            <div class="page-header d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <button class="menu-btn d-lg-none me-3" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarOffcanvas">
                        <i class="bi bi-list"></i>
                    </button>
                    <div>
                        <h4 class="fw-bold">
                            <i class="bi bi-pencil-square text-primary me-2"></i>
                            Edit Receptionist
                        </h4>
                        <p class="text-muted mb-0">Update receptionist information</p>
                    </div>
                </div>
                <div>
                    <span class="badge bg-primary px-3 py-2 rounded-pill">
                        <i class="bi bi-person-check me-2"></i>ID: <?= $recep_id ?>
                    </span>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="form-card">
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

                        <!-- Receptionist Avatar -->
                        <div class="text-center mb-4">
                            <div class="receptionist-avatar">
                                <i class="bi bi-person-fill"></i>
                            </div>
                            <h5 class="mb-0"><?= $receptionist['name'] ?></h5>
                            <small class="text-muted">Receptionist</small>
                        </div>

                        <form method="POST" action="admin_edit_receptionist_1.php">
                            <input type="hidden" name="recep_id" value="<?= $recep_id ?>">
                            <input type="hidden" name="user_id" value="<?= $receptionist['user_id'] ?>">
                            
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="form-label">
                                        <i class="bi bi-person text-primary me-1"></i>Full Name <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control" name="name" 
                                           value="<?= $receptionist['name'] ?>" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">
                                        <i class="bi bi-envelope text-primary me-1"></i>Email <span class="text-danger">*</span>
                                    </label>
                                    <input type="email" class="form-control" name="email" 
                                           value="<?= $receptionist['email'] ?>" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">
                                        <i class="bi bi-telephone text-primary me-1"></i>Phone
                                    </label>
                                    <input type="text" class="form-control" name="phone" 
                                           value="<?= $receptionist['phone'] ?? '' ?>">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">
                                        <i class="bi bi-gender-ambiguous text-primary me-1"></i>Gender
                                    </label>
                                    <select class="form-select" name="gender">
                                        <option value="">Select Gender</option>
                                        <option value="Male" <?= ($receptionist['gender'] == 'Male') ? 'selected' : '' ?>>Male</option>
                                        <option value="Female" <?= ($receptionist['gender'] == 'Female') ? 'selected' : '' ?>>Female</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">
                                        <i class="bi bi-key text-primary me-1"></i>New Password
                                    </label>
                                    <input type="password" class="form-control" name="access_code" 
                                           placeholder="Leave blank to keep current">
                                </div>

                                <div class="col-12 text-center mt-4">
                                    <button type="submit" class="btn-update me-2">
                                        <i class="bi bi-check-circle me-2"></i>Update Receptionist
                                    </button>
                                    <a href="admin_view_receptionists.php" class="btn-back">
                                        <i class="bi bi-arrow-left me-2"></i>Back to Receptionists
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