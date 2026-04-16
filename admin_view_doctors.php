<?php
session_start();
require_once "db_connection.php";

// Check if user is logged in as admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Get admin name
$admin_sql = "SELECT name FROM admin WHERE user_id = $user_id";
$admin_rs = $mysqli->query($admin_sql);
$admin = mysqli_fetch_assoc($admin_rs);
$admin_name = $admin['name'];

$doctors_sql = "SELECT d.*, s.sp_name, u.email 
                FROM doctor d 
                JOIN specialization s ON d.sp_id = s.sp_id 
                JOIN user u ON d.user_id = u.user_id 
                ORDER BY d.name ASC";
$doctors_rs = $mysqli->query($doctors_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Doctors - ABC Hospital</title>
    
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
                            <i class="bi bi-person-badge text-primary me-2"></i>
                            Manage Doctors
                        </h4>
                    </div>
                </div>
                <div>
                    <a href="admin_add_user.php" class="btn btn-primary">
                        <i class="bi bi-person-plus me-2"></i>Add New Doctor
                    </a>
                </div>
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

            <!-- Doctors List -->
            <?php if (mysqli_num_rows($doctors_rs) > 0): ?>
                <div class="row g-4">
                    <?php while ($doctor = mysqli_fetch_assoc($doctors_rs)): 
                        // Check if doctor has appointments
                        $appointment_check = $mysqli->query("SELECT COUNT(*) as total FROM appointment WHERE doctor_id = {$doctor['doctor_id']}");
                        $appointment_count = $appointment_check->fetch_assoc()['total'];
                        
                        // Check if doctor has schedules
                        $schedule_check = $mysqli->query("SELECT COUNT(*) as total FROM doctor_schedule WHERE doctor_id = {$doctor['doctor_id']}");
                        $schedule_count = $schedule_check->fetch_assoc()['total'];
                        
                        // Determine if doctor can be deleted (no appointments AND no schedules)
                        $can_delete = ($appointment_count == 0 && $schedule_count == 0);
                        $delete_disabled_reason = "";
                        
                        if (!$can_delete) {
                            if ($appointment_count > 0 && $schedule_count > 0) {
                                $delete_disabled_reason = "Has $appointment_count appointment(s) and $schedule_count schedule(s)";
                            } elseif ($appointment_count > 0) {
                                $delete_disabled_reason = "Has $appointment_count appointment(s)";
                            } else {
                                $delete_disabled_reason = "Has $schedule_count schedule(s)";
                            }
                        }
                    ?>
                        <div class="col-md-6 col-lg-4">
                            <div class="stat-card">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="doctor-avatar-sm me-3">
                                        <i class="bi bi-person-fill"></i>
                                    </div>
                                    <div>
                                        <h5 class="fw-bold mb-1">Dr. <?= htmlspecialchars($doctor['name']) ?></h5>
                                        <span class="specialization-badge">
                                            <i class="bi bi-stethoscope me-1"></i><?= htmlspecialchars($doctor['sp_name']) ?>
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="bi bi-envelope text-primary me-2"></i>
                                        <small class="text-muted"><?= htmlspecialchars($doctor['email']) ?></small>
                                    </div>
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="bi bi-telephone text-primary me-2"></i>
                                        <small class="text-muted"><?= htmlspecialchars($doctor['phone'] ?? 'N/A') ?></small>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-gender-<?= strtolower($doctor['gender'] ?? 'male') ?> text-primary me-2"></i>
                                        <small class="text-muted"><?= htmlspecialchars($doctor['gender'] ?? 'N/A') ?></small>
                                    </div>
                                </div>
                                
                                <!-- Appointment and Schedule Status Badges -->
                                <div class="mb-3 d-flex gap-2">
                                    <?php if ($appointment_count > 0): ?>
                                        <span class="badge bg-warning text-dark">
                                            <i class="bi bi-calendar-event me-1"></i><?= $appointment_count ?> Appointment(s)
                                        </span>
                                    <?php endif; ?>
                                    <?php if ($schedule_count > 0): ?>
                                        <span class="badge bg-info text-dark">
                                            <i class="bi bi-clock-history me-1"></i><?= $schedule_count ?> Schedule(s)
                                        </span>
                                    <?php endif; ?>
                                    <?php if ($appointment_count == 0 && $schedule_count == 0): ?>
                                        <span class="badge bg-success">
                                            <i class="bi bi-check-circle me-1"></i>Can Delete
                                        </span>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="d-flex gap-2">
                                    <a href="admin_edit_doctor.php?id=<?= $doctor['doctor_id'] ?>" 
                                       class="btn-action btn-edit">
                                        <i class="bi bi-pencil me-1"></i>Edit
                                    </a>
                                    <a href="admin_view_appointments.php?doctor_id=<?= $doctor['doctor_id'] ?>" 
                                       class="btn-action btn-view">
                                        <i class="bi bi-calendar-check me-1"></i>Appointments
                                    </a>
                                    <a href="admin_view_schedules.php?doctor_id=<?= $doctor['doctor_id'] ?>" 
                                       class="btn-action btn-info">
                                        <i class="bi bi-clock me-1"></i>Schedules
                                    </a>
                                    <?php if ($can_delete): ?>
                                        <button type="button" class="btn-action btn-delete" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#deleteDoctorModal<?= $doctor['doctor_id'] ?>">
                                            <i class="bi bi-trash me-1"></i>Delete
                                        </button>
                                    <?php else: ?>
                                        <button class="btn-action btn-delete" disabled 
                                                title="<?= htmlspecialchars($delete_disabled_reason) ?>">
                                            <i class="bi bi-trash me-1"></i>Delete
                                        </button>
                                    <?php endif; ?>
                                </div>
                                
                                <?php if (!$can_delete): ?>
                                    <small class="text-danger d-block mt-2">
                                        <i class="bi bi-info-circle me-1"></i>
                                        <?= htmlspecialchars($delete_disabled_reason) ?>
                                    </small>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Delete Doctor Modal (only shown if doctor can be deleted) -->
                        <?php if ($can_delete): ?>
                        <div class="modal fade" id="deleteDoctorModal<?= $doctor['doctor_id'] ?>" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header bg-danger text-white">
                                        <h5 class="modal-title">
                                            <i class="bi bi-exclamation-triangle me-2"></i>
                                            Confirm Delete
                                        </h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Are you sure you want to delete <strong>Dr. <?= htmlspecialchars($doctor['name']) ?></strong>?</p>
                                        <div class="bg-light p-3 rounded-3">
                                            <div class="d-flex align-items-center mb-2">
                                                <i class="bi bi-envelope text-danger me-2"></i>
                                                <span><?= htmlspecialchars($doctor['email']) ?></span>
                                            </div>
                                            <div class="d-flex align-items-center mb-2">
                                                <i class="bi bi-stethoscope text-danger me-2"></i>
                                                <span><?= htmlspecialchars($doctor['sp_name']) ?></span>
                                            </div>
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-telephone text-danger me-2"></i>
                                                <span><?= htmlspecialchars($doctor['phone'] ?? 'N/A') ?></span>
                                            </div>
                                        </div>
                                        <p class="text-danger mt-3 mb-0">
                                            <i class="bi bi-exclamation-circle me-1"></i>
                                            This action cannot be undone.
                                        </p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                            <i class="bi bi-x me-1"></i>Cancel
                                        </button>
                                        <a href="admin_delete_doctor.php?doctor_id=<?= $doctor['doctor_id'] ?>" 
                                           class="btn btn-danger">
                                            <i class="bi bi-trash me-1"></i>Delete Doctor
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <!-- No Results Found -->
                <div class="empty-state">
                    <i class="bi bi-person-badge display-1 text-muted"></i>
                    <h4 class="text-muted mt-3">No Doctors Found</h4>
                    <p class="text-muted">No doctors have been added to the system yet.</p>
                    <a href="admin_add_user.php" class="btn btn-primary">
                        <i class="bi bi-person-plus me-2"></i>Add First Doctor
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>