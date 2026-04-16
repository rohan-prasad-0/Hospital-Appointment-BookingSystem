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
    header("Location: admin_view_receptionists.php");
    exit();
}

// Get form data
$recep_id = intval($_POST['recep_id'] ?? 0);
$user_id = intval($_POST['user_id'] ?? 0);
$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$gender = $_POST['gender'] ?? '';
$access_code = trim($_POST['access_code'] ?? '');

// Validate required fields
if (empty($recep_id) || empty($user_id) || empty($name) || empty($email)) {
    header("Location: admin_edit_receptionist.php?id=$recep_id&error=" . urlencode("Please fill all required fields."));
    exit();
}

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header("Location: admin_edit_receptionist.php?id=$recep_id&error=" . urlencode("Invalid email format."));
    exit();
}

// Check if email already exists (excluding current receptionist)
$check_email = $mysqli->prepare("SELECT user_id FROM user WHERE email = ? AND user_id != ?");
$check_email->bind_param("si", $email, $user_id);
$check_email->execute();
$result = $check_email->get_result();

if ($result->num_rows > 0) {
    header("Location: admin_edit_receptionist.php?id=$recep_id&error=" . urlencode("Email already exists!"));
    exit();
}

// Begin transaction
$mysqli->begin_transaction();

try {
    // Update receptionist table
    $update_receptionist = $mysqli->prepare("UPDATE receptionist SET name = ?, phone = ?, gender = ? WHERE recep_id = ?");
    $update_receptionist->bind_param("sssi", $name, $phone, $gender, $recep_id);
    
    if (!$update_receptionist->execute()) {
        throw new Exception("Error updating receptionist details");
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
    header("Location: admin_view_receptionists.php?success=" . urlencode("Receptionist updated successfully!"));
    exit();

} catch (Exception $e) {
    // Rollback transaction on error
    $mysqli->rollback();
    
    // Redirect with error message
    header("Location: admin_edit_receptionist.php?id=$recep_id&error=" . urlencode($e->getMessage()));
    exit();
}
?>