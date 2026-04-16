<?php
session_start();
require_once('db_connection.php');

$email = mysqli_real_escape_string($mysqli, trim($_POST['email']));
$access_code = trim($_POST['access_code']);

// Check if user exists
$sql = "SELECT * FROM user WHERE email = '$email'";
$rs = $mysqli->query($sql);

if(mysqli_num_rows($rs) > 0) {
    $row = mysqli_fetch_assoc($rs);
    
    // Verify access code
    if (password_verify($access_code, $row['access_code'])) {

        $_SESSION['email'] = $row['email'];
        $_SESSION['role'] = $row['role'];
        $_SESSION['user_id'] = $row['user_id'];
        
        // Get user-specific data based on role
        $role = $row['role'];
        $user_id = $row['user_id'];
        $table_name = '';
        $redirect_page = '';
        
        // Determine table and redirect based on role
        switch($role) {
            case 'Patient':
                $table_name = 'patient';
                $redirect_page = 'patient_dashboard.php';
                break;
            case 'Doctor':
                $table_name = 'doctor';
                $redirect_page = 'doctor_dashboard.php';
                break;
            case 'Receptionist':
                $table_name = 'receptionist';
                $redirect_page = 'receptionist_dashboard.php';
                break;
            case 'Admin':
                $table_name = 'admin';
                $redirect_page = 'admin_dashboard.php';
                break;
            default:
                error_log("Unknown role: " . $role);
                header("Location: login_failed.php?error=invalid_role");
                exit();
        }
        
        // Get user details
        $user_sql = "SELECT name FROM $table_name WHERE user_id = $user_id";
        $user_rs = $mysqli->query($user_sql);
        
        if ($user_rs && $user_rs->num_rows > 0) {
            $data = $user_rs->fetch_assoc();
            $_SESSION['name'] = $data['name'];
            
            // DEBUG - Add this temporarily to see where it's redirecting
            error_log("Login successful for $email - Redirecting to: $redirect_page");
            
            header("Location: $redirect_page");
            exit();
        } else {
            error_log("User not found in $table_name for user_id: $user_id");
            header("Location: login_failed.php?error=user_not_found");
            exit();
        }
    } else {
        // Invalid password
        error_log("Password verification failed for email: $email");
        header("Location: login_failed.php?error=invalid_credentials");
        exit();
    }
} else {
    // User not found
    error_log("User not found with email: $email");
    header("Location: login_failed.php?error=invalid_credentials");
    exit();
}
?>