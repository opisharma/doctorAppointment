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


           // find total appointments of a doctor from a date
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
            


            mysqli_query($con,"INSERT INTO appointments(patientName,PatientPhone,DoctorID,SerialNo,AppointmentDate,AppointmentTime	) VALUES('$username','$phone','$doctor','$serialNo','$date','$time')") or die("Erroe Occured");

            echo "<div class='message'>
                      <p>Appointment successfully Created!</p>
                  </div> <br>";
            echo "<a href='home.php'><button class='btn'>Go Home</button>";
         

         }else{
            $query = mysqli_query($con, "SELECT DoctorID, Name,Specialization FROM doctors");
            $doctors = mysqli_fetch_all($query, MYSQLI_ASSOC);
         
        ?>

            <header>Create An Appointment</header>

            <form action="" method="post">
                <div style="margin-right: 15px;" class="field input">
                    <label for="username">Patient Name</label>
                    <input type="text" name="patientName" id="username" autocomplete="off" required>
                </div>
                <div style="margin-right: 15px;" class="field input">
                    <label for="username">Patient Phone</label>
                    <input type="text" name="phone" id="username" autocomplete="off" required>
                </div>

                <div class="field input" style="margin-right: 15px; display: flex; flex-direction: column;">
                    <label for="doctor" style="font-weight: bold; margin-bottom: 5px;">Doctor Name</label>
                    <select name="DoctorName" id="doctor" required style="padding: 10px; border: 1px solid #ccc; border-radius: 8px; font-size: 16px;">
                        <option value="" disabled selected>Select Doctor</option>
                        <?php foreach ($doctors as $doctor) { ?>
                            <option value="<?php echo $doctor['DoctorID']; ?>">
                                <?php echo $doctor['Name'] . ' - ' . $doctor['Specialization']; ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
                <div style="margin-right: 15px;" class="field input">
                    <label for="date">Date</label>
                    <input type="date" name="Date" id="date"  required>
                </div>
                <div style="margin-right: 15px;" class="field input">
                    <label for="">Time</label>
                    <input type="time" name="time" id="datetime" autocomplete="off" required>
                </div>

                <div class="field">
                    
                    <input type="submit" class="btn" name="submit" value="Register" required>
                </div>
                <!-- <div class="links">
                    Already a member? <a href="index.php">Sign In</a>
                </div> -->
            </form>
        </div>
        <?php } ?>
      </div>
</body>
</html>