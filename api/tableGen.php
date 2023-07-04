<?php
include "conn.php";
if (isset($_POST['rfid'])){
    $rfid = $_POST['rfid'];
    $query = mysqli_query($conn,"SELECT * FROM `credentials` WHERE `rfid_id` = '$rfid'");
    $studentData = mysqli_fetch_assoc($query);
    $name = $studentData['username'];
    $batch = $studentData['studentgroup'];
    $roll = $studentData['userid'];
    $response = array('status' => 'send_data', 'username' => $name, 'batch' => $batch , 'roll' => $roll);
    echo json_encode($response);
}
?>