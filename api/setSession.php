<?php
session_start();

if (isset($_POST['rfid'])) {
    $rfid = $_POST['rfid'];

    // Set the session variables
    $_SESSION['logged_in'] = true;
    $_SESSION['rfid'] = $rfid;

    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}
?>
