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

// Process the RFID value
if (isset($_POST['rfid'])) {
    $rfid = $_POST['rfid'];

    // Fetch all RFIDs from the database
    $sql = "SELECT rfid FROM credentials";
    $result = $conn->query($sql);

    $validRFID = false;

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            if ($rfid === $row['rfid']) {
                // RFID is correct
                $_SESSION['logged_in'] = true;
                $validRFID = true;
                break;
            }
        }
    }

    // Close the result set
    $result->free_result();
}

// Return the response as JSON
echo json_encode(['valid' => $validRFID]);

// Close the database connection
$conn->close();
?>
