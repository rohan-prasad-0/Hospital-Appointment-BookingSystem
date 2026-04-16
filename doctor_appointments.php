<?php
session_start();
require_once "db_connection.php";

// Check if user is logged in as doctor
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Doctor') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Get doctor details
$doc_sql = "SELECT doctor_id, name FROM doctor WHERE user_id = $user_id";
$doc_rs = $mysqli->query($doc_sql);
$doc_row = mysqli_fetch_assoc($doc_rs);
$doctor_id = $doc_row['doctor_id'];
$doctor_name = $doc_row['name'];

// Filter by schedule if provided
$schedule_filter = isset($_GET['schedule_id']) ? intval($_GET['schedule_id']) : '';

// Get appointments
$appointments_sql = "SELECT a.*, p.name as patient_name, p.phone as patient_phone,
                    ds.available_date, ds.time_slot, s.sp_name
                    FROM appointment a
                    JOIN patient p ON a.patient_id = p.patient_id
                    JOIN doctor_schedule ds ON a.sch_id = ds.sch_id
                    JOIN doctor d ON a.doctor_id = d.doctor_id
                    JOIN specialization s ON d.sp_id = s.sp_id
                    WHERE a.doctor_id = $doctor_id";

if ($schedule_filter) {
    $appointments_sql .= " AND a.sch_id = $schedule_filter";
}

$appointments_sql .= " ORDER BY ds.available_date DESC, ds.time_slot DESC, a.appointment_number ASC";
$appointments_rs = $mysqli->query($appointments_sql);

// Get schedule info for filter
$schedule_info = null;
if ($schedule_filter) {
    $schedule_info_sql = "SELECT * FROM doctor_schedule WHERE sch_id = $schedule_filter AND doctor_id = $doctor_id";
    $schedule_info_rs = $mysqli->query($schedule_info_sql);
    $schedule_info = $schedule_info_rs->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointments - ABC Hospital</title>
    
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
        <?php include('sidebar_doctor.php'); ?>
        
        <div class="main-content">
            <div class="page-header d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <button class="menu-btn d-lg-none me-3" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarOffcanvas">
                        <i class="bi bi-list"></i>
                    </button>
                    <div>
                        <h4 class="fw-bold">
                            <i class="bi bi-calendar-check text-primary me-2"></i>
                            <?= $schedule_filter ? 'Schedule Appointments' : 'All Appointments' ?>
                        </h4>
                    </div>
                </div>
            </div>

            <!-- Filter Info -->
            <?php if ($schedule_filter && $schedule_info): 
                $date = new DateTime($schedule_info['available_date']);
                $time = DateTime::createFromFormat('H:i:s', $schedule_info['time_slot']);
            ?>
                <div class="filter-info">
                    <div>
                        <i class="bi bi-filter-circle-fill text-primary me-2"></i>
                        <strong>Showing appointments for:</strong> 
                        <?= $date->format('l, M j, Y') ?> at <?= $time->format('h:i A') ?>
                    </div>
                    <a href="doctor_appointments.php" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-x-lg me-1"></i>Clear Filter
                    </a>
                </div>
            <?php endif; ?>

            <!-- Appointments List -->
            <?php if (mysqli_num_rows($appointments_rs) > 0): ?>
                <div class="appointments-list">
                    <?php while ($appointment = mysqli_fetch_assoc($appointments_rs)): 
                        $date = new DateTime($appointment['available_date']);
                        $formatted_date = $date->format('l, M j, Y');
                        
                        $time = DateTime::createFromFormat('H:i:s', $appointment['time_slot']);
                        $formatted_time = $time->format('h:i A');
                        
                        $status_class = strtolower($appointment['status']);
                    ?>
                        <div class="appointment-card">
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="d-flex">
                                        <div class="patient-avatar">
                                            <i class="bi bi-person-fill"></i>
                                        </div>
                                        <div>
                                            <h5 class="fw-bold mb-1"><?= $appointment['patient_name'] ?></h5>
                                            <div class="appointment-detail">
                                                <i class="bi bi-telephone"></i>
                                                <span><?= $appointment['patient_phone'] ?></span>
                                            </div>
                                            <div class="appointment-detail">
                                                <i class="bi bi-stethoscope"></i>
                                                <span><?= $appointment['sp_name'] ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="appointment-detail">
                                        <i class="bi bi-calendar"></i>
                                        <span><?= $formatted_date ?></span>
                                    </div>
                                    <div class="appointment-detail">
                                        <i class="bi bi-clock"></i>
                                        <span><?= $formatted_time ?></span>
                                    </div>
                                    <div class="appointment-detail">
                                        <span>Appointment #<?= $appointment['appointment_number'] ?></span>
                                    </div>
                                </div>
                                
                                <div class="col-md-3">
                                    <span class="status-badge <?= $status_class ?>">
                                        <i class="bi bi-<?= $status_class == 'booked' ? 'clock' : ($status_class == 'completed' ? 'check-circle' : 'x-circle') ?> me-1"></i>
                                        <?= $appointment['status'] ?>
                                    </span>
                                </div>
                            </div>
                            
                            <!-- Notes Section - Displayed inline -->
                            <?php if (!empty($appointment['note'])): ?>
                                <div class="note-section">
                                    <div class="note-content">
                                        <i class="bi bi-sticky"></i>
                                        <strong>Patient Note:</strong> <?= htmlspecialchars($appointment['note']) ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <!-- No Results Found -->
                <div class="empty-state">
                    <i class="bi bi-calendar-x display-1 text-muted"></i>
                    <h4 class="text-muted mt-3">No Appointments Found</h4>
                    <p class="text-muted">
                        <?= $schedule_filter ? 'No appointments for this schedule.' : 'You don\'t have any appointments yet.' ?>
                    </p>
                    <?php if (!$schedule_filter): ?>
                        <a href="doctor_schedule_management.php" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-2"></i>Create Schedule
                        </a>
                    <?php else: ?>
                        <a href="doctor_appointments.php" class="btn btn-primary">
                            <i class="bi bi-arrow-left me-2"></i>View All Appointments
                        </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>