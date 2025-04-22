<?php
      $id = $_GET['id'];
      include("php/config.php");
        $query = mysqli_query($con, "DELETE FROM appointments WHERE AppointmentID = '$id'") or die("Erroe Occured");
        header("Location:http://localhost/doctorDako/home.php");
?>