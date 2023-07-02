<!DOCTYPE html>
<html lang="en">
<?php
    include 'header.php'
?>

<head>

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PARIKSIT RFID Attendance</title>

    <!-- Font Awesome -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="sweetalert2.all.min.js"></script>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet" />
    <!-- MDB -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.3.0/mdb.min.css" rel="stylesheet" />
</head>

<body>

    <div class="row my-5 mx-5">
        <div class="col col-lg-6">
            <div class="form-outline">
                <input type="text" autofocus id="rfid_id" class="form-control" />
                <label class="form-label" for="rfid_id">RFID ID</label>
            </div>
        </div>
        <div class="col col-lg-6">
            <button class="btn btn-primary btn-rounded" id="attendance_btn">MARK ATTENDANCE</button>
        </div>
    </div>

    <!-- MDB -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.3.0/mdb.min.js"></script>
    <script>
        $(document).ready(() => {
            // Add an event listener to the attendance submit button
            $("#attendance_btn").on('click', () => {
                var rfid_id = $("#rfid_id").val();
                if (rfid_id == "") {
                    Swal.fire(
                        'Please enter a valid RFID ID',
                        '',
                        'error'
                    );
                } else {
                    // Send the RFID ID to the server for marking attendance
                    $.ajax({
                        url: "./api/markAttendance.php", // Replace with the URL to your PHP script for marking attendance
                        method: "POST",
                        data: {
                            "rfid_id": rfid_id
                        },
                        success: function (response) {
                            var result = JSON.parse(response);
                            name = result.username;

                            if (result.status == "OK") {
                                Swal.fire({
                                    position: 'center',
                                    icon: 'success',
                                    title: `Attendance Marked Successfully for ${name}`,
                                    showConfirmButton: false,
                                    timer: 1500
                                })
                                $("#rfid_id").val("");
                            } else {
                                Swal.fire({
                                    position: 'center',
                                    icon: 'error',
                                    title: "RFID doesn't exist",
                                    showConfirmButton: false,
                                    timer: 1000
                                })
                                $("#rfid_id").val("");
                            }
                        }
                    });
                }
            });
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var inputField = document.getElementById("rfid_id");
            var clickButton = document.getElementById("attendance_btn");

            inputField.addEventListener("keyup", function (event) {
                if (event.keyCode === 13) { // 13 represents the Enter key
                    clickButton.click();
                }
            });

            clickButton.addEventListener("click", function () {
                // Perform the desired button click action here
                console.log("Button clicked!");
            });
        });
    </script>

</body>

</html>