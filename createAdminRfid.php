<?php include './includes/navbar.php'; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Insert Admin RFID</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
</head>

<body>
    <div class="container mt-5">
        <h3>Insert Admin RFID</h3>
        <div>
            <label for="rfid" class="form-label">RFID</label>
            <input type="text" autofocus class="form-control" id="rfid" name="rfid" required>
        </div>
        <button type="button" class="btn btn-primary mt-3" onclick="submitRFID()">Submit</button>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function submitRFID() {
            var rfid = document.getElementById("rfid").value;

            // Send AJAX request to insertAdminRfid.php
            $.ajax({
                type: "POST",
                url: "./api/insertAdminRfid.php",
                data: {
                    rfid: rfid
                },
                success: function(response) {
                    // Show sweet alert message based on the response
                    if (response === "success") {
                        Swal.fire({
                            icon: 'success',
                            text: 'RFID inserted successfully.',
                            timer: 1500, // Duration in milliseconds
                            showConfirmButton: false
                        }).then(function() {
                            // Clear the RFID value
                            document.getElementById("rfid").value = "";
                        });
                    } else if (response === "exists") {
                        Swal.fire({
                            icon: 'warning',
                            text: 'RFID already exists in the database.',
                            timer: 1500, // Duration in milliseconds
                            showConfirmButton: false
                        }).then(function() {
                            // Clear the RFID value
                            document.getElementById("rfid").value = "";
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            text: 'Failed to insert RFID.',
                            timer: 1500, // Duration in milliseconds
                            showConfirmButton: false
                        }).then(function() {
                            // Clear the RFID value
                            document.getElementById("rfid").value = "";
                        });
                    }
                }
            });
        }
        $(document).ready(function() {
        $("#rfid").keydown(function(event) {
            if (event.keyCode === 13) {
                event.preventDefault();
                submitRFID();
            }
        });
    });
    </script>
</body>

</html>
