<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Attendance</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.3/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.0.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/select/1.3.3/js/dataTables.select.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet" />
    <!-- MDB -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.3.0/mdb.min.css" rel="stylesheet" />
    <!-- Datatables CSS -->
    <link href="https://cdn.datatables.net/1.11.3/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
    <link href="https://cdn.datatables.net/buttons/2.0.1/css/buttons.dataTables.min.css" rel="stylesheet"
        type="text/css" />
</head>

<body>
<div class="wrapper">
    <?php 
    include "includes/navbar.php";
    ?>
    <div class="container">
        <h1 style = "text-align:center;margin-top:2.5%;color:black">View Attendance</h1>
        <div class="container mt-10">
            <div class="row">
                <div class="col-12 col-md-4">
                    <div class="form-group">
                        <label for="dateInput">Select Date</label>
                        <input type="date" class="form-control" id="dateInput" name="date">
                    </div>
                </div>
                <div class="col-12 col-md-4 mt-4">
                    <div class="dropdown">
                        <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Class
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="#">8th</a>
                            <a class="dropdown-item" href="#">9th</a>
                            <a class="dropdown-item" href="#">10th</a>
                            <a class="dropdown-item" href="#">11th</a>
                            <a class="dropdown-item" href="#">12th</a>
                            <a class="dropdown-item" href="#">13th</a>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-4 mt-4">
                    <button id="fetchButton" class="btn btn-primary">Fetch</button>
                </div>
            </div>
            <div class="table-responsive mt-5">
                <table id="datatable-buttons" class="table table-striped">
                    <thead>
                        <tr>
                            <th>Roll NO</th>
                            <th>Name</th>
                            <th>Batch</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php 
    include "includes/footer.php";
    ?>
</div>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.3.0/mdb.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <!-- bootstrap -->
    
    <script>
        $(document).ready(function () {
            // Initialize Bootstrap dropdown
            var dropdownElement = document.querySelector('.dropdown-toggle');
            var dropdown = new bootstrap.Dropdown(dropdownElement);
            var table = $("#datatable-buttons").DataTable({
                responsive: true,
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ]
            });

            function addStudent(rfid, name, batch) {
                var row = $("<tr>");
                row.append($("<td>").text(rfid));
                row.append($("<td>").text(name));
                row.append($("<td>").text(batch));
                table.row.add(row).draw();
            }

            $('.dropdown-item').click(function () {
                var selectedOption = $(this).text();
                dropdownElement.innerText = selectedOption;
            });

            $('#fetchButton').click(function () {
                var dateValue = $('#dateInput').val();
                var classValue = dropdownElement.innerText.trim();
                $.ajax({
                    url: "./api/getAttendanceForDate.php", // Replace with the URL to your PHP script for marking attendance
                    method: "POST",
                    data: {
                        "date": dateValue,
                        "class": classValue
                    },
                    success: function (response) {
                        table.clear().draw();
                        var result = JSON.parse(response);
                        if (result.status == "no_data") {
                            Swal.fire({
                                position: 'center',
                                icon: 'error',
                                title: `No such attendance found`,
                                showConfirmButton: false,
                                timer: 1500
                            });
                        } else {
                            result = JSON.parse(result);
                            rfidArray = result.students;
                            i = 0;
                            while (i < rfidArray.length) {
                                $.ajax({
                                    url: "./api/tableGen.php",
                                    method: "POST",
                                    data: {
                                        "rfid": rfidArray[i],
                                    },
                                    success: function (response) {
                                        var result = JSON.parse(response);
                                        rfids = result.rfid;
                                        name = result.username;
                                        batch = result.batch;
                                        roll = result.roll;
                                        if (result.status = "send_data") {
                                            addStudent(roll, name, batch);
                                        }
                                    }
                                });
                                i++;
                            }
                        }
                    },
                    error: function (xhr, status, error) {
                        console.log(error);
                    }
                });
            });
        });
    </script>
</body>

</html>
