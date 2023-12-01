<?php 
   session_start();

   include("php/config.php");
   if(!isset($_SESSION['valid'])){
    header("Location: index.php");
   }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style\ticket.css">
    
    <title>Document</title>
    
</head>

<body>  <div class="box">
    <?php
    include("php/config.php");
    $id = $_GET['id'];
    $query = mysqli_query($con,"SELECT * FROM appointments WHERE AppointmentID = '$id'") or die("Error Occured");
    $result = mysqli_fetch_assoc($query);
    $patientName = $result['PatientName'];
    $doctorID = $result['DoctorID'];
    $date = $result['AppointmentDate'];
    $time = $result['AppointmentTime'];
    $serialNo = $result['SerialNo'];
    $query = mysqli_query($con,"SELECT Name FROM doctors WHERE DoctorID = '$doctorID'") or die("Error Occured");
    $result = mysqli_fetch_assoc($query);
    $doctorName = $result['Name'];



    ?>

    <div class='inner'>
    <h1>Doctor Dako</h1>
    <p class="ap">Appointment Ticket</p>
    <br>
    <p>Doctor: <span class="bold"><?php echo $doctorName ?></span></p>
    <p>Patient: <span class="bold"><?php echo $patientName ?></span></p>
    <br>
    <div class='info clearfix'>
      <div class='wp'>Serial NO<h2><?php echo $serialNo ?></h2></div>
      <div class='wp'>Date<h2><?php echo $date ?></h2></div>
      <div class='wp'>Time<h2><?php echo $time ?></h2></div>
    </div>
    <div class='total clearfix'>
      <h2><p>Thank you</p></h2>
    </div>
    </div>
  </div>
</body>
</html>