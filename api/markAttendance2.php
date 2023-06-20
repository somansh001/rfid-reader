<?php

// Assuming you have a database connection established
// Replace the database credentials with your own
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "oep_generic";

// Create a PDO instance
try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // Set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    // Handle database connection errors
    echo "Connection failed: " . $e->getMessage();
    exit;
}

// Check if the RFID ID is provided
if (isset($_POST['rfid_id'])) {
    $rfid_id = $_POST['rfid_id'];

    // Check if the RFID ID exists in the database
    $stmt = $conn->prepare("SELECT * FROM credentials WHERE rfid_id = :rfid_id");
    $stmt->bindParam(':rfid_id', $rfid_id);
    $stmt->execute();

    $student = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($student) {
        // Get the current date and time
        $currentDateTime = time();

        // Insert the attendance record into the database
        $stmt = $conn->prepare("INSERT INTO attendance (userid,username,attendance_time,phnumber,std,studentgroup) VALUES (:userid,:username,:attendance_time,:phnumber,:std,:studentgroup)");
        $stmt->bindParam(':userid', $student['userid']);
        $stmt->bindParam(':username', $student['username']);
        $stmt->bindParam(':attendance_time', $currentDateTime);
        $stmt->bindParam(':phnumber', $student['phnumber']);
        $stmt->bindParam(':std', $student['std']);
        $stmt->bindParam(':studentgroup', $student['studentgroup']);

        if ($stmt->execute()) {
            $response = array('status' => 'OK', 'username' => $student['username']);
            echo json_encode($response);
        } else {
            $response = array('status' => 'ERROR');
            echo json_encode($response);
        }
    } else {
        $response = array('status' => 'NOT_FOUND');
        echo json_encode($response);
    }
} else {
    $response = array('status' => 'INVALID_REQUEST');
    echo json_encode($response);
}

// Close the database connection
$conn = null;
?>
