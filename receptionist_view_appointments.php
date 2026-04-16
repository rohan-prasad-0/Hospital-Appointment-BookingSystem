<?php
session_start();
require_once "db_connection.php";

// Check if user is logged in as receptionist
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Receptionist') {
    header("Location: login_1.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Get receptionist details
$rec_sql = "SELECT name FROM receptionist WHERE user_id = $user_id";
$rec_rs = $mysqli->query($rec_sql);
$rec_row = mysqli_fetch_assoc($rec_rs);
$receptionist_name = $rec_row['name'];

// Get all doctors for filter
$doctors_sql = "SELECT doctor_id, name FROM doctor ORDER BY name ASC";
$doctors_rs = $mysqli->query($doctors_sql);

// Filter parameters
$doctor_filter = $_GET['doctor_id'] ?? '';
$status_filter = $_GET['status'] ?? '';
$date_filter = $_GET['date'] ?? '';

// Build appointments query
$appointments_sql = "SELECT a.*, d.name as doctor_name, p.name as patient_name, 
                    s.sp_name, ds.available_date, ds.time_slot
                    FROM appointment a
                    JOIN doctor d ON a.doctor_id = d.doctor_id
                    JOIN patient p ON a.patient_id = p.patient_id
                    JOIN specialization s ON d.sp_id = s.sp_id
                    JOIN doctor_schedule ds ON a.sch_id = ds.sch_id
                    WHERE 1=1";

if ($doctor_filter) {
    $appointments_sql .= " AND a.doctor_id = $doctor_filter";
}

if ($status_filter) {
    $appointments_sql .= " AND a.status = '$status_filter'";
}

if ($date_filter) {
    $appointments_sql .= " AND ds.available_date = '$date_filter'";
}

$appointments_sql .= " ORDER BY ds.available_date DESC, ds.time_slot DESC";
$appointments_rs = $mysqli->query($appointments_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Appointments - ABC Hospital</title>
    
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
        <?php include('sidebar_receptionist.php'); ?>
        
        <div class="main-content">
            <!-- Page Header with Mobile Menu Button -->
            <div class="page-header d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <button class="menu-btn d-lg-none me-3" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarOffcanvas">
                        <i class="bi bi-list"></i>
                    </button>
                    <div>
                        <h4 class="fw-bold">
                            <i class="bi bi-calendar-check text-primary me-2"></i>
                            View Appointments
                        </h4>
                    </div>
                </div>
            </div>

            <!-- Filter Card -->
            <div class="filter-card">
                <form method="GET" action="" class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Filter by Doctor</label>
                        <select name="doctor_id" class="form-select">
                            <option value="">All Doctors</option>
                            <?php 
                            mysqli_data_seek($doctors_rs, 0);
                            while ($doctor = mysqli_fetch_assoc($doctors_rs)): 
                            ?>
                                <option value="<?= $doctor['doctor_id'] ?>" 
                                    <?= ($doctor_filter == $doctor['doctor_id']) ? 'selected' : '' ?>>
                                    Dr. <?= $doctor['name'] ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Status</label>
                        <select name="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="Booked" <?= ($status_filter == 'Booked') ? 'selected' : '' ?>>Booked</option>
                            <option value="Completed" <?= ($status_filter == 'Completed') ? 'selected' : '' ?>>Completed</option>
                            <option value="Cancelled" <?= ($status_filter == 'Cancelled') ? 'selected' : '' ?>>Cancelled</option>
                        </select>
                    </div>
                    
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Date</label>
                        <input type="date" name="date" class="form-control" value="<?= $date_filter ?>">
                    </div>
                    
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-funnel me-2"></i>Apply Filters
                        </button>
                    </div>
                </form>
            </div>

            <!-- Appointments List -->
            <?php if (mysqli_num_rows($appointments_rs) > 0): ?>
                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Patient</th>
                                        <th>Doctor</th>
                                        <th>Date & Time</th>
                                        <th>Appointment #</th>
                                        <th>Status</th>
                                        <th>Notes</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($appointment = mysqli_fetch_assoc($appointments_rs)): 
                                        $date = new DateTime($appointment['available_date']);
                                        $formatted_date = $date->format('M j, Y');
                                        
                                        $time = DateTime::createFromFormat('H:i:s', $appointment['time_slot']);
                                        $formatted_time = $time->format('h:i A');
                                        
                                        $status_class = strtolower($appointment['status']);
                                    ?>
                                        <tr>
                                            <td>
                                                <strong><?= $appointment['patient_name'] ?></strong>
                                            </td>
                                            <td>
                                                <strong>Dr. <?= $appointment['doctor_name'] ?></strong>
                                                <div class="text-muted small"><?= $appointment['sp_name'] ?></div>
                                            </td>
                                            <td>
                                                <strong><?= $formatted_date ?></strong>
                                                <div class="text-muted small"><?= $formatted_time ?></div>
                                            </td>
                                            <td>
                                                <span class="badge bg-primary">#<?= $appointment['appointment_number'] ?></span>
                                            </td>
                                            <td>
                                                <span class="status-badge <?= $status_class ?>">
                                                    <i class="bi bi-<?= $status_class == 'booked' ? 'clock' : ($status_class == 'completed' ? 'check-circle' : 'x-circle') ?> me-1"></i>
                                                    <?= $appointment['status'] ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?php if (!empty($appointment['note'])): ?>
                                                    <span class="note-popover" 
                                                          data-bs-toggle="popover" 
                                                          data-bs-title="Patient Notes"
                                                          data-bs-content="<?= htmlspecialchars($appointment['note']) ?>">
                                                        <i class="bi bi-sticky text-primary"></i> View
                                                    </span>
                                                <?php else: ?>
                                                    <span class="text-muted">-</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <!-- No Results Found -->
                <div class="empty-state">
                    <i class="bi bi-calendar-x display-1 text-muted"></i>
                    <h4 class="text-muted mt-3">No Appointments Found</h4>
                    <p class="text-muted">No appointments match your filter criteria.</p>
                    <a href="receptionist_view_appointments.php" class="btn btn-primary">
                        <i class="bi bi-arrow-clockwise me-2"></i>Clear Filters
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
    // Initialize popovers
    var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
    var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl, {
            trigger: 'hover',
            placement: 'left'
        })
    })
    </script>
</body>
</html>