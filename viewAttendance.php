<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="icon" href="https://pariksit.com/img/logo.png" type="image/x-icon">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Attendance</title>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"
        integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
    <!-- Bootstrap CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.3.0/mdb.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css" />
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/buttons/2.0.1/css/buttons.dataTables.min.css" />
    <style>
        div.dt-buttons {
            position: relative;
            float: left;
            margin-left: 10px;
        }
    </style>

</head>

<body>
    <div class="wrapper">
        <?php include "./includes/navbar.php"; ?>

        <div class="container">
            <h1 style="text-align:center;margin-top:8%">View Attendance</h1>
            <?php
            date_default_timezone_set('Asia/Kolkata');

            // Establish database connections
            // $conn = mysqli_connect("localhost", "root", "", "oep_generic");
            // $conn2 = mysqli_connect("localhost", "root", "", "rfid_attendance");
            include 'api/conn.php';
            include 'api/conn2.php';
            $selectedClass = $_POST['selectedClass'] ?? null;
            $selectedDate = $_POST['selectedDate'] ?? date("Y-m-d");
            $absentees = []; // This array will hold the absentees data
            
            ?>

            <div class="card p-3 mt-5 mb-5 shadow">
                <form method="post" action="" class="row g-3 align-items-center mb-5">
                    <div class="col-md-4">
                        <label for="selectedClass" class="form-label">Select Class:</label>
                        <select id="selectedClass" name="selectedClass" class="form-select">
                            <!-- Assuming classes are from 1 to 12, modify if needed -->
                            <?php for ($i = 8; $i <= 13; $i++) { ?>
                                <option value="<?php echo $i; ?>" <?php echo ($i == $selectedClass) ? 'selected' : ''; ?>>
                                    <?php echo $i; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="selectedDate" class="form-label">Select Date:</label>
                        <input type="date" id="selectedDate" name="selectedDate" value="<?php echo $selectedDate; ?>"
                            class="form-control">
                    </div>
                    <div class="col-md-4 d-grid gap-2 mt-5">
                        <button type="submit" class="btn btn-primary shadow">View Attendance</button>
                    </div>

                </form>

                <div class="table-responsive p-3 ">
                    <table id="datatable-buttons" class="table table-striped">
                        <thead>
                            <tr>
                                <th>Roll NO</th>
                                <th>Name</th>
                                <th>Batch</th>
                                <th>In-Time</th>
                                <th>Out-Time</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                            if ($selectedClass !== null) {
                                $tableName = $selectedClass . "th";

                                // Query to fetch all students from selected class
                                $query = "SELECT * FROM `credentials` WHERE `std` = '$selectedClass' and `registeredby`='PARIKSIT' and `rfid_id`!=0";
                                $result = mysqli_query($conn, $query);

                                while ($row = mysqli_fetch_assoc($result)) {
                                    $userId = $row['userid'];
                                    $username = $row['username'];
                                    $studentGroup = $row['studentgroup'];
                                    $rfid_id = $row['rfid_id'];

                                    // Query to fetch attendance data from respective table
                                    $attendanceQuery = "SELECT * FROM `$tableName` WHERE `onDate` = '$selectedDate'";
                                    $attendanceResult = mysqli_query($conn2, $attendanceQuery);

                                    if (mysqli_num_rows($attendanceResult) > 0) {
                                        $attendanceData = mysqli_fetch_assoc($attendanceResult);
                                        $studentsArray = json_decode($attendanceData['students'], true);

                                        // Check if attendance data exists for this student
                                        // Check if attendance data exists for this student
                                        if (
                                            isset($studentsArray['students'][$rfid_id]) &&
                                            !empty($studentsArray['students'][$rfid_id]['in_time'])
                                        ) {
                                            $inTime = $studentsArray['students'][$rfid_id]['in_time'];
                                            $outTime = $studentsArray['students'][$rfid_id]['out_time'];
                                        } else {
                                            $absentees[] = [
                                                'userid' => $userId,
                                                'username' => $username,
                                                'studentgroup' => $studentGroup
                                            ];

                                            continue;
                                        }


                                        // Print data in table row
                                        echo "<tr>";
                                        echo "<td>$userId</td>";
                                        echo "<td>$username</td>";
                                        echo "<td>$studentGroup</td>";
                                        echo "<td>$inTime</td>";
                                        echo "<td>$outTime</td>";
                                        echo "</tr>";
                                    }
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <button id="downloadAbsentees" class="btn shadow btn-danger">Download Absentees List</button>
            </div>

        </div>
        <script>
            $(document).ready(function () {
                var absentees = <?php echo json_encode($absentees); ?>; // This gets the PHP array of absentees into JS

                $('#downloadAbsentees').click(function () {
                    var csvContent = "data:text/csv;charset=utf-8,"
                        + "Roll No, Name, Batch\n"
                        + absentees.map(function (item) {
                            return item.userid + "," + item.username + "," + item.studentgroup;
                        }).join("\n");

                    var encodedUri = encodeURI(csvContent);
                    var link = document.createElement("a");
                    link.setAttribute("href", encodedUri);
                    link.setAttribute("download", "absentees.csv");
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                });
            });
        </script>
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

    <!-- DataTables and Buttons JavaScript -->
    <script type="text/javascript" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.0.1/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.html5.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#datatable-buttons').DataTable({

                dom: "<'row'<'col-md-6'B><'col-md-6'f>>" +
                    "<'row'<'col-md-12't>>" +
                    "<'row'<'col-md-6'i><'col-md-6'p>>",
                language: {
                    search: "",
                    searchPlaceholder: "Search...",
                },
                buttons: [
                    { extend: 'copy', text: 'Copy' },
                    { extend: 'excel', text: 'Excel' },
                    { extend: 'pdf', text: 'PDF' }
                ],
                lengthChange: true, // Enable "Show entries" dropdown
                // Add the following two lines to align the elements correctly
                initComplete: function () {
                    $('#datatable-buttons_wrapper').removeClass('row');
                    $('#datatable-buttons_wrapper .col-md-6:last-child').addClass('float-end');
                }
            });
        });
    </script>
</body>

</html>