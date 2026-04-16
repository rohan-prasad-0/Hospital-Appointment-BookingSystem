<?php
session_start();
require_once "db_connection.php";

// Check if user is logged in as admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header("Location: login.php");
    exit();
}

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: admin_add_user.php");
    exit();
}

// Get and sanitize inputs
$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$gender = $_POST['gender'] ?? '';
$user_type = $_POST['user_type'] ?? '';
$access_code = trim($_POST['access_code'] ?? '');
$sp_id = $_POST['sp_id'] ?? null;

// Validate required fields
if (empty($name) || empty($email) || empty($access_code) || empty($user_type)) {
    header("Location: admin_add_user.php?status=error&message=" . urlencode("Please fill all required fields."));
    exit();
}

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header("Location: admin_add_user.php?status=error&message=" . urlencode("Invalid email format."));
    exit();
}

// Check if email already exists
$check_email = $mysqli->prepare("SELECT user_id FROM user WHERE email = ?");
$check_email->bind_param("s", $email);
$check_email->execute();
$result = $check_email->get_result();

if ($result->num_rows > 0) {
    header("Location: admin_add_user.php?status=error&message=" . urlencode("Email already exists!"));
    exit();
}

// Begin transaction
$mysqli->begin_transaction();

try {
    // Hash password
    $hashed_password = password_hash($access_code, PASSWORD_DEFAULT);

    // Insert into user table
    $user_sql = "INSERT INTO user (email, access_code, role) VALUES (?, ?, ?)";
    $user_stmt = $mysqli->prepare($user_sql);
    $user_stmt->bind_param("sss", $email, $hashed_password, $user_type);
    
    if (!$user_stmt->execute()) {
        throw new Exception("Error creating user account");
    }
    
    $new_user_id = $mysqli->insert_id;

    // Insert into specific role table
    if ($user_type === 'Doctor') {
        if (empty($sp_id)) {
            throw new Exception("Specialization is required for doctors");
        }
        
        $insert_sql = "INSERT INTO doctor (user_id, name, phone, gender, sp_id) 
                       VALUES (?, ?, ?, ?, ?)";
        $insert_stmt = $mysqli->prepare($insert_sql);
        $insert_stmt->bind_param("isssi", $new_user_id, $name, $phone, $gender, $sp_id);
        
    } else if ($user_type === 'Receptionist') {
        $insert_sql = "INSERT INTO receptionist (user_id, name, phone, gender) 
                       VALUES (?, ?, ?, ?)";
        $insert_stmt = $mysqli->prepare($insert_sql);
        $insert_stmt->bind_param("isss", $new_user_id, $name, $phone, $gender);
    } else {
        throw new Exception("Invalid user type");
    }

    if (!$insert_stmt->execute()) {
        throw new Exception("Error adding $user_type details");
    }

    // Commit transaction
    $mysqli->commit();
    
    // Success
    $success_message = "$user_type added successfully!";
    header("Location: admin_add_user.php?status=success&message=" . urlencode($success_message));
    exit();

} catch (Exception $e) {
    // transaction on error
    $mysqli->rollback();
    
    // Redirect with error message
    header("Location: admin_add_user.php?status=error&message=" . urlencode($e->getMessage()));
    exit();
}
?>