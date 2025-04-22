<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/style.css">
    <title>Create An Appointment</title>
</head>
<body>
      <div class="container">
        <div class="box form-box">

        <?php 
         
         include("php/config.php");
         if(isset($_POST['submit'])){
            $username = $_POST['patientName'];
            $doctor = $_POST['DoctorName'];
            $phone = $_POST['phone'];
            $date = $_POST['Date'];
            $time = $_POST['time'];
            $appointmentID = $_POST['id'];


            
                
                $data = mysqli_query($con,"SELECT AppointmentDate,DoctorID FROM appointments WHERE AppointmentID = '$appointmentID'") or die("Erroe Occured");
                $data = mysqli_fetch_assoc($data);

                $oldDate = $data['AppointmentDate'];
                $olddoctor = $data['DoctorID'];

                if ($_POST['Date'] != $oldDate || $_POST['DoctorName'] != $olddoctor) {
                    $data = mysqli_query($con,"SELECT COUNT(*) AS TotalAppointments FROM appointments WHERE DoctorID='$doctor' AND AppointmentDate='$date'") or die("Erroe Occured");
                    $total = mysqli_fetch_assoc($data);
                    $total = $total['TotalAppointments'];
                    $serialNo = $total + 1;
                    
                    if($serialNo > 20){
                        echo "<div class='message'>
                              <p>Sorry! No more appointments available for this doctor on this date</p>
                          </div> <br>";
                        echo "<a href='home.php'><button class='btn'>Go Home</button>";
                        exit();
                    }
                    mysqli_query($con,"UPDATE appointments SET patientName = '$username', PatientPhone = '$phone',DoctorID = '$doctor', serialNo = '$serialNo', AppointmentDate = '$date', AppointmentTime = '$time' WHERE AppointmentID = '$appointmentID'") or die("Erroe Occured");

                }else{
                    mysqli_query($con,"UPDATE appointments SET patientName = '$username',PatientPhone = '$phone',DoctorID = '$doctor', AppointmentDate = '$date', AppointmentTime = '$time' WHERE AppointmentID = '$appointmentID'") or die("Erroe Occured");

                }

            echo "<div class='message'>
                      <p>Appointment successfully Updated!</p>
                  </div> <br>";
            echo "<a href='home.php'><button class='btn'>Go Home</button>";
         

         }else{
            $id = $_GET['id'];
            $query = mysqli_query($con, "SELECT 
            A.AppointmentID,
            A.PatientName,
            A.PatientPhone,
            A.DoctorID,
            D.Name,
            A.SerialNo,
            A.AppointmentDate,
            A.AppointmentTime
        FROM 
            appointments A
        JOIN 
            Doctors D ON A.DoctorID = D.DoctorID 
        Where A.AppointmentID = $id;");
        $appointments = mysqli_fetch_all($query, MYSQLI_ASSOC);
    

        $query = mysqli_query($con, "SELECT DoctorID, Name,Specialization FROM Doctors");
        $doctors = mysqli_fetch_all($query, MYSQLI_ASSOC);
         
        ?>

            <header>Create An Appointment</header>

            <form action="" method="post">
            <input type="hidden" name="id" value="<?php echo $appointments[0]['AppointmentID']; ?>">
                <div class="field input">
                    <label for="username">Patient Name</label>
                    <input type="text" name="patientName" id="username" autocomplete="off" required value="<?php echo $appointments[0]['PatientName']; ?>">
                </div>
                <div class="field input">
                    <label for="username">Patient Phone</label>
                    <input type="text" name="phone"  autocomplete="off" required value="<?php echo $appointments[0]['PatientPhone']; ?>">
                </div>

                <div class="field input">
                    <label for="doctor">Doctor Name</label>
                    <select name="DoctorName" id="doctor" required>
                        <option value="">Select Doctor</option>
                        <?php foreach ($doctors as $doctor) { ?>
                            <option value="<?php echo $doctor['DoctorID']; ?> " <?php if($doctor['DoctorID'] == $appointments[0]['DoctorID']){ echo "selected"; } ?>><?php echo $doctor['Name'] . '-' .$doctor['Specialization']; ?></option>
                            
                        <?php } ?>
                    </select>
                </div>
                <div class="field input">
                    <label for="date">Date</label>
                    <input type="date" name="Date" id="date"  value="<?php echo $appointments[0]['AppointmentDate']; ?>" required>
                </div>
                <div class="field input">
                    <label for="">Time</label>
                    <input type="time" name="time" id="datetime" autocomplete="off" value="<?php echo $appointments[0]['AppointmentTime']; ?>" required>
                </div>

                <div class="field">
                    
                    <input type="submit" class="btn" name="submit" value="Update" required>
                </div>
            </form>
        </div>
        <?php } ?>
      </div>
</body>
</html>