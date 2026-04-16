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

// Get specializations for filter
$spec_sql = "SELECT * FROM specialization ORDER BY sp_name";
$spec_rs = $mysqli->query($spec_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Doctors - ABC Hospital</title>
    
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
            <div class="page-header d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <button class="menu-btn d-lg-none me-3" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarOffcanvas">
                        <i class="bi bi-list"></i>
                    </button>
                    <div>
                        <h4 class="fw-bold">
                            <i class="bi bi-search text-primary me-2"></i>
                            Search Doctors
                        </h4>
                    </div>
                </div>
            </div>

            <!-- Search Filter Card -->
            <div class="filter-card">
                <form method="GET" action="" class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Doctor Name</label>
                        <input type="text" name="doctor_name" class="form-control" 
                               placeholder="Enter doctor name" value="<?= $_GET['doctor_name'] ?? '' ?>">
                    </div>
                    
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Specialization</label>
                        <select name="specialization" class="form-select">
                            <option value="">All Specializations</option>
                            <?php 
                            mysqli_data_seek($spec_rs, 0);
                            while ($spec = mysqli_fetch_assoc($spec_rs)): 
                                $selected = ($_GET['specialization'] ?? '') == $spec['sp_id'] ? 'selected' : '';
                            ?>
                                <option value="<?= $spec['sp_id'] ?>" <?= $selected ?>>
                                    <?= $spec['sp_name'] ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Date</label>
                        <input type="date" name="date" class="form-control" 
                               value="<?= $_GET['date'] ?? '' ?>" min="<?= date('Y-m-d') ?>">
                    </div>
                    
                    <div class="col-12 text-center">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-search me-2"></i>Search Doctors
                        </button>
                        <a href="receptionist_doctor_view.php" class="btn btn-outline-secondary px-4">
                            <i class="bi bi-arrow-clockwise me-2"></i>Reset
                        </a>
                    </div>
                </form>
            </div>

            <?php
            $doctor_name = trim($_GET['doctor_name'] ?? '');
            $specialization = $_GET['specialization'] ?? '';
            $date_filter = $_GET['date'] ?? '';

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
            ?>
                <div class="row g-4">
                    <?php while ($doctor = mysqli_fetch_assoc($doctors_rs)): 
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
                        <div class="col-md-6 col-lg-4">
                            <div class="doctor-card">
                                <div class="text-center mb-4">
                                    <h5 class="fw-bold mb-1">Dr. <?= $doctor['doctor_name'] ?></h5>
                                    <span class="specialization-badge">
                                        <i class="bi bi-stethoscope me-1"></i><?= $doctor['sp_name'] ?>
                                    </span>
                                </div>
                                
                                <div class="mt-4">
                                    <h6 class="fw-semibold mb-2">Available Time Slots</h6>
                                    
                                    <?php if (mysqli_num_rows($schedules_rs) > 0): ?>
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
                                        ?>
                                            <div class="time-slot-item">
                                                <div class="row align-items-center">
                                                    <div class="col-8">
                                                        <small class="text-muted"><?= $dayName ?>, <?= $formattedDate ?></small>
                                                        <div class="fw-semibold"><?= $time_slot ?></div>
                                                        <span class="<?= $remaining > 0 ? 'slots-available' : 'slots-full' ?>">
                                                            <i class="bi bi-<?= $remaining > 0 ? 'check-circle' : 'x-circle' ?> me-1"></i>
                                                            <?= $remaining > 0 ? "$remaining slots left" : 'Full' ?>
                                                        </span>
                                                    </div>
                                                    <div class="col-4 text-end">
                                                        <a href="receptionist_view_appointments.php?doctor_id=<?= $doctor_id ?>" 
                                                           class="btn btn-sm btn-outline-primary">
                                                            View
                                                        </a>
                                                    </div>
                                                    
                                                </div>
                                                <hr>
                                            </div>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <p class="text-muted text-center py-2">No available schedules</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <!-- No Results Found -->
                <div class="empty-state">
                    <i class="bi bi-search display-1 text-muted"></i>
                    <h4 class="text-muted mt-3">No Doctors Found</h4>
                    <p class="text-muted">No doctors match your search criteria. Try adjusting your filters.</p>
                    <a href="receptionist_doctor_view.php" class="btn btn-primary">
                        <i class="bi bi-arrow-clockwise me-2"></i>Clear Filters
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>