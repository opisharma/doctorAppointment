<?php
// update_appointment.php
// Handles updating an existing doctor's appointment

// DEBUG: show all errors (remove or disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

include __DIR__ . '/php/config.php';

// Initialize variables
$message = '';
$error   = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    // Sanitize inputs
    $appointmentID = intval($_POST['id']);
    $username      = trim(mysqli_real_escape_string($con, $_POST['patientName']));
    $phone         = trim(mysqli_real_escape_string($con, $_POST['phone']));
    $doctor        = intval($_POST['DoctorName']);
    $date          = $_POST['Date'];
    $time          = $_POST['time'];

    // Fetch existing appointment data
    $stmt = $con->prepare("SELECT AppointmentDate, DoctorID FROM appointments WHERE AppointmentID = ?");
    $stmt->bind_param('i', $appointmentID);
    $stmt->execute();
    $stmt->bind_result($oldDate, $oldDoctor);
    if (!$stmt->fetch()) {
        $error = 'Appointment not found.';
    }
    $stmt->close();

    if (!$error) {
        // Check if date or doctor changed
        if ($date !== $oldDate || $doctor !== $oldDoctor) {
            // Count existing appointments for that doctor on the new date
            $stmt = $con->prepare(
                "SELECT COUNT(*) 
                   FROM appointments 
                  WHERE DoctorID = ? 
                    AND AppointmentDate = ?"
            );
            $stmt->bind_param('is', $doctor, $date);
            $stmt->execute();
            $stmt->bind_result($totalAppointments);
            $stmt->fetch();
            $stmt->close();

            $serialNo = $totalAppointments + 1;
            if ($serialNo > 20) {
                $error = 'Sorry! No more appointments available for this doctor on ' 
                         . htmlspecialchars($date) 
                         . '. Please choose another date or doctor.';
            }
        } else {
            // Keep existing serial number
            $serialNo = null;
        }
    }

    if (!$error) {
        // Perform update
        if ($serialNo !== null) {
            // 7 parameters: s, s, i, i, s, s, i
            $stmt = $con->prepare(
                "UPDATE appointments
                    SET PatientName     = ?,
                        PatientPhone    = ?,
                        DoctorID        = ?,
                        serialNo        = ?,
                        AppointmentDate = ?,
                        AppointmentTime = ?
                  WHERE AppointmentID = ?"
            );
            $stmt->bind_param(
                'ssiissi',
                $username,
                $phone,
                $doctor,
                $serialNo,
                $date,
                $time,
                $appointmentID
            );
        } else {
            // 6 parameters: s, s, i, s, s, i
            $stmt = $con->prepare(
                "UPDATE appointments
                    SET PatientName     = ?,
                        PatientPhone    = ?,
                        DoctorID        = ?,
                        AppointmentDate = ?,
                        AppointmentTime = ?
                  WHERE AppointmentID = ?"
            );
            $stmt->bind_param(
                'ssissi',
                $username,
                $phone,
                $doctor,
                $date,
                $time,
                $appointmentID
            );
        }

        if ($stmt->execute()) {
            $message = 'Appointment successfully updated!';
        } else {
            $error = 'Error occurred while updating: ' . $stmt->error;
        }
        $stmt->close();
    }
}

// If not POST or on error, fetch appointment details and doctors list
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || $error) {
    if (!isset($appointmentID) || !$appointmentID) {
        $appointmentID = intval($_GET['id'] ?? 0);
    }

    // Fetch appointment details
    $stmt = $con->prepare(
        "SELECT A.AppointmentID,
                A.PatientName,
                A.PatientPhone,
                A.DoctorID,
                D.Name,
                A.SerialNo,
                A.AppointmentDate,
                A.AppointmentTime
           FROM appointments A
           JOIN doctors D ON A.DoctorID = D.DoctorID
          WHERE A.AppointmentID = ?"
    );
    $stmt->bind_param('i', $appointmentID);
    $stmt->execute();
    $result      = $stmt->get_result();
    $appointment = $result->fetch_assoc();
    $stmt->close();

    // Fetch doctors list
    $doctors = [];
    $res     = $con->query("SELECT DoctorID, Name, Specialization FROM doctors");
    while ($row = $res->fetch_assoc()) {
        $doctors[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Appointment</title>
    <style>
        /* Internal CSS */
        body { font-family: Arial, sans-serif; background: #f0f2f5; margin: 0; padding: 20px; }
        .container { max-width: 500px; margin: 40px auto; background: #fff; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .box { padding: 30px; }
        header { font-size: 1.5em; margin-bottom: 20px; text-align: center; }
        .field { margin-bottom: 15px; }
        .field label { display: block; margin-bottom: 5px; }
        .field input[type="text"],
        .field input[type="date"],
        .field input[type="time"],
        .field select { width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; }
        .btn { display: inline-block; background: #007bff; color: #fff; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; }
        .btn:hover { background: #0056b3; }
        .message { padding: 10px; margin-bottom: 15px; border-radius: 4px; }
        .message.success { background: #d4edda; color: #155724; }
        .message.error   { background: #f8d7da; color: #721c24; }
    </style>
</head>
<body>
<div class="container">
    <div class="box form-box">
        <?php if ($message): ?>
            <div class="message success"><?= htmlspecialchars($message) ?></div>
            <div style="text-align:center;">
                <a href="home.php" class="btn">Go Home</a>
            </div>
        <?php elseif ($error): ?>
            <div class="message error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if (!$message): ?>
            <header>Update Appointment</header>
            <form action="" method="post">
                <input type="hidden" name="id" value="<?= $appointment['AppointmentID'] ?>">

                <div class="field">
                    <label for="patientName">Patient Name</label>
                    <input type="text" id="patientName" name="patientName" required value="<?= htmlspecialchars($appointment['PatientName']) ?>">
                </div>

                <div class="field">
                    <label for="phone">Patient Phone</label>
                    <input type="text" id="phone" name="phone" required value="<?= htmlspecialchars($appointment['PatientPhone']) ?>">
                </div>

                <div class="field">
                    <label for="doctor">Doctor</label>
                    <select id="doctor" name="DoctorName" required>
                        <option value="">Select Doctor</option>
                        <?php foreach ($doctors as $doc): ?>
                            <option value="<?= $doc['DoctorID'] ?>" <?= $doc['DoctorID'] == $appointment['DoctorID'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($doc['Name'] . ' - ' . $doc['Specialization']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="field">
                    <label for="date">Date</label>
                    <input type="date" id="date" name="Date" required value="<?= $appointment['AppointmentDate'] ?>">
                </div>

                <div class="field">
                    <label for="time">Time</label>
                    <input type="time" id="time" name="time" required value="<?= $appointment['AppointmentTime'] ?>">
                </div>

                <div class="field" style="text-align:center;">
                    <input type="submit" class="btn" name="submit" value="Update">
                </div>
            </form>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
