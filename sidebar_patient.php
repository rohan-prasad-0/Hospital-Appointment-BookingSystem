<?php 
    $current_page = basename($_SERVER['PHP_SELF']); 
?>

<!-- Desktop Sidebar -->
<div class="sidebar d-flex flex-column">
    <!-- Hospital Logo -->
    <div class="sidebar-header text-center py-4">
        <h5 class="text-white mb-0">Patient Portal</h5>
        <span class="badge bg-light text-primary mt-2 px-3 py-1 rounded-pill">
            <i class="bi bi-person-circle me-1"></i><?= $_SESSION['role'] ?? 'Patient' ?>
        </span>
    </div>
    
    <!-- User Info (Mobile visible) -->
    <div class="user-info-mobile d-lg-none p-3 border-bottom border-primary">
        <div class="d-flex align-items-center">
            <div class="avatar-circle bg-white bg-opacity-25 me-3">
                <span class="initials text-white fw-bold"><?= substr($_SESSION['name'] ?? 'P', 0, 1) ?></span>
            </div>
            <div>
                <h6 class="text-white mb-0"><?= $_SESSION['name'] ?? 'Patient' ?></h6>
                <small class="text-white-50"><?= $_SESSION['email'] ?? '' ?></small>
            </div>
        </div>
    </div>
    
    <!-- Navigation Menu -->
    <ul class="nav-menu flex-column p-3">
        <li class="nav-item mb-2">
            <a href="patient_dashboard.php" class="nav-link <?= ($current_page == 'patient_dashboard.php') ? 'active' : '' ?>">
                <div class="nav-icon">
                    <i class="bi bi-grid-fill"></i>
                </div>
                <span>Dashboard</span>
                <?php if ($current_page == 'patient_dashboard.php'): ?>
                    <span class="active-indicator"></span>
                <?php endif; ?>
            </a>
        </li>
        
        <li class="nav-item mb-2">
            <a href="doctor_schedules.php" class="nav-link <?= ($current_page == 'doctor_schedules.php') ? 'active' : '' ?>">
                <div class="nav-icon">
                    <i class="bi bi-calendar-week"></i>
                </div>
                <span>Doctor Schedules</span>
                <?php if ($current_page == 'doctor_schedules.php'): ?>
                    <span class="active-indicator"></span>
                <?php endif; ?>
            </a>
        </li>
        
        <li class="nav-item mb-2">
            <a href="view_appointments.php" class="nav-link <?= ($current_page == 'view_appointments.php') ? 'active' : '' ?>">
                <div class="nav-icon">
                    <i class="bi bi-calendar-check"></i>
                </div>
                <span>My Appointments</span>
                <?php if ($current_page == 'view_appointments.php'): ?>
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
                    <span class="initials text-white fw-bold"><?= substr($_SESSION['name'] ?? 'P', 0, 1) ?></span>
                </div>
                <div>
                    <h6 class="mb-0"><?= $_SESSION['name'] ?? 'Patient' ?></h6>
                    <small class="text-muted"><?= $_SESSION['email'] ?? '' ?></small>
                </div>
            </div>
        </div>
        
        <!-- Mobile Navigation -->
        <ul class="nav-menu-mobile list-unstyled p-3">
            <li class="mb-2">
                <a href="patient_dashboard.php" class="mobile-nav-link d-flex align-items-center p-3 rounded-3 <?= ($current_page == 'patient_dashboard.php') ? 'active bg-primary text-white' : 'bg-light' ?>">
                    <i class="bi bi-grid-fill me-3 fs-5"></i>
                    <span class="fw-medium">Dashboard</span>
                </a>
            </li>
            <li class="mb-2">
                <a href="doctor_schedules.php" class="mobile-nav-link d-flex align-items-center p-3 rounded-3 <?= ($current_page == 'doctor_schedules.php') ? 'active bg-primary text-white' : 'bg-light' ?>">
                    <i class="bi bi-calendar-week me-3 fs-5"></i>
                    <span class="fw-medium">Doctor Schedules</span>
                </a>
            </li>
            <li class="mb-2">
                <a href="view_appointments.php" class="mobile-nav-link d-flex align-items-center p-3 rounded-3 <?= ($current_page == 'view_appointments.php') ? 'active bg-primary text-white' : 'bg-light' ?>">
                    <i class="bi bi-calendar-check me-3 fs-5"></i>
                    <span class="fw-medium">My Appointments</span>
                </a>
            </li>
            <li class="mb-2">
                <a href="patient_profile.php" class="mobile-nav-link d-flex align-items-center p-3 rounded-3 <?= ($current_page == 'patient_profile.php') ? 'active bg-primary text-white' : 'bg-light' ?>">
                    <i class="bi bi-person-circle me-3 fs-5"></i>
                    <span class="fw-medium">My Profile</span>
                </a>
            </li>
            <li class="mb-2">
                <a href="patient_settings.php" class="mobile-nav-link d-flex align-items-center p-3 rounded-3 <?= ($current_page == 'patient_settings.php') ? 'active bg-primary text-white' : 'bg-light' ?>">
                    <i class="bi bi-gear me-3 fs-5"></i>
                    <span class="fw-medium">Settings</span>
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