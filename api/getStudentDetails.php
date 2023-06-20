<?php
    include 'conn.php';
    if(isset($_POST['rno'])){
        $rno = $_POST['rno'];
        $getStudentDetails = mysqli_query($conn,"SELECT `username`,`rfid_id`,`studentgroup`,`phnumber` FROM `credentials` WHERE `userid` = '$rno';");
        if(mysqli_num_rows($getStudentDetails)>0){
            $studentDetails = mysqli_fetch_assoc($getStudentDetails);
            $studentDetails['status'] = "OK";
        }else{
            $studentDetails['status'] = "NA";
        }
        echo json_encode($studentDetails);
    }
    
