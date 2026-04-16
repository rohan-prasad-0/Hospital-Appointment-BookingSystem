<?php
session_start();
require_once "db_connection.php";

// Check if user is logged in as admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header("Location: login_1.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Get admin name
$admin_sql = "SELECT name FROM admin WHERE user_id = $user_id";
$admin_rs = $mysqli->query($admin_sql);
$admin = mysqli_fetch_assoc($admin_rs);
$admin_name = $admin['name'];

// Get all receptionists
$receptionists_sql = "SELECT r.*, u.email 
                      FROM receptionist r 
                      JOIN user u ON r.user_id = u.user_id 
                      ORDER BY r.name ASC";
$receptionists_rs = $mysqli->query($receptionists_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Receptionists - ABC Hospital</title>
    
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
                            <i class="bi bi-person-check text-primary me-2"></i>
                            Manage Receptionists
                        </h4>
                    </div>
                </div>
                <div>
                    <a href="admin_add_user.php" class="btn btn-primary">
                        <i class="bi bi-person-plus me-2"></i>Add New Receptionist
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

            <!-- Receptionists List -->
            <?php if (mysqli_num_rows($receptionists_rs) > 0): ?>
                <div class="row g-4">
                    <?php while ($receptionist = mysqli_fetch_assoc($receptionists_rs)): ?>
                        <div class="col-md-6 col-lg-4">
                            <div class="staff-card">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="staff-avatar me-3">
                                        <i class="bi bi-person-fill"></i>
                                    </div>
                                    <div>
                                        <h5 class="fw-bold mb-1"><?= $receptionist['name'] ?></h5>
                                        <span class="badge bg-primary">Receptionist</span>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="bi bi-envelope text-primary me-2"></i>
                                        <small class="text-muted"><?= $receptionist['email'] ?></small>
                                    </div>
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="bi bi-telephone text-primary me-2"></i>
                                        <small class="text-muted"><?= $receptionist['phone'] ?? 'N/A' ?></small>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-gender-<?= strtolower($receptionist['gender'] ?? 'male') ?> text-primary me-2"></i>
                                        <small class="text-muted"><?= $receptionist['gender'] ?? 'N/A' ?></small>
                                    </div>
                                </div>
                                
                                <div class="d-flex gap-2">
                                    <a href="admin_edit_receptionist.php?id=<?= $receptionist['recep_id'] ?>" 
                                       class="btn-action btn-edit">
                                        <i class="bi bi-pencil me-1"></i>Edit
                                    </a>
                                    <button type="button" class="btn-action btn-delete" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#deleteReceptionistModal<?= $receptionist['recep_id'] ?>">
                                        <i class="bi bi-trash me-1"></i>Delete
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Delete Receptionist Modal -->
                        <div class="modal fade" id="deleteReceptionistModal<?= $receptionist['recep_id'] ?>" tabindex="-1">
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
                                        <p>Are you sure you want to delete <strong><?= $receptionist['name'] ?></strong>?</p>
                                        <div class="bg-light p-3 rounded-3">
                                            <div class="d-flex align-items-center mb-2">
                                                <i class="bi bi-envelope text-danger me-2"></i>
                                                <span><?= $receptionist['email'] ?></span>
                                            </div>
                                            <div class="d-flex align-items-center mb-2">
                                                <i class="bi bi-telephone text-danger me-2"></i>
                                                <span><?= $receptionist['phone'] ?? 'N/A' ?></span>
                                            </div>
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-gender-<?= strtolower($receptionist['gender'] ?? 'male') ?> text-danger me-2"></i>
                                                <span><?= $receptionist['gender'] ?? 'N/A' ?></span>
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
                                        <a href="admin_delete_receptionist.php?receptionist_id=<?= $receptionist['recep_id'] ?>" 
                                           class="btn btn-danger">
                                            <i class="bi bi-trash me-1"></i>Delete Receptionist
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <!-- No Results Found -->
                <div class="empty-state">
                    <i class="bi bi-person-check display-1 text-muted"></i>
                    <h4 class="text-muted mt-3">No Receptionists Found</h4>
                    <p class="text-muted">No receptionists have been added to the system yet.</p>
                    <a href="admin_add_user.php" class="btn btn-primary">
                        <i class="bi bi-person-plus me-2"></i>Add First Receptionist
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>