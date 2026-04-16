<?php
session_start();
require_once "db_connection.php";

// Check if user is logged in as patient
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Patient') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Get patient_id from user_id
$p_sql = "SELECT patient_id, name FROM patient WHERE user_id = $user_id";
$p_rs = $mysqli->query($p_sql);
$p_row = mysqli_fetch_assoc($p_rs);
$patient_id = $p_row['patient_id'];
$patient_name = $p_row['name'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Appointments - ABC Hospital</title>
    
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

<?php include('sidebar_patient.php'); ?>

<div class="main-content">
    <!-- Page Header -->
    <div class="page-header d-flex justify-content-between align-items-center">
        <div>
            <h4 class="fw-bold">
                <i class="bi bi-calendar-check text-primary me-2"></i>
                My Appointments
            </h4>
        </div>
    </div>

    <div class="appointment-tabs">
        <ul class="nav" id="appointmentTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="upcoming-tab" data-bs-toggle="tab" data-bs-target="#upcoming" type="button" role="tab">
                    Upcoming Appointments
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="past-tab" data-bs-toggle="tab" data-bs-target="#past" type="button" role="tab">
                    Past Appointments
                </button>
            </li>
        </ul>
    </div>

    <div class="tab-content" id="appointmentTabsContent">
        
        <!-- Upcoming Appointments Tab -->
        <div class="tab-pane fade show active" id="upcoming" role="tabpanel">
            <?php
            $upcoming_sql = "SELECT a.*, 
                            d.name AS doctor_name, 
                            d.gender,
                            s.sp_name,
                            ds.available_date, 
                            ds.time_slot,
                            ds.max_patient
                        FROM appointment a
                        JOIN doctor d ON a.doctor_id = d.doctor_id
                        JOIN specialization s ON d.sp_id = s.sp_id
                        JOIN doctor_schedule ds ON a.sch_id = ds.sch_id
                        WHERE a.patient_id = $patient_id 
                        AND (a.status = 'Booked' OR a.status = 'Confirmed')
                        AND (ds.available_date > CURDATE() OR (ds.available_date = CURDATE() AND ds.time_slot > CURTIME()))
                        ORDER BY ds.available_date ASC, ds.time_slot ASC";

            $upcoming_rs = $mysqli->query($upcoming_sql);

            if (mysqli_num_rows($upcoming_rs) > 0) {
                while ($row = mysqli_fetch_assoc($upcoming_rs)) {
                    $date = new DateTime($row['available_date']);
                    $dayName = $date->format('l');
                    $monthName = $date->format('F');
                    $dayNum = $date->format('d');
                    $year = $date->format('Y');

                    $start = DateTime::createFromFormat('H:i:s', $row['time_slot']);
                    $time_slot = $start->format('h:i A');
                    
                    // Get total appointments in queue for this schedule
                    $queue_sql = "SELECT COUNT(*) as total FROM appointment WHERE sch_id = {$row['sch_id']} AND status = 'Booked'";
                    $queue_rs = $mysqli->query($queue_sql);
                    $queue_data = $queue_rs->fetch_assoc();
                    $total_queue = $queue_data['total'];
            ?>
                    <div class="appointment-card upcoming">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <div class="d-flex">
                                    <div class="doctor-avatar-sm mx-3">
                                        <i class="bi bi-person-fill" ></i>
                                    </div>
                                    <div>
                                        <h5 class="fw-bold mb-1">Dr. <?= $row['doctor_name'] ?></h5>
                                        <p class="text-muted mb-2"><?= $row['sp_name'] ?> • <?= $row['gender'] ?></p>
                                        <div class="appointment-detail">
                                            <i class="bi bi-hash"></i>
                                            <span><strong>Appointment #<?= $row['appointment_number'] ?></strong> of <?= $row['max_patient'] ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="appointment-detail">
                                    <i class="bi bi-calendar"></i>
                                    <span><?= $dayName ?>, <?= $monthName ?> <?= $dayNum ?>, <?= $year ?></span>
                                </div>
                                <div class="appointment-detail">
                                    <i class="bi bi-clock"></i>
                                    <span><?= $time_slot ?></span>
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="d-flex gap-2">
                                    <button class="btn-cancel" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#cancelModal<?= $row['appointment_id'] ?>">
                                            Cancel
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <?php if (!empty($row['note'])): ?>
                            <div class="mt-3 pt-2 border-top">
                                <small class="text-muted">
                                    <i class="bi bi-pencil-square me-1"></i>
                                    Note: <?= htmlspecialchars($row['note']) ?>
                                </small>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Cancel Modal -->
                    <div class="modal fade" id="cancelModal<?= $row['appointment_id'] ?>" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">
                                        <i class="bi bi-exclamation-triangle me-2"></i>
                                        Cancel Appointment
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <p>Are you sure you want to cancel this appointment?</p>
                                    
                                    <div class="appointment-summary">
                                        <div class="summary-item">
                                            <span><strong>Dr. <?= $row['doctor_name'] ?></strong> (<?= $row['sp_name'] ?>)</span>
                                        </div>
                                        <div class="summary-item">
                                            <span><?= $dayName ?>, <?= $monthName ?> <?= $dayNum ?>, <?= $year ?></span>
                                        </div>
                                        <div class="summary-item">
                                            <span><?= $time_slot ?></span>
                                        </div>
                                        <div class="summary-item">
                                            <span>Appointment #<?= $row['appointment_number'] ?></span>
                                        </div>
                                    </div>
                                    
                                    <p class="text-danger mb-0">
                                        This action cannot be undone.
                                    </p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                        Keep Appointment
                                    </button>
                                    <a href="cancel_appointment.php?appointment_id=<?= $row['appointment_id'] ?>" 
                                       class="btn-modal-cancel">
                                        Yes, Cancel
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
            <?php
                }
            } else {
            ?>
                <div class="empty-state">
                    <i class="bi bi-calendar-check"></i>
                    <h4>No Upcoming Appointments</h4>
                    <p>You don't have any upcoming appointments scheduled.</p>
                </div>
            <?php
            }
            ?>
        </div>

        <!-- Past Appointments Tab -->
        <div class="tab-pane fade" id="past" role="tabpanel">
            <?php
            // Query for past appointments
            $past_sql = "SELECT a.*, 
                        d.name AS doctor_name, 
                        d.gender,
                        s.sp_name,
                        ds.available_date, 
                        ds.time_slot
                    FROM appointment a
                    JOIN doctor d ON a.doctor_id = d.doctor_id
                    JOIN specialization s ON d.sp_id = s.sp_id
                    JOIN doctor_schedule ds ON a.sch_id = ds.sch_id
                    WHERE a.patient_id = $patient_id 
                    AND (a.status = 'Completed' OR a.status = 'Cancelled' 
                        OR ds.available_date < CURDATE() 
                        OR (ds.available_date = CURDATE() AND ds.time_slot < CURTIME()))
                    ORDER BY ds.available_date DESC, ds.time_slot DESC";

            $past_rs = $mysqli->query($past_sql);

            if (mysqli_num_rows($past_rs) > 0) {
                while ($row = mysqli_fetch_assoc($past_rs)) {
                    $date = new DateTime($row['available_date']);
                    $dayName = $date->format('l');
                    $monthName = $date->format('F');
                    $dayNum = $date->format('d');
                    $year = $date->format('Y');

                    $start = DateTime::createFromFormat('H:i:s', $row['time_slot']);
                    $time_slot = $start->format('h:i A');

                    $status_class = strtolower($row['status']);
            ?>
                    <div class="appointment-card past">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <div class="d-flex" >
                                    <div class="doctor-avatar-sm mx-3" style="background: var(--gray-dark);">
                                        <i class="bi bi-person-fill"></i>
                                    </div>
                                    <div>
                                        <h5 class="fw-bold mb-1">Dr. <?= $row['doctor_name'] ?></h5>
                                        <p class="text-muted mb-2"><?= $row['sp_name'] ?> • <?= $row['gender'] ?></p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="appointment-detail">
                                    <i class="bi bi-calendar"></i>
                                    <span><?= $monthName ?> <?= $dayNum ?>, <?= $year ?></span>
                                </div>
                                <div class="appointment-detail">
                                    <i class="bi bi-clock"></i>
                                    <span><?= $time_slot ?></span>
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="mb-2">
                                    <span class="status-badge <?= $status_class ?>">
                                        <i class="bi bi-<?= $status_class == 'completed' ? 'check-circle' : 'x-circle' ?>"></i>
                                        <?= $row['status'] ?>
                                    </span>
                                </div>
                                <div class="appointment-detail">
                                    <i class="bi bi-hash"></i>
                                    <span>Appointment #<?= $row['appointment_number'] ?></span>
                                </div>
                            </div>
                        </div>
                        
                        <?php if (!empty($row['note'])): ?>
                            <div class="mt-3 pt-2 border-top">
                                <small class="text-muted">
                                    Note: <?= htmlspecialchars($row['note']) ?>
                                </small>
                            </div>
                        <?php endif; ?>
                    </div>
            <?php
                }
            } else {
            ?>
                <div class="empty-state">
                    <i class="bi bi-clock-history"></i>
                    <h4>No Past Appointments</h4>
                    <p>You haven't had any appointments yet.</p>
                </div>
            <?php
            }
            ?>
        </div>
    </div>
</div>

</body>
</html>