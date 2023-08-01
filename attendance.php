<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PARIKSIT RFID Attendance</title>
    <!-- <script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script> -->
    <!-- Font Awesome -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="sweetalert2.all.min.js"></script>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet" />
    <!-- MDB -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.3.0/mdb.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <style>
        .container {
            text-align: center;
            margin: 0 auto;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <?php
        include "includes/navbar.php";
        ?>
        <div class="container">
            <h1 style="text-align:center;margin-top:2.5%">Mark Attendance</h1>
            <div class="row my-5 mx-5">
                <div class="col col-lg-6">
                    <div class="form-outline">

                        <input type="text" autofocus id="rfid_id" class="form-control" name="rfid_id" />
                        <label class="form-label" for="rfid_id">RFID ID</label>

                    </div>
                </div>
                <div class="col col-lg-6">
                    <button class="btn btn-primary btn-rounded" id="attendance_btn" type="submit" name="submit">MARK
                        ATTENDANCE</button>
                </div>
            </div>
        </div>
        <?php
        include "includes/footer.php";
        ?>
    </div>

    <!-- MDB -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.3.0/mdb.min.js"></script>
    <!-- bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"
        integrity="sha384-fbbOQedDUMZZ5KreZpsbe1LCZPVmfTnH7ois6mU1QK+m14rQ1l2bGBq41eYeM/fS"
        crossorigin="anonymous"></script>

    <script>
        $(document).ready(() => {
            // Add an event listener to the RFID input field for auto-submit on enter key press
            $("#rfid_id").on('keypress', (e) => {
                if (e.which === 13) {
                    e.preventDefault();
                    $("#attendance_btn").click();
                }
            });

            // Add an event listener to the attendance submit button
            $("#attendance_btn").on('click', () => {
                var rfid_ids = [$("#rfid_id").val()]; // Assuming you have a single input field for RFID ID

                if (rfid_ids[0] == "") {
                    Swal.fire(
                        'Please enter a valid RFID ID',
                        '',
                        'error'
                    );
                } else {
                    // Send the RFID IDs to the server for marking attendance
                    $.ajax({
                        url: "./api/markAttendance.php",
                        method: "POST",
                        data: {
                            "rfid_ids": rfid_ids
                        },
                        success: function (response) {
                            var result = JSON.parse(response);
                            name = result.username;
                            std = result.std;
                            batch = result.batch;

                            if (result.status == "intime_recorded") {
                                Swal.fire({
                                    position: 'center',
                                    icon: 'success',
                                    title: `InTime recorded for ${name} of class ${std} ${batch}`,
                                    showConfirmButton: false,
                                    timer: 1500
                                }).then(() => {
                                    $("#rfid_id").val("");
                                });
                            } else if (result.status == "outtime_recorded") {
                                Swal.fire({
                                    position: 'center',
                                    icon: 'success',
                                    title: `OutTime recorded for ${name} of class ${std} ${batch}`,
                                    showConfirmButton: false,
                                    timer: 1500
                                }).then(() => {
                                    $("#rfid_id").val("");
                                });
                            } else if (result.status == "attendance_taken") {
                                Swal.fire({
                                    position: 'center',
                                    icon: 'info',
                                    title: `Attendance already taken!`,
                                    showConfirmButton: false,
                                    timer: 1500
                                }).then(() => {
                                    $("#rfid_id").val("");
                                });
                            } else if (result.status == "RFID_NOT_FOUND") {
                                Swal.fire({
                                    position: 'center',
                                    icon: 'error',
                                    title: `RFID not registered`,
                                    showConfirmButton: false,
                                    timer: 1500
                                }).then(() => {
                                    $("#rfid_id").val("");
                                });
                            } else {
                                Swal.fire({
                                    position: 'center',
                                    icon: 'error',
                                    title: "Error marking attendance",
                                    showConfirmButton: false,
                                    timer: 1000
                                }).then(() => {
                                    $("#rfid_id").val("");
                                });
                            }
                        }
                    });
                }
            });

        });
    </script>

</body>

</html>