<?php
session_start();

include("php/config.php");
if (!isset($_SESSION['valid'])) {
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
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 10px;
            background-color: #f4f4f4;
        }

        .container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
            gap: 5px;

        }

        .card {
            width: 300px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: rgb(204, 219, 232) 3px 3px 6px 0px inset, rgba(255, 255, 255, 0.5) -3px -3px 6px 1px inset;
            padding: 20px;
        }

        .card h3 {
            margin-top: 0;
            font-size: 24px;
            margin-bottom: 5px;
            color: #2196F3;
            
        }

        .card p {
            margin: 5px 0;
        }

        .specialization {
            font-style: italic;
            color: #555;
        }

        .phone,
        .email {
            color: #777;
        }
    </style>
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

            while ($result = mysqli_fetch_assoc($query)) {
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
            <div class="bottom">
                <div class="box">
                    <div class="appointment-table">
                        <h2>Appointments</h2>
                        <form class="frm" action="" method="post"><input type="text" name="name" placeholder="Doctor Name"> <button class='sb' type="submit"><i class="fa-solid fa-magnifying-glass"></i></button> </form>
                        <div class="container">
                            <?php
                            if(isset($_POST['name'])){
                                $name = $_POST['name'];
                                $query = mysqli_query($con, "SELECT* FROM doctors WHERE Name LIKE '%$name%'");
                                $doctors = mysqli_fetch_all($query, MYSQLI_ASSOC);
                                if(count($doctors) == 0){
                                    echo "<h3>No Doctor Found</h3>";
                                }
                                foreach ($doctors as $doctor) { ?>
                                    <div class="card">
                                        <h3><?php echo $doctor['Name']; ?></h3>
                                        <p class="specialization"><?php echo $doctor['Specialization']; ?></p>
                                        <p class="phone">Phone: <?php echo $doctor['ContactNumber']; ?></p>
                                        <p class="email">Email: <?php echo $doctor['Email']; ?></p>
                                    </div>
                                <?php } 

                            }else{
                            
                            $query = mysqli_query($con, "SELECT * FROM doctors");
                            $doctors = mysqli_fetch_all($query, MYSQLI_ASSOC);
                            ?>
                            <?php foreach ($doctors as $doctor) { ?>
                                <div class="card">
                                    <h3><?php echo $doctor['Name']; ?></h3>
                                    <p class="specialization"><?php echo $doctor['Specialization']; ?></p>
                                    <p class="phone">Phone: <?php echo $doctor['ContactNumber']; ?></p>
                                    <p class="email">Email: <?php echo $doctor['Email']; ?></p>
                                </div>
                            <?php } ?>
                            
                            <?php } ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>


    </main>
</body>

</html>