<?php
session_start();
require_once('db_connection.php');

// Function to sanitize input
function sanitize($data) {
    global $mysqli;
    return mysqli_real_escape_string($mysqli, htmlspecialchars(trim($data)));
}


// Get and sanitize inputs
$name = sanitize($_POST['name']);
$email = sanitize($_POST['email']);
$phone = sanitize($_POST['phone']);
$gender = sanitize($_POST['gender']);
$dob = sanitize($_POST['dob']);
$access_code = password_hash(trim($_POST['access_code']), PASSWORD_DEFAULT);

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header("Location: user_register_1.php?error=invalid_email");
    exit();
}

// Validate date of birth
if (empty($dob)) {
    header("Location: user_register_1.php?error=invalid_dob");
    exit();
}

// Check if email already exists
$check_sql = "SELECT * FROM user WHERE email = '$email'";
$check_result = $mysqli->query($check_sql);

if (mysqli_num_rows($check_result) > 0) {
    header("Location: register_fail.php");
    exit();
}

// Begin transaction
$mysqli->begin_transaction();

try {
    $user_sql = "INSERT INTO user (email, access_code, role) 
                 VALUES ('$email', '$access_code', 'Patient')";
    
    if (!$mysqli->query($user_sql)) {
        throw new Exception("User insertion failed: " . $mysqli->error);
    }
    
    $user_id = $mysqli->insert_id;
    
    $patient_sql = "INSERT INTO patient (name, dob, gender, phone, user_id) 
                    VALUES ('$name', '$dob', '$gender', '$phone', '$user_id')";
    
    if (!$mysqli->query($patient_sql)) {
        throw new Exception("Patient insertion failed: " . $mysqli->error);
    }
    
    // Commit transaction
    $mysqli->commit();
    
    // Set session variables
    $_SESSION['email'] = $email;
    $_SESSION['user_id'] = $user_id;
    $_SESSION['name'] = $name;
    $_SESSION['role'] = 'Patient';
    
    // Redirect to dashboard
    header("Location: patient_dashboard.php?welcome=1");
    exit();
    
} catch (Exception $e) {
    // transaction on error
    $mysqli->rollback();
    
    // If user was inserted but patient failed, delete the user
    if (isset($user_id)) {
        $delete_sql = "DELETE FROM user WHERE user_id = $user_id";
        $mysqli->query($delete_sql);
    }
    
    error_log("Registration error: " . $e->getMessage());
    
    header("Location: user_register_1.php?error=registration_failed");
    exit();
}
?>