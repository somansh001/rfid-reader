<?php
date_default_timezone_set('Asia/Kolkata');
error_reporting(E_ALL);
ini_set('display_errors', 1);

$conn = mysqli_connect("localhost", "root", "", "oep_generic");
$conn2 = mysqli_connect("localhost", "root", "", "rfid_attendance");

if (isset($_POST['rfid_ids'])) {
    $rfid_ids = $_POST['rfid_ids'];

    // Check if the RFID IDs are in an array
    if (is_array($rfid_ids)) {
        $attDate = date("Y-m-d");
        // Check if the RFID ID exists in the credentials table
        $idCheck = mysqli_query($conn, "SELECT * FROM `credentials` WHERE `rfid_id` IN ('" . implode("','", $rfid_ids) . "')");
        $numIds = mysqli_num_rows($idCheck);

        if ($numIds === count($rfid_ids)) { // Check if all RFID IDs are found
            $studentData = mysqli_fetch_assoc($idCheck);
            $name = $studentData['username'];
            $std = $studentData['std'];
            $batch = $studentData['studentgroup'];
            $tableName = $std . "th";
            $dateQuery = mysqli_query($conn2, "SELECT * FROM `$tableName` WHERE `onDate` = '$attDate'");
            $numRows = mysqli_num_rows($dateQuery);
            if ($numRows > 0) {
                $data = mysqli_fetch_assoc($dateQuery);
                $studentsArray = json_decode($data['students'], true);
                $rfid_ids_updated = [];

                foreach ($rfid_ids as $rfid_id) {
                    // if this rfid_id already exists
                    if (in_array($rfid_id, array_column($studentsArray['students'], 'rfid_id'))) {
                        // Find the index of the matching student and update out_time
                        $index = array_search($rfid_id, array_column($studentsArray['students'], 'rfid_id'));
                        $studentsArray['students'][$index]['out_time'] = date("H:i:s");
                    } else {
                        // if this rfid_id does not exist in today's attendance, then consider it as in_time
                        array_push($rfid_ids_updated, ['rfid_id' => $rfid_id, 'in_time' => date("H:i:s"), 'out_time' => '']);
                    }
                }
                $studentsArray['students'] = array_merge($studentsArray['students'], $rfid_ids_updated);
                $studentsJson = json_encode($studentsArray);
                $query = "UPDATE `$tableName` SET `students` = '$studentsJson' WHERE `onDate` = '$attDate'";
                $execQuery = mysqli_query($conn2, $query);
            } else {
                $studentsArray = array('students' => []);
                foreach ($rfid_ids as $rfid_id) {
                    array_push($studentsArray['students'], ['rfid_id' => $rfid_id, 'in_time' => date("H:i:s"), 'out_time' => '']);
                }
                $studentsJson = json_encode($studentsArray);
                $query = "INSERT INTO `$tableName`(`onDate`, `students`) VALUES ('$attDate', '$studentsJson')";
                $execQuery = mysqli_query($conn2, $query);
            }
            if ($execQuery) {
                $response = array('status' => 'OK', 'username' => $name, 'std' => $std, 'batch' => $batch);
                echo json_encode($response);
            } else {
                $response = array('status' => 'ERROR', 'message' => 'Failed to update attendance.');
                echo json_encode($response);
            }
        } else {
            $response = array('status' => 'RFID_NOT_FOUND', 'message' => 'One or more RFID IDs not found in credentials.');
            echo json_encode($response);
        }
    } else {
        $response = array('status' => 'INVALID_INPUT', 'message' => 'Invalid input data.');
        echo json_encode($response);
    }
} else {
    $response = array('status' => 'INVALID_REQUEST', 'message' => 'Invalid request.');
    echo json_encode($response);
}
?>