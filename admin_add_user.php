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

// Get specializations for doctor dropdown
$spec_sql = "SELECT * FROM specialization ORDER BY sp_name";
$spec_rs = $mysqli->query($spec_sql);

// Check for status messages from processing page
$status = $_GET['status'] ?? '';
$message = $_GET['message'] ?? '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add User - ABC Hospital</title>
    
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
                            Add New User
                        </h4>
                    </div>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="form-card">
                        <div class="form-header">
                            <h5>User Details</h5>
                        </div>
                        
                        <!-- Status Messages -->
                        <?php if ($status == 'success'): ?>
                            <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
                                <i class="bi bi-check-circle-fill me-2 fs-4"></i>
                                <div><?= htmlspecialchars($message) ?></div>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($status == 'error'): ?>
                            <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center" role="alert">
                                <i class="bi bi-exclamation-triangle-fill me-2 fs-4"></i>
                                <div><?= htmlspecialchars($message) ?></div>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <form method="POST" action="admin_add_user_1.php">
                            <div class="row g-4">
                                <!-- User Type -->
                                <div class="col-md-6">
                                    <label class="form-label">
                                        <i class="bi bi-person-badge text-primary me-1"></i>User Type <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select" id="user_type" name="user_type" required onchange="toggleDoctorFields()">
                                        <option value="">Select User Type</option>
                                        <option value="Doctor">Doctor</option>
                                        <option value="Receptionist">Receptionist</option>
                                    </select>
                                </div>

                                <!-- Name -->
                                <div class="col-md-6">
                                    <label class="form-label">
                                        <i class="bi bi-person text-primary me-1"></i>Full Name <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control" name="name" placeholder="Enter full name" required>
                                </div>

                                <!-- Email -->
                                <div class="col-md-6">
                                    <label class="form-label">
                                        <i class="bi bi-envelope text-primary me-1"></i>Email <span class="text-danger">*</span>
                                    </label>
                                    <input type="email" class="form-control" name="email" placeholder="Enter email address" required>
                                </div>

                                <!-- Phone -->
                                <div class="col-md-6">
                                    <label class="form-label">
                                        <i class="bi bi-telephone text-primary me-1"></i>Phone Number
                                    </label>
                                    <input type="text" class="form-control" name="phone" placeholder="Enter phone number">
                                </div>

                                <!-- Gender -->
                                <div class="col-md-6">
                                    <label class="form-label">
                                        <i class="bi bi-gender-ambiguous text-primary me-1"></i>Gender
                                    </label>
                                    <select class="form-select" name="gender">
                                        <option value="">Select Gender</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                    </select>
                                </div>

                                <!-- Access Code -->
                                <div class="col-md-6">
                                    <label class="form-label">
                                        <i class="bi bi-lock text-primary me-1"></i>Password <span class="text-danger">*</span>
                                    </label>
                                    <input type="password" class="form-control" name="access_code" placeholder="Enter password" required>
                                </div>

                                <!-- Doctor Specific Fields -->
                                <div class="col-12" id="doctorFields" style="display: none;">
                                    <div class="doctor-fields">
                                        <h6 class="fw-bold mb-3"><i class="bi bi-stethoscope text-primary me-2"></i>Doctor Information</h6>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label class="form-label">
                                                    <i class="bi bi-clipboard2-pulse text-primary me-1"></i>Specialization <span class="text-danger">*</span>
                                                </label>
                                                <select class="form-select" name="sp_id" id="sp_id">
                                                    <option value="">Select Specialization</option>
                                                    <?php while ($spec = mysqli_fetch_assoc($spec_rs)): ?>
                                                        <option value="<?= $spec['sp_id'] ?>"><?= $spec['sp_name'] ?></option>
                                                    <?php endwhile; ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Submit Buttons -->
                                <div class="col-12 text-center mt-4">
                                    <button type="submit" class="btn-submit me-2">
                                        <i class="bi bi-check-circle me-2"></i>Add User
                                    </button>
                                    <a href="admin_dashboard.php" class="btn-back">
                                        <i class="bi bi-arrow-left me-2"></i>Cancel
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    function toggleDoctorFields() {
        const userType = document.getElementById('user_type').value;
        const doctorFields = document.getElementById('doctorFields');
        const spSelect = document.getElementById('sp_id');
        
        if (userType === 'Doctor') {
            doctorFields.style.display = 'block';
            spSelect.required = true;
        } else {
            doctorFields.style.display = 'none';
            spSelect.required = false;
        }
    }

    const urlParams = new URLSearchParams(window.location.search);
    const selectedType = urlParams.get('type');
    if (selectedType) {
        document.getElementById('user_type').value = selectedType;
        toggleDoctorFields();
    }
    </script>
</body>
</html>