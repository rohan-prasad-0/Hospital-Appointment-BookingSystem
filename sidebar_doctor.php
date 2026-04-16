<?php 
$current_page = basename($_SERVER['PHP_SELF']); 
$user_id = $_SESSION['user_id'] ?? 0;

// Get pending appointments count for badge
$pending_count = 0;
if ($user_id) {
    global $mysqli;
    $doctor_sql = "SELECT doctor_id FROM doctor WHERE user_id = $user_id";
    $doctor_rs = $mysqli->query($doctor_sql);
    if ($doctor_rs && $doctor_rs->num_rows > 0) {
        $doctor_row = $doctor_rs->fetch_assoc();
        $doctor_id = $doctor_row['doctor_id'];
        
        $pending_sql = "SELECT COUNT(*) as total FROM appointment a 
                       JOIN doctor_schedule ds ON a.sch_id = ds.sch_id 
                       WHERE a.doctor_id = $doctor_id
                       AND a.status = 'Booked' 
                       AND ds.available_date >= CURDATE()";
        $pending_rs = $mysqli->query($pending_sql);
        $pending_count = $pending_rs->fetch_assoc()['total'];
    }
}
?>

<!-- Desktop Sidebar -->
<div class="sidebar d-flex flex-column">
    <!-- Hospital Logo -->
    <div class="sidebar-header text-center py-4">
        <h5 class="text-white mb-0">Doctor Portal</h5>
        <span class="badge bg-light text-primary mt-2 px-3 py-1 rounded-pill">
            <i class="bi bi-person-badge me-1"></i><?= $_SESSION['role'] ?? 'Doctor' ?>
        </span>
    </div>
    
    <!-- User Info (Mobile visible) -->
    <div class="user-info-mobile d-lg-none p-3 border-bottom border-primary">
        <div class="d-flex align-items-center">
            <div class="avatar-circle bg-white bg-opacity-25 me-3">
                <span class="initials text-white fw-bold"><?= substr($_SESSION['name'] ?? 'D', 0, 1) ?></span>
            </div>
            <div>
                <h6 class="text-white mb-0">Dr. <?= $_SESSION['name'] ?? 'Doctor' ?></h6>
                <small class="text-white-50"><?= $_SESSION['email'] ?? '' ?></small>
            </div>
        </div>
    </div>
    
    <!-- Navigation Menu -->
    <ul class="nav-menu flex-column p-3">
        <li class="nav-item mb-2">
            <a href="doctor_dashboard.php" class="nav-link <?= ($current_page == 'doctor_dashboard.php') ? 'active' : '' ?>">
                <div class="nav-icon">
                    <i class="bi bi-grid-fill"></i>
                </div>
                <span>Dashboard</span>
                <?php if ($current_page == 'doctor_dashboard.php'): ?>
                    <span class="active-indicator"></span>
                <?php endif; ?>
            </a>
        </li>
        
        <li class="nav-item mb-2">
            <a href="doctor_schedule_management.php" class="nav-link <?= ($current_page == 'doctor_schedule_management.php') ? 'active' : '' ?>">
                <div class="nav-icon">
                    <i class="bi bi-plus-circle"></i>
                </div>
                <span>Add Schedule</span>
                <?php if ($current_page == 'doctor_schedule_management.php'): ?>
                    <span class="active-indicator"></span>
                <?php endif; ?>
            </a>
        </li>
        
        <li class="nav-item mb-2">
            <a href="doctor_schedule_view.php" class="nav-link <?= ($current_page == 'doctor_schedule_view.php') ? 'active' : '' ?>">
                <div class="nav-icon">
                    <i class="bi bi-calendar-week"></i>
                </div>
                <span>My Schedules</span>
                <?php if ($current_page == 'doctor_schedule_view.php'): ?>
                    <span class="active-indicator"></span>
                <?php endif; ?>
            </a>
        </li>
        
        <li class="nav-item mb-2">
            <a href="doctor_appointments.php" class="nav-link <?= ($current_page == 'doctor_appointments.php') ? 'active' : '' ?>">
                <div class="nav-icon">
                    <i class="bi bi-calendar-check"></i>
                </div>
                <span>Appointments</span>
                <?php if ($current_page == 'doctor_appointments.php'): ?>
                    <span class="active-indicator"></span>
                <?php endif; ?>
            </a>
        </li>
        
        <li class="nav-item mb-2">
            <a href="doctor_profile_view.php" class="nav-link <?= ($current_page == 'doctor_profile_view.php') ? 'active' : '' ?>">
                <div class="nav-icon">
                    <i class="bi bi-person-circle"></i>
                </div>
                <span>My Profile</span>
                <?php if ($current_page == 'doctor_profile_view.php'): ?>
                    <span class="active-indicator"></span>
                <?php endif; ?>
            </a>
        </li>
    </ul>
    
    <!-- Logout Button -->
    <div class="sidebar-footer p-3 mt-auto">
        <a href="logout.php" class="btn btn-logout w-100">
            <i class="bi bi-box-arrow-right me-2"></i>
            <span>Logout</span>
        </a>
    </div>
</div>

<!-- Mobile Offcanvas Sidebar -->
<div class="offcanvas offcanvas-start d-lg-none" tabindex="-1" id="sidebarOffcanvas" aria-labelledby="sidebarOffcanvasLabel">
    <div class="offcanvas-header bg-primary text-white">
        <h5 class="offcanvas-title" id="sidebarOffcanvasLabel">
            <img src="images/logo-white.png" alt="ABC Hospital" height="35">
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body p-0">
        <!-- Mobile User Info -->
        <div class="mobile-user-info p-3 bg-light">
            <div class="d-flex align-items-center">
                <div class="avatar-circle bg-primary me-3">
                    <span class="initials text-white fw-bold"><?= substr($_SESSION['name'] ?? 'D', 0, 1) ?></span>
                </div>
                <div>
                    <h6 class="mb-0">Dr. <?= $_SESSION['name'] ?? 'Doctor' ?></h6>
                    <small class="text-muted"><?= $_SESSION['email'] ?? '' ?></small>
                </div>
            </div>
        </div>
        
        <!-- Mobile Navigation -->
        <ul class="nav-menu-mobile list-unstyled p-3">
            <li class="mb-2">
                <a href="doctor_dashboard.php" class="mobile-nav-link d-flex align-items-center p-3 rounded-3 <?= ($current_page == 'doctor_dashboard.php') ? 'active bg-primary text-white' : 'bg-light' ?>">
                    <i class="bi bi-grid-fill me-3 fs-5"></i>
                    <span class="fw-medium">Dashboard</span>
                </a>
            </li>
            <li class="mb-2">
                <a href="doctor_schedule_management.php" class="mobile-nav-link d-flex align-items-center p-3 rounded-3 <?= ($current_page == 'doctor_schedule_management.php') ? 'active bg-primary text-white' : 'bg-light' ?>">
                    <i class="bi bi-plus-circle me-3 fs-5"></i>
                    <span class="fw-medium">Add Schedule</span>
                </a>
            </li>
            <li class="mb-2">
                <a href="doctor_schedule_view.php" class="mobile-nav-link d-flex align-items-center p-3 rounded-3 <?= ($current_page == 'doctor_schedule_view.php') ? 'active bg-primary text-white' : 'bg-light' ?>">
                    <i class="bi bi-calendar-week me-3 fs-5"></i>
                    <span class="fw-medium">My Schedules</span>
                </a>
            </li>
            <li class="mb-2">
                <a href="doctor_appointments.php" class="mobile-nav-link d-flex align-items-center p-3 rounded-3 <?= ($current_page == 'doctor_appointments.php') ? 'active bg-primary text-white' : 'bg-light' ?>">
                    <i class="bi bi-calendar-check me-3 fs-5"></i>
                    <span class="fw-medium">Appointments</span>
                    <?php if ($pending_count > 0): ?>
                        <span class="badge bg-warning ms-2"><?= $pending_count ?></span>
                    <?php endif; ?>
                </a>
            </li>
            <li class="mb-2">
                <a href="doctor_profile_view.php" class="mobile-nav-link d-flex align-items-center p-3 rounded-3 <?= ($current_page == 'doctor_profile_view.php') ? 'active bg-primary text-white' : 'bg-light' ?>">
                    <i class="bi bi-person-circle me-3 fs-5"></i>
                    <span class="fw-medium">My Profile</span>
                </a>
            </li>
            <li class="mt-4">
                <a href="logout.php" class="mobile-nav-link d-flex align-items-center p-3 rounded-3 bg-danger text-white">
                    <i class="bi bi-box-arrow-right me-3 fs-5"></i>
                    <span class="fw-medium">Logout</span>
                </a>
            </li>
        </ul>
    </div>
</div>