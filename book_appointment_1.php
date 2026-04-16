<?php
session_start();
require_once "db_connection.php";

// Check if user is logged in as patient
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Patient') {
    header("Location: login.php");
    exit();
}

$doctor_id = $_GET['doctor_id'] ?? '';
$status = $_GET['status'] ?? '';
$user_id = $_SESSION['user_id'];

// Get patient details
$p_sql = "SELECT patient_id, name FROM patient WHERE user_id = $user_id";
$p_rs = $mysqli->query($p_sql);
$p_row = mysqli_fetch_assoc($p_rs);
$patient_id = $p_row['patient_id'];
$patient_name = $p_row['name'];

// Get doctor details
$sql = "SELECT d.*, s.sp_name 
        FROM doctor d 
        JOIN specialization s ON d.sp_id = s.sp_id 
        WHERE d.doctor_id = $doctor_id";
$rs = $mysqli->query($sql);

if (mysqli_num_rows($rs) > 0) {
    $doctor = mysqli_fetch_assoc($rs);
} else {
    die("Doctor not found.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Appointment - ABC Hospital</title>
    
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
                <i class="bi bi-calendar-plus text-primary me-2"></i>
                Book Appointment
            </h4>
            <p class="text-muted mb-0">Select a convenient time slot to book your appointment</p>
        </div>
        <div>
            <a href="doctor_schedules.php" class="btn btn-outline-primary">
                <i class="bi bi-arrow-left me-2"></i>Back to Doctors
            </a>
        </div>
    </div>

    <!-- Status Messages -->
    <?php if ($status == 'slot_full'): ?>
        <div class="status-alert status-warning d-flex align-items-center">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <div>
                <strong>Slot Full!</strong> The selected time slot is no longer available. Please choose another slot.
            </div>
        </div>
    <?php elseif ($status == 'booking_error'): ?>
        <div class="status-alert status-error d-flex align-items-center">
            <i class="bi bi-x-circle-fill"></i>
            <div>
                <strong>Booking Failed!</strong> There was an error processing your appointment. Please try again.
            </div>
        </div>
    <?php elseif ($status == 'schedule_not_found'): ?>
        <div class="status-alert status-error d-flex align-items-center">
            <i class="bi bi-x-circle-fill"></i>
            <div>
                <strong>Schedule Not Found!</strong> The selected schedule is no longer available.
            </div>
        </div>
    <?php endif; ?>

    <!-- Doctor Profile Section -->
    <div class="doctor-profile-card">
        <div class="row align-items-center">
            <div class="col-md-2 text-center">
                <div class="doctor-avatar-lg">
                    <i class="bi bi-person-fill"></i>
                </div>
            </div>
            <div class="col-md-10">
                <h3 class="fw-bold text-primary mb-3">Dr. <?= $doctor['name'] ?></h3>
                <div class="row">
                    <div class="col-md-4">
                        <div class="info-badge">
                            <i class="bi bi-stethoscope"></i>
                            <?= $doctor['sp_name'] ?>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="info-badge">
                            <?= $doctor['gender'] ?>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="info-badge">
                            <i class="bi bi-telephone"></i>
                            <?= $doctor['phone'] ?>
                        </div>
                    </div>
                </div>
                <?php if (!empty($doctor['qualification'])): ?>
                    <div class="mt-2">
                        <i class="bi bi-award text-primary me-2"></i>
                        <?= $doctor['qualification'] ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Available Time Slots Section -->
    <div class="appointment-card">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="bi bi-calendar-check me-2"></i>
                Available Time Slots
            </h5>
        </div>
        <div class="card-body">
            <?php
            // Get available time slots for the doctor
            $schedule_sql = "SELECT * FROM doctor_schedule 
                           WHERE doctor_id = $doctor_id 
                           AND status = 'Available' 
                           AND available_date >= CURDATE()
                           ORDER BY available_date ASC, time_slot ASC";
            
            $schedules_rs = $mysqli->query($schedule_sql);

            if (mysqli_num_rows($schedules_rs) > 0) {
                $current_date = '';
                while ($schedule = mysqli_fetch_assoc($schedules_rs)) {
                    $date = new DateTime($schedule['available_date']);
                    $formatted_date = $date->format('Y-m-d');
                    $dayName = $date->format('l');
                    $monthName = $date->format('F');
                    $dayNum = $date->format('d');

                    $start = DateTime::createFromFormat('H:i:s', $schedule['time_slot']);
                    $time_slot = $start->format('h:i A');

                    // Check availability
                    $sch_id = $schedule['sch_id'];
                    $booked_sql = "SELECT COUNT(*) AS booked_count FROM appointment 
                                 WHERE sch_id = $sch_id AND status = 'Booked'";
                    $booked_rs = $mysqli->query($booked_sql);
                    $booked_count = mysqli_fetch_assoc($booked_rs)['booked_count'];
                    $remaining = $schedule['max_patient'] - $booked_count;

                    // Determine availability class
                    if ($remaining > 5) {
                        $avail_class = 'availability-high';
                        $avail_text = 'Available';
                    } elseif ($remaining > 0) {
                        $avail_class = 'availability-medium';
                        $avail_text = 'Limited slots';
                    } else {
                        $avail_class = 'availability-low';
                        $avail_text = 'Fully booked';
                    }

                    // Display date header if it's a new date
                    if ($current_date != $formatted_date) {
                        if ($current_date != '') {
                            echo '</div></div>';
                        }
                        $current_date = $formatted_date;
            ?>
                        <div class="date-section">
                            <div class="date-header">
                                <i class="bi bi-calendar-date"></i>
                                <?= $dayName ?>, <?= $monthName ?> <?= $dayNum ?>
                            </div>
                            <div class="time-slots-container">
                                <div class="row g-3">
            <?php
                    }
            ?>
                                <div class="col-lg-4 col-md-6">
                                    <div class="slot-card">
                                        <div class="slot-time">
                                            <i class="bi bi-clock"></i>
                                            <?= $time_slot ?>
                                        </div>
                                        <div class="mb-2">
                                            <span class="availability-indicator <?= $avail_class ?>">
                                                <i class="bi bi-<?= $remaining > 0 ? 'check-circle' : 'x-circle' ?> me-1"></i>
                                                <?= $avail_text ?>
                                            </span>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="max-patients">
                                                <i class="bi bi-people me-1"></i>
                                                <?= $remaining ?>/<?= $schedule['max_patient'] ?> slots
                                            </span>
                                            <?php if ($remaining > 0): ?>
                                                <button type="button" class="btn-book-slot" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#bookModal<?= $schedule['sch_id'] ?>">
                                                    <i class="bi bi-calendar-plus me-1"></i>Book
                                                </button>
                                            <?php else: ?>
                                                <button class="btn-book-slot" disabled>
                                                    <i class="bi bi-calendar-x me-1"></i>Full
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>

                                <!-- Booking Modal -->
                                <div class="modal fade" id="bookModal<?= $schedule['sch_id'] ?>" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">
                                                    <i class="bi bi-calendar-check me-2"></i>
                                                    Confirm Appointment
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form action="book_appointment_2.php" method="post">
                                                <div class="modal-body">
                                                    <div class="appointment-summary">
                                                        <div class="summary-item">
                                                            <span><strong>Doctor:</strong> Dr. <?= $doctor['name'] ?></span>
                                                        </div>
                                                        <div class="summary-item">
                                                            <span><strong>Date:</strong> <?= $dayName ?>, <?= $monthName ?> <?= $dayNum ?></span>
                                                        </div>
                                                        <div class="summary-item">
                                                            <span><strong>Time:</strong> <?= $time_slot ?></span>
                                                        </div>
                                                        <?php
                                                        $current_booked = $mysqli->query("SELECT COUNT(*) AS total FROM appointment WHERE sch_id = {$schedule['sch_id']} AND status = 'Booked'");
                                                        $current_count = $current_booked->fetch_assoc()['total'];
                                                        $queue_position = $current_count + 1;
                                                        ?>
                                                        <div class="summary-item">
                                                            <span><strong>Queue Position:</strong> #<?= $queue_position ?></span>
                                                        </div>
                                                    </div>
                                                    
                                                    <input type="hidden" name="doctor_id" value="<?= $doctor_id ?>">
                                                    <input type="hidden" name="patient_id" value="<?= $patient_id ?>">
                                                    <input type="hidden" name="sch_id" value="<?= $schedule['sch_id'] ?>">
                                                    
                                                    <div class="mb-3">
                                                        <label for="note<?= $schedule['sch_id'] ?>" class="form-label fw-medium">
                                                        </label>
                                                        <textarea class="form-control" id="note<?= $schedule['sch_id'] ?>" 
                                                                name="note" rows="3" 
                                                                placeholder="Any notes for the doctor..."></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                        Cancel
                                                    </button>
                                                    <button type="submit" class="btn btn-success">
                                                        <i class="bi bi-check-circle me-1"></i>Confirm Booking
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
            <?php
                }
                // Close the last date section
                if ($current_date != '') {
                    echo '</div></div></div>';
                }
            } else {
            ?>
                <div class="text-center py-5">
                    <i class="bi bi-calendar-x display-1 text-muted"></i>
                    <h4 class="text-muted mt-3">No Available Slots</h4>
                    <p class="text-muted mb-4">This doctor doesn't have any available time slots at the moment.</p>
                    <a href="doctor_schedules.php" class="btn btn-primary">
                        <i class="bi bi-arrow-left me-2"></i>Back to Doctors
                    </a>
                </div>
            <?php
            }
            ?>
        </div>
    </div>
</div>

</body>
</html>