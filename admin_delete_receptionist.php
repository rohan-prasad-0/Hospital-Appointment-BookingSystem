<?php
session_start();
require_once "db_connection.php";

// Check admin login
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header("Location: login_1.php");
    exit();
}

if (isset($_GET['receptionist_id'])) {
    $recep_id = intval($_GET['receptionist_id']);

    // Get user_id
    $user_result = $mysqli->query("SELECT user_id FROM receptionist WHERE recep_id = $recep_id");
    if ($user_result->num_rows == 0) {
        header("Location: admin_view_receptionists.php?error=Receptionist+not+found");
        exit();
    }

    $user_id = $user_result->fetch_assoc()['user_id'];

    // Delete receptionist and user
    if ($mysqli->query("DELETE FROM receptionist WHERE recep_id = $recep_id") &&
        $mysqli->query("DELETE FROM user WHERE user_id = $user_id")) {
        header("Location: admin_view_receptionists.php?success=Receptionist+deleted+successfully");
        exit();
    } else {
        header("Location: admin_view_receptionists.php?error=Error+deleting+receptionist");
        exit();
    }

} else {
    header("Location: admin_view_receptionists.php");
    exit();
}
?>