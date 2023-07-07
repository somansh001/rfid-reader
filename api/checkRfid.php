<?php
session_start();

// Establish database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "rfid_attendance";

// Check if the connection was successful
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch the correct RFID from the database
$sql = "SELECT rfid FROM credentials"; // Modify the query based on your database structure
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $correctRFID = $row['rfid'];

    // Process the RFID value
    if (isset($_POST['rfid'])) {
        $rfid = $_POST['rfid'];

        // Check if the entered RFID matches the correct RFID
        if ($rfid === $correctRFID) {
            // RFID is correct
            $_SESSION['logged_in'] = true;
            echo json_encode(['valid' => true]);
        } else {
            // RFID is incorrect
            echo json_encode(['valid' => false]);
        }
    }
} else {
    // RFID not found in the database
    echo json_encode(['valid' => false]);
}

// Close the database connection
$conn->close();
?>
