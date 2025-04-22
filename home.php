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
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://kit.fontawesome.com/dfa05d603e.js" crossorigin="anonymous"></script>
    <title>Home</title>
</head>
<body>
    <div class="nav">
        <div class="logo">
            <p><a href="home.php">Doctor Dako</a> </p>
        </div>

        <div class="right-links">
            <?php 
            $id = $_SESSION['id'];
            $query = mysqli_query($con, "SELECT * FROM users WHERE Id=$id");

            while($result = mysqli_fetch_assoc($query)){
                $res_Uname = $result['Username'];
                $res_Age = $result['Age'];
                $res_id = $result['Id'];
            }
            
            
            echo "<a href='edit.php?Id=$res_id'> <button class='btn'><i class='fa-solid fa-user'></i></button> </a>";
            echo "<a href='doctor.php'> <button class='btn'><i class='fa-solid fa-hospital-user'></i></button> </a>";
            echo "<a href='php/logout.php'> <button class='btn'><i class='fa-solid fa-right-from-bracket'></i></button> </a>";
            ?>
            
        </div>
    </div>
    <main>
        <div class="main-box top">
            <div class="top">
                <div class="box">
                    <p>Hello <b><?php echo $res_Uname ?></b>, Welcome</p>
                </div>
                <a href="appointment.php"> <button class="btn">Create An Appointment</button> </a>
            </div>
            <div class="bottom">
                <div class="box">
                <div class="appointment-table">
            <h2>Appointments</h2> <form class="frm" action="" method="post"><input type="date" name="date"> <input type="text" name="name" placeholder="Doctor Name"> <button class= 'sb' type="submit"><i class="fa-solid fa-magnifying-glass"></i></button> </form>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Patient Name</th>
                        <th>Patient Phone</th>
                        <th>Doctor Name</th>
                        <th>Serial No</th>
                        <th>Appointment Date</th>
                        <th>Appointment Time</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if(isset($_POST['name']) || isset($_POST['date'])){
                        $name = $_POST['name']?? "";
                        $date = $_POST['date']?? "";

                        $query = mysqli_query($con, "SELECT 
                        A.AppointmentID,
                        A.PatientName,
                        A.PatientPhone,
                        D.Name,
                        A.SerialNo,
                        A.AppointmentDate,
                        A.AppointmentTime
                    FROM
                        appointments A
                    JOIN
                        Doctors D ON A.DoctorID = D.DoctorID
                    WHERE
                        D.Name = '$name' or A.AppointmentDate = '$date';");
                        
                        $appointments = mysqli_fetch_all($query, MYSQLI_ASSOC);
                        foreach ($appointments as $appointment) {
                            echo "<tr>";
                            echo "<td>{$appointment['AppointmentID']}</td>";
                            echo "<td>{$appointment['PatientName']}</td>";
                            echo "<td>{$appointment['PatientPhone']}</td>";
                            echo "<td>{$appointment['Name']}</td>";
                            echo "<td>{$appointment['SerialNo']}</td>";
                            echo "<td>{$appointment['AppointmentDate']}</td>";
                            echo "<td>{$appointment['AppointmentTime']}</td>";
                    
                            echo "<td>";
                            echo "<a href='update.php?id={$appointment['AppointmentID']}'><i class='fas fa-edit' title='Edit'></i></a>";
                            echo "&nbsp;&nbsp;";
                            echo "&nbsp;&nbsp;";
                            echo "<a href='delete.php?id={$appointment['AppointmentID']}'><i class='fas fa-trash-alt' title='Delete'></i></a>";
                            echo "</td>";
                            echo "</tr>";
                        }
                    }
                
                    else{
                    $query = mysqli_query($con, "SELECT 
                    A.AppointmentID,
                    A.PatientName,
                    A.PatientPhone,
                    D.Name,
                    A.SerialNo,
                    A.AppointmentDate,
                    A.AppointmentTime
                FROM 
                    appointments A
                JOIN 
                    doctors D ON A.DoctorID = D.DoctorID;");
                    $appointments = mysqli_fetch_all($query, MYSQLI_ASSOC);

                    foreach ($appointments as $appointment) {
                        echo "<tr>";
                        echo "<td>{$appointment['AppointmentID']}</td>";
                        echo "<td>{$appointment['PatientName']}</td>";
                        echo "<td>{$appointment['PatientPhone']}</td>";
                        echo "<td>{$appointment['Name']}</td>";
                        echo "<td>{$appointment['SerialNo']}</td>";
                        echo "<td>{$appointment['AppointmentDate']}</td>";
                        echo "<td>{$appointment['AppointmentTime']}</td>";
                
                        echo "<td>";
                        echo "<a href='update.php?id={$appointment['AppointmentID']}'><i class='fas fa-edit' title='Edit'></i></a>";
                        echo "<a href='delete.php?id={$appointment['AppointmentID']}'><i class='fas fa-trash-alt' title='Delete'></i></a>";
                        echo "<a href='print.php?id={$appointment['AppointmentID']}'><i class='fa-solid fa-print'></i></a>";
                        echo "</td>";
                        echo "</tr>";
                    }
                }
                    ?>
                </tbody>
            </table>
        </div>
                </div>
            </div>
        </div>

        
    </main>
</body>
</html>
