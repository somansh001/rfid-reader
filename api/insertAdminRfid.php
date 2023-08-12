<?php
// Establish database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "rfid_attendance";
// include 'config2.php';
// Check if the connection was successful
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables
$rfid = '';
$response = '';

// Check if RFID is submitted
if (isset($_POST['rfid'])) {
    // Retrieve the RFID value from the input field
    $rfid = $_POST['rfid'];

    // Check if the RFID already exists in the database
    $stmt = $conn->prepare("SELECT rfid FROM credentials WHERE rfid = ?");
    $stmt->bind_param("s", $rfid);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // RFID already exists
        $response = "exists";
    } else {
        // RFID does not exist, insert into the database
        $stmt = $conn->prepare("INSERT INTO credentials (rfid) VALUES (?)");
        $stmt->bind_param("s", $rfid);
        if ($stmt->execute()) {
            // RFID inserted successfully
            $response = "success";
        } else {
            // Failed to insert RFID
            $response = "error";
        }
    }

    // Close the statement and result set
    $stmt->close();
    $result->close();
}

// Close the database connection
$conn->close();

// Return the response as JSON
echo $response;
?>