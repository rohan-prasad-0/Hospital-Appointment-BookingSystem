<?php
session_start();
require_once "db_connection.php";

// Check if user is logged in as patient
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Patient') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Get patient name
$p_sql = "SELECT name FROM patient WHERE user_id = $user_id";
$p_rs = $mysqli->query($p_sql);
$p_row = mysqli_fetch_assoc($p_rs);
$patient_name = $p_row['name'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Schedules - ABC Hospital</title>
    
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
    <!-- Top Navigation Bar -->
    <div class="page-header d-flex justify-content-between align-items-center">
        <div>
            <h4 class="fw-bold">
                <i class="bi bi-calendar-week text-primary me-2"></i>
                Doctor Schedules
            </h4>
        </div>
    </div>

    <!-- Search and Filter Section -->
    <div class="filter-card">
        <div class="filter-title">
            <i class="bi bi-funnel"></i>
            <h5>Find Available Doctors</h5>
        </div>
        
        <form action="doctor_schedules.php" method="get" class="row g-4">
            <div class="col-md-4">
                <label class="form-label">
                    <i class="bi bi-person me-1"></i>Doctor Name
                </label>
                <input type="text" name="doctor_name" class="form-control" 
                       placeholder="Search by doctor name" value="<?= $_GET['doctor_name'] ?? '' ?>">
            </div>
            
            <div class="col-md-4">
                <label class="form-label">
                    <i class="bi bi-stethoscope me-1"></i>Specialization
                </label>
                <select name="specialization" class="form-select">
                    <option value="">All Specializations</option>
                    <?php
                    $spec_sql = "SELECT * FROM specialization ORDER BY sp_name";
                    $spec_rs = $mysqli->query($spec_sql);
                    while ($spec = mysqli_fetch_assoc($spec_rs)) {
                        $selected = ($_GET['specialization'] ?? '') == $spec['sp_id'] ? 'selected' : '';
                        echo "<option value='{$spec['sp_id']}' $selected>{$spec['sp_name']}</option>";
                    }
                    ?>
                </select>
            </div>
            
            <div class="col-md-4">
                <label class="form-label">
                    <i class="bi bi-calendar-date me-1"></i>Preferred Date
                </label>
                <input type="date" name="date" class="form-control" 
                       value="<?= $_GET['date'] ?? '' ?>" min="<?= date('Y-m-d') ?>">
            </div>
            
            <div class="col-12 text-center">
                <button type="submit" class="btn-filter me-2">
                    <i class="bi bi-search me-2"></i>Find Schedules
                </button>
                <a href="doctor_schedules.php" class="btn-reset">
                    <i class="bi bi-arrow-clockwise me-2"></i>Reset Filters
                </a>
            </div>
        </form>
    </div>

    <?php
    // Get filter values
    $doctor_name = trim($_GET['doctor_name'] ?? '');
    $specialization = $_GET['specialization'] ?? '';
    $date_filter = $_GET['date'] ?? '';

    // Build query
    $sql = "SELECT DISTINCT d.doctor_id, d.name AS doctor_name, 
                d.gender, s.sp_name
            FROM doctor d
            JOIN specialization s ON d.sp_id = s.sp_id
            JOIN doctor_schedule ds ON d.doctor_id = ds.doctor_id
            WHERE ds.status = 'Available' 
            AND (ds.available_date >= CURDATE())";

    if (!empty($doctor_name)) {
        $doctor_name = $mysqli->real_escape_string($doctor_name);
        $sql .= " AND d.name LIKE '%$doctor_name%'";
    }

    if (!empty($specialization)) {
        $specialization = intval($specialization);
        $sql .= " AND d.sp_id = $specialization";
    }

    if (!empty($date_filter)) {
        $date_filter = $mysqli->real_escape_string($date_filter);
        $sql .= " AND ds.available_date = '$date_filter'";
    }

    $sql .= " ORDER BY d.name ASC";

    $doctors_rs = $mysqli->query($sql);

    if (mysqli_num_rows($doctors_rs) > 0):
        while ($doctor = mysqli_fetch_assoc($doctors_rs)):
            $doctor_id = $doctor['doctor_id'];
            
            // Get schedules for this doctor
            $schedule_sql = "SELECT * FROM doctor_schedule 
                           WHERE doctor_id = $doctor_id 
                           AND status = 'Available' 
                           AND available_date >= CURDATE()";
            
            if (!empty($date_filter)) {
                $schedule_sql .= " AND available_date = '$date_filter'";
            }
            
            $schedule_sql .= " ORDER BY available_date ASC, time_slot ASC";
            $schedules_rs = $mysqli->query($schedule_sql);
    ?>
            <!-- Doctor Card -->
            <div class="doctor-card">
                <div class="row g-0">
                    <div class="col-md-3">
                        <div class="doctor-sidebar">
                            <div class="doctor-avatar-lg ">
                                <i class="bi bi-person-fill"></i>
                            </div>
                            <h4>Dr. <?= $doctor['doctor_name'] ?></h4>
                            <span class="specialization"><?= $doctor['sp_name'] ?></span>
                        </div>
                    </div>
                    
                    <div class="col-md-9">
                        <div class="schedule-section">
                            <div class="schedule-header">
                                <h5>
                                    <i class="bi bi-clock-history"></i>
                                    Available Time Slots
                                </h5>
                                <?php if (mysqli_num_rows($schedules_rs) > 0): ?>
                                    <span class="badge bg-primary"><?= mysqli_num_rows($schedules_rs) ?> slots available</span>
                                <?php endif; ?>
                            </div>
                            
                            <?php if (mysqli_num_rows($schedules_rs) > 0): ?>
                                <div class="row g-3">
                                    <?php while ($schedule = mysqli_fetch_assoc($schedules_rs)): 
                                        $date = new DateTime($schedule['available_date']);
                                        $dayName = $date->format('l');
                                        $formattedDate = $date->format('M d, Y');
                                        
                                        $start = DateTime::createFromFormat('H:i:s', $schedule['time_slot']);
                                        $time_slot = $start->format('h:i A');

                                        // Check availability
                                        $sch_id = $schedule['sch_id'];
                                        $booked_sql = "SELECT COUNT(*) AS booked_count FROM appointment 
                                                     WHERE sch_id = $sch_id AND status = 'Booked'";
                                        $booked_rs = $mysqli->query($booked_sql);
                                        $booked_count = mysqli_fetch_assoc($booked_rs)['booked_count'];
                                        $remaining = $schedule['max_patient'] - $booked_count;
                                        
                                        // availability class
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
                                    ?>
                                        <div class="col-md-6">
                                            <div class="time-slot-card">
                                                <div class="slot-date">
                                                    <i class="bi bi-calendar3"></i> <?= $dayName ?>, <?= $formattedDate ?>
                                                </div>
                                                <div class="slot-time">
                                                    <i class="bi bi-clock"></i> <?= $time_slot ?>
                                                </div>
                                                <div class="slot-availability">
                                                    <span class="availability-badge <?= $avail_class ?>">
                                                        <i class="bi bi-<?= $remaining > 0 ? 'check-circle' : 'x-circle' ?> me-1"></i>
                                                        <?= $avail_text ?> (<?= $remaining ?> slots)
                                                    </span>
                                                </div>
                                                <?php if ($remaining > 0): ?>
                                                    <a href="book_appointment_1.php?doctor_id=<?= $doctor_id ?>&sch_id=<?= $sch_id ?>" class="btn-book">
                                                        <i class="bi bi-calendar-plus me-1"></i>Book Now
                                                    </a>
                                                <?php else: ?>
                                                    <button class="btn-book" disabled>
                                                        <i class="bi bi-calendar-x me-1"></i>Full
                                                    </button>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php endwhile; ?>
                                </div>
                            <?php else: ?>
                                <div class="text-center py-4">
                                    <i class="bi bi-calendar-x display-4 text-muted"></i>
                                    <p class="text-muted mt-2 mb-0">No available schedules for this doctor.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
    <?php
        endwhile;
    else:
    ?>
        <!-- No Results Found -->
        <div class="no-results">
            <i class="bi bi-search"></i>
            <h4>No Doctors Found</h4>
            <p>No doctors match your search criteria. Try adjusting your filters.</p>
            <a href="doctor_schedules.php" class="btn btn-primary">
                <i class="bi bi-arrow-clockwise me-2"></i>View All Schedules
            </a>
        </div>
    <?php
    endif;
    ?>
</div>

</body>
</html>