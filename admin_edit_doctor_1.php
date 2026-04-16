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
    header("Location: admin_view_doctors.php");
    exit();
}

// Get form data
$doctor_id = intval($_POST['doctor_id'] ?? 0);
$user_id = intval($_POST['user_id'] ?? 0);
$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$gender = $_POST['gender'] ?? '';
$sp_id = intval($_POST['sp_id'] ?? 0);
$access_code = trim($_POST['access_code'] ?? '');

// Validate required fields
if (empty($doctor_id) || empty($user_id) || empty($name) || empty($email) || empty($sp_id)) {
    header("Location: admin_edit_doctor.php?id=$doctor_id&error=" . urlencode("Please fill all required fields."));
    exit();
}

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header("Location: admin_edit_doctor.php?id=$doctor_id&error=" . urlencode("Invalid email format."));
    exit();
}

// Check if email already exists (excluding current doctor)
$check_email = $mysqli->prepare("SELECT user_id FROM user WHERE email = ? AND user_id != ?");
$check_email->bind_param("si", $email, $user_id);
$check_email->execute();
$result = $check_email->get_result();

if ($result->num_rows > 0) {
    header("Location: admin_edit_doctor.php?id=$doctor_id&error=" . urlencode("Email already exists!"));
    exit();
}

// Begin transaction
$mysqli->begin_transaction();

try {
    // Update doctor table
    $update_doctor = $mysqli->prepare("UPDATE doctor SET name = ?, phone = ?, gender = ?, sp_id = ? WHERE doctor_id = ?");
    $update_doctor->bind_param("sssii", $name, $phone, $gender, $sp_id, $doctor_id);
    
    if (!$update_doctor->execute()) {
        throw new Exception("Error updating doctor details");
    }

    // Update user table
    if (!empty($access_code)) {
        // Update both email and password
        $hashed_password = password_hash($access_code, PASSWORD_DEFAULT);
        $update_user = $mysqli->prepare("UPDATE user SET email = ?, access_code = ? WHERE user_id = ?");
        $update_user->bind_param("ssi", $email, $hashed_password, $user_id);
    } else {
        // Update only email
        $update_user = $mysqli->prepare("UPDATE user SET email = ? WHERE user_id = ?");
        $update_user->bind_param("si", $email, $user_id);
    }

    if (!$update_user->execute()) {
        throw new Exception("Error updating user account");
    }

    // Commit transaction
    $mysqli->commit();
    
    // Success - redirect
    header("Location: admin_view_doctors.php?success=" . urlencode("Doctor updated successfully!"));
    exit();

} catch (Exception $e) {
    // Rollback transaction on error
    $mysqli->rollback();
    
    // Redirect with error message
    header("Location: admin_edit_doctor.php?id=$doctor_id&error=" . urlencode($e->getMessage()));
    exit();
}
?>