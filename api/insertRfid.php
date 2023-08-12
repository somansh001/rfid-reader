<?php

// Assuming you are using MySQL, establish a connection to your database
// $servername = "localhost";
// $username = "root";
// $password = "";
// $dbname = "oep_generic";
include 'config.php';
// Create a new PDO instance
try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Handle any connection errors
    echo "Connection failed: " . $e->getMessage();
    exit();
}

// Check if the RFID ID and Roll Number are provided in the POST data
if (isset($_POST['rfid_id']) && isset($_POST['rno'])) {
    $rfidId = $_POST['rfid_id'];
    $rno = $_POST['rno'];

    // Prepare and execute the SQL statement to update the RFID ID based on the Roll Number
    $stmt = $conn->prepare("UPDATE credentials SET rfid_id = :rfidId WHERE userid = :rno");
    $stmt->bindParam(':rfidId', $rfidId);
    $stmt->bindParam(':rno', $rno);

    if ($stmt->execute()) {
        // Return a success response if the update was successful
        $response = array('status' => 'OK');
        echo json_encode($response);
    } else {
        // Return an error response if the update failed
        $response = array('status' => 'Error');
        echo json_encode($response);
    }
} else {
    // Return an error response if the RFID ID or Roll Number is not provided
    $response = array('status' => 'Error', 'message' => 'RFID ID or Roll Number not provided');
    echo json_encode($response);
}

// Close the database connection
$conn = null;

?>