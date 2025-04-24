<!-- <?php
      $id = $_GET['id'];
      include("php/config.php");
        $query = mysqli_query($con, "DELETE FROM appointments WHERE AppointmentID = '$id'") or die("Error Occurred");
        header("Location:http://localhost/doctorDako/home.php");
?> -->


<?php
// delete_appointment.php

// 1) DEBUG: show all errors (remove in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 2) Grab and validate the ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die('Invalid appointment ID.');
}
$appointmentID = (int) $_GET['id'];

// 3) Connect
include __DIR__ . '/php/config.php';

// 4) Prepare & execute DELETE
$stmt = $con->prepare("DELETE FROM appointments WHERE AppointmentID = ?");
if (!$stmt) {
    die('Prepare failed: ' . $con->error);
}
$stmt->bind_param('i', $appointmentID);

if (!$stmt->execute()) {
    die('Delete failed: ' . $stmt->error);
}

$stmt->close();

// 5) Redirect back to your home page
//    Uses relative path, so it will look for home.php in the same folder.
header('Location: home.php');
exit;
