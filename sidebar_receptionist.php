<?php 
$current_page = basename($_SERVER['PHP_SELF']); 
?>

<!-- Desktop Sidebar -->
<div class="sidebar d-flex flex-column">
    <!-- Hospital Logo -->
    <div class="sidebar-header text-center py-4">
        <h5 class="text-white mb-0">Receptionist Portal</h5>
        <span class="badge bg-light text-primary mt-2 px-3 py-1 rounded-pill">
            <i class="bi bi-person-badge me-1"></i><?= $_SESSION['role'] ?? 'Receptionist' ?>
        </span>
    </div>
    
    <!-- User Info (Mobile visible) -->
    <div class="user-info-mobile d-lg-none p-3 border-bottom border-primary">
        <div class="d-flex align-items-center">
            <div class="avatar-circle bg-white bg-opacity-25 me-3">
                <span class="initials text-white fw-bold"><?= substr($_SESSION['name'] ?? 'R', 0, 1) ?></span>
            </div>
            <div>
                <h6 class="text-white mb-0"><?= $_SESSION['name'] ?? 'Receptionist' ?></h6>
                <small class="text-white-50"><?= $_SESSION['email'] ?? '' ?></small>
            </div>
        </div>
    </div>
    
    <!-- Navigation Menu -->
    <ul class="nav-menu flex-column p-3">
        <li class="nav-item mb-2">
            <a href="receptionist_dashboard.php" class="nav-link <?= ($current_page == 'receptionist_dashboard.php') ? 'active' : '' ?>">
                <div class="nav-icon">
                    <i class="bi bi-grid-fill"></i>
                </div>
                <span>Dashboard</span>
                <?php if ($current_page == 'receptionist_dashboard.php'): ?>
                    <span class="active-indicator"></span>
                <?php endif; ?>
            </a>
        </li>
        
        <li class="nav-item mb-2">
            <a href="receptionist_doctor_view.php" class="nav-link <?= ($current_page == 'receptionist_doctor_view.php') ? 'active' : '' ?>">
                <div class="nav-icon">
                    <i class="bi bi-search"></i>
                </div>
                <span>Search Doctors</span>
                <?php if ($current_page == 'receptionist_doctor_view.php'): ?>
                    <span class="active-indicator"></span>
                <?php endif; ?>
            </a>
        </li>
        
        <li class="nav-item mb-2">
            <a href="receptionist_view_appointments.php" class="nav-link <?= ($current_page == 'receptionist_view_appointments.php') ? 'active' : '' ?>">
                <div class="nav-icon">
                    <i class="bi bi-calendar-check"></i>
                </div>
                <span>View Appointments</span>
                <?php if ($current_page == 'receptionist_view_appointments.php'): ?>
                    <span class="active-indicator"></span>
                <?php endif; ?>
                <?php
                // Get today's appointments count for badge
                if (isset($_SESSION['user_id'])) {
                    $today_sql = "SELECT COUNT(*) as total FROM appointment a 
                                 JOIN doctor_schedule ds ON a.sch_id = ds.sch_id 
                                 WHERE ds.available_date = CURDATE() AND a.status = 'Booked'";
                    $today_rs = $mysqli->query($today_sql);
                    $today_count = $today_rs->fetch_assoc()['total'];
                    if ($today_count > 0) {
                        echo "<span class='badge bg-warning ms-auto'>$today_count</span>";
                    }
                }
                ?>
            </a>
        </li>
        
        <li class="nav-item mb-2">
            <a href="receptionist_profile.php" class="nav-link <?= ($current_page == 'receptionist_profile.php') ? 'active' : '' ?>">
                <div class="nav-icon">
                    <i class="bi bi-person-circle"></i>
                </div>
                <span>My Profile</span>
                <?php if ($current_page == 'receptionist_profile.php'): ?>
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
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body p-0">
        <!-- Mobile User Info -->
        <div class="mobile-user-info p-3 bg-light">
            <div class="d-flex align-items-center">
                <div class="avatar-circle bg-primary me-3">
                    <span class="initials text-white fw-bold"><?= substr($_SESSION['name'] ?? 'R', 0, 1) ?></span>
                </div>
                <div>
                    <h6 class="mb-0"><?= $_SESSION['name'] ?? 'Receptionist' ?></h6>
                    <small class="text-muted"><?= $_SESSION['email'] ?? '' ?></small>
                </div>
            </div>
        </div>
        
        <!-- Mobile Navigation -->
        <ul class="nav-menu-mobile list-unstyled p-3">
            <li class="mb-2">
                <a href="receptionist_dashboard.php" class="mobile-nav-link d-flex align-items-center p-3 rounded-3 <?= ($current_page == 'receptionist_dashboard.php') ? 'active bg-primary text-white' : 'bg-light' ?>">
                    <i class="bi bi-grid-fill me-3 fs-5"></i>
                    <span class="fw-medium">Dashboard</span>
                </a>
            </li>
            <li class="mb-2">
                <a href="receptionist_doctor_view.php" class="mobile-nav-link d-flex align-items-center p-3 rounded-3 <?= ($current_page == 'receptionist_doctor_view.php') ? 'active bg-primary text-white' : 'bg-light' ?>">
                    <i class="bi bi-search me-3 fs-5"></i>
                    <span class="fw-medium">Search Doctors</span>
                </a>
            </li>
            <li class="mb-2">
                <a href="receptionist_view_appointments.php" class="mobile-nav-link d-flex align-items-center p-3 rounded-3 <?= ($current_page == 'receptionist_view_appointments.php') ? 'active bg-primary text-white' : 'bg-light' ?>">
                    <i class="bi bi-calendar-check me-3 fs-5"></i>
                    <span class="fw-medium">View Appointments</span>
                </a>
            </li>
            <li class="mb-2">
                <a href="receptionist_profile.php" class="mobile-nav-link d-flex align-items-center p-3 rounded-3 <?= ($current_page == 'receptionist_profile.php') ? 'active bg-primary text-white' : 'bg-light' ?>">
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