<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Attendance</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/buttons/1.7.1/css/buttons.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>
</head>

<body>
    <div class="wrapper">
        <?php
        include "includes/navbar.php";
        ?>

        <div class="container">
            <h1 style="text-align:center;margin-top:2.5%">View Attendance</h1>
            <?php
            date_default_timezone_set('Asia/Kolkata');

            // Establish database connections
            $conn = mysqli_connect("localhost", "root", "", "oep_generic");
            $conn2 = mysqli_connect("localhost", "root", "", "rfid_attendance");

            $selectedClass = $_POST['selectedClass'] ?? null;
            $selectedDate = $_POST['selectedDate'] ?? date("Y-m-d");
            ?>

            <form method="post" action="">
                <label for="selectedClass">Select Class:</label>
                <select id="selectedClass" name="selectedClass">
                    <!-- Assuming classes are from 1 to 12, modify if needed -->
                    <?php for ($i = 8; $i <= 13; $i++) { ?>
                        <option value="<?php echo $i; ?>" <?php echo ($i == $selectedClass) ? 'selected' : ''; ?>><?php echo $i; ?></option>
                    <?php } ?>
                </select>
                <label for="selectedDate">Select Date:</label>
                <input type="date" id="selectedDate" name="selectedDate" value="<?php echo $selectedDate; ?>">
                <input type="submit" value="View Attendance">
            </form>

            <div class="table-responsive mt-5">
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
                            $query = "SELECT * FROM `credentials` WHERE `std` = '$selectedClass' and `registeredby`='PARIKSIT'";
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
                                    if (isset($studentsArray['students'][$rfid_id])) {
                                        $inTime = $studentsArray['students'][$rfid_id]['in_time'];
                                        $outTime = $studentsArray['students'][$rfid_id]['out_time'];
                                    } else {
                                        $inTime = "Not Recorded";
                                        $outTime = "Not Recorded";
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
        </div>

        <?php
        include "includes/footer.php";
        ?>
    </div>

    <!-- jQuery and DataTables -->
    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#datatable-buttons').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'excel'
                ]
            });
        });
    </script>
</body>

</html>