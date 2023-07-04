<?php
$conn2 = mysqli_connect("localhost","root","","rfid_attendance");
    if(isset($_POST['date'])){
        $date = $_POST['date'];
        $class = $_POST['class'];
        $query = mysqli_query($conn2,"SELECT `students` FROM `$class` WHERE `onDate`='$date';");
        
        // echo $query;
        $numRows = mysqli_num_rows($query);
        if ($numRows>0){
            $data = mysqli_fetch_assoc($query);
            $studentsJson = $data['students'];
            echo json_encode($studentsJson);

            // $queryStudent = mysqli_query($conn,"SELECT * FROM `credentials` WHERE `rfid_id` = ''")
           

        }
        else{
            $response = array('status' => 'no_data', 'message' => 'no date exist in db');
            echo json_encode($response);
        }
    }
?>