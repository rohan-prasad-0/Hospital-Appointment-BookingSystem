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

// Handle schedule deletion
if (isset($_GET['delete_schedule'])) {
    $sch_id = intval($_GET['delete_schedule']);
    
    // Check if any appointments exist for this schedule
    $check_appointments = $mysqli->query("
        SELECT COUNT(*) as total FROM appointment 
        WHERE sch_id = $sch_id AND status = 'Booked'
    ");
    $appointment_count = $check_appointments->fetch_assoc()['total'];
    
    if ($appointment_count > 0) {
        $error = "Cannot delete schedule. There are booked appointments for this time slot.";
    } else {
        $delete_sql = "DELETE FROM doctor_schedule WHERE sch_id = $sch_id AND doctor_id = $doctor_id";
        if ($mysqli->query($delete_sql)) {
            $success = "Schedule deleted successfully!";
        } else {
            $error = "Error deleting schedule: " . $mysqli->error;
        }
    }
}

// Get doctor schedules
$schedules_sql = "SELECT ds.*, (SELECT COUNT(*) FROM appointment WHERE sch_id = ds.sch_id AND status = 'Booked') as booked_count 
                 FROM doctor_schedule ds
                 WHERE ds.doctor_id = $doctor_id
                 ORDER BY ds.available_date DESC, ds.time_slot DESC";
$schedules_rs = $mysqli->query($schedules_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Schedules - ABC Hospital</title>
    
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
        
    </style>
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
                            <i class="bi bi-calendar-week text-primary me-2"></i>
                            My Schedules
                        </h4>
                    </div>
                </div>
                <div>
                    <a href="doctor_schedule_management.php" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i>Add New Slot
                    </a>
                </div>
            </div>

            <!-- Status Messages -->
            <?php if (isset($success)): ?>
                <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
                    <i class="bi bi-check-circle-fill me-2 fs-4"></i>
                    <div><?= $success ?></div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <?php if (isset($error)): ?>
                <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2 fs-4"></i>
                    <div><?= $error ?></div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if (mysqli_num_rows($schedules_rs) > 0): ?>
                <div class="schedule-list">
                    <?php while ($schedule = mysqli_fetch_assoc($schedules_rs)): 
                        $date = new DateTime($schedule['available_date']);
                        $today = new DateTime();
                        $is_past = $date < $today;
                        
                        $day = $date->format('d');
                        $month = $date->format('M');
                        $year = $date->format('Y');
                        $day_name = $date->format('l');
                        
                        $time = DateTime::createFromFormat('H:i:s', $schedule['time_slot']);
                        $formatted_time = $time->format('h:i A');
                        
                        $available_slots = $schedule['max_patient'] - $schedule['booked_count'];
                        $booked_percentage = ($schedule['booked_count'] / $schedule['max_patient']) * 100;
                        
                        $status_class = $schedule['status'] == 'Available' ? 'bg-success' : 'bg-secondary';
                    ?>
                        <div class="schedule-card <?= $is_past ? 'past' : '' ?>" data-status="<?= $is_past ? 'past' : 'upcoming' ?>">
                            <div class="row align-items-center">
                                <div class="col-md-2">
                                    <div class="date-badge">
                                        <div class="day"><?= $day ?></div>
                                        <div class="month"><?= $month ?> <?= $year ?></div>
                                    </div>
                                </div>
                                
                                <div class="col-md-2">
                                    <span class="time-badge">
                                        <i class="bi bi-clock"></i> <?= $formatted_time ?>
                                    </span>
                                </div>
                                
                                <div class="col-md-3">
                                    <div class="d-flex align-items-center gap-3">
                                        <div>
                                            <small class="text-muted d-block">Max Patients</small>
                                            <strong><?= $schedule['max_patient'] ?> patients</strong>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-2">
                                    <span class="badge <?= $status_class ?> px-3 py-2"><?= $schedule['status'] ?></span>
                                    <?php if ($available_slots > 0): ?>
                                        <small class="text-success d-block mt-1">
                                            <i class="bi bi-check-circle me-1"></i><?= $available_slots ?> slots available
                                        </small>
                                    <?php else: ?>
                                        <small class="text-danger d-block mt-1">
                                            <i class="bi bi-x-circle me-1"></i>Fully booked
                                        </small>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="col-md-3">
                                    <div class="d-flex justify-content-end gap-2">
                                        <a href="doctor_appointments.php?schedule_id=<?= $schedule['sch_id'] ?>" 
                                           class="btn-icon btn-view" title="View Appointments">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        
                                        <?php if ($schedule['booked_count'] == 0): ?>
                                            <button type="button" class="btn-icon btn-delete" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#deleteModal<?= $schedule['sch_id'] ?>"
                                                    title="Delete Schedule">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        <?php else: ?>
                                            <button class="btn-icon btn-delete" disabled title="Cannot delete - has appointments">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Delete Confirmation Modal -->
                        <div class="modal fade" id="deleteModal<?= $schedule['sch_id'] ?>" tabindex="-1">
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
                                        <p class="fw-medium">Are you sure you want to delete this time slot?</p>
                                        <div class="bg-light p-3 rounded-3">
                                            <div class="d-flex align-items-center mb-2">
                                                <i class="bi bi-calendar text-danger me-2"></i>
                                                <span><strong>Date:</strong> <?= $day_name ?>, <?= $month ?> <?= $day ?>, <?= $year ?></span>
                                            </div>
                                            <div class="d-flex align-items-center mb-2">
                                                <i class="bi bi-clock text-danger me-2"></i>
                                                <span><strong>Time:</strong> <?= $formatted_time ?></span>
                                            </div>
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-people text-danger me-2"></i>
                                                <span><strong>Max Patients:</strong> <?= $schedule['max_patient'] ?></span>
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
                                        <a href="doctor_schedule_view.php?delete_schedule=<?= $schedule['sch_id'] ?>" 
                                           class="btn btn-danger">
                                            <i class="bi bi-trash me-1"></i>Delete Schedule
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
                    <i class="bi bi-calendar-week display-1 text-muted"></i>
                    <h4 class="text-muted mt-3">No Schedules Found</h4>
                    <p class="text-muted">You haven't created any time slots yet.</p>
                    <a href="doctor_schedule_management.php" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i>Create Your First Schedule
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>