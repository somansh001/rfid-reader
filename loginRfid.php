<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>PARIKSIT RFID ATTENDANCE SYSTEM</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="" name="description" />
    <meta content="PARIKSIT INC." name="author" />

    <!-- App favicon -->
    <link rel="shortcut icon" href="https://media.discordapp.net/attachments/1078997396995985408/1115983582331146352/logo-dark-sm.png">

    <!-- Theme Config Js -->
    <script src="./vendors/assets/js/hyper-config.js"></script>

    <!-- App css -->
    <link href="./vendors/assets/css/app-saas.min.css" rel="stylesheet" type="text/css" id="app-style" />

    <!-- Icons css -->
    <link href="./vendors/assets/css/icons.min.css" rel="stylesheet" type="text/css" />
</head>

<body class="authentication-bg">
    <div class="account-pages pt-2 pt-sm-5 pb-4 pb-sm-5">
        <!-- Page content -->
        <div class="container">
            <!-- Container content -->
            <div class="row justify-content-center">
                <div class="col-xxl-4 col-lg-5">
                    <div class="card">
                        <!-- Card content -->
                        <div class="card-header py-4 text-center bg-primary">
                            <!-- Logo -->
                            <a href="#">
                                <span><img src="https://media.discordapp.net/attachments/1078997396995985408/1115983582838669433/logo.png?width=1170&height=303" alt="logo" height="50"></span>
                            </a>
                        </div>
                        <div class="card-body p-4">
                            <div class="text-center w-75 m-auto">
                                <h4 class="text-dark-50 text-center pb-0 fw-bold">LOG IN</h4>
                            </div>
                            <div class="mb-3">
                                <label for="rfid" class="form-label">RFID</label>
                                <input class="form-control" autofocus type="text" id="rfid" name="rfid" required="" placeholder="Enter your RFID">
                            </div>
                            <div class="mb-3 mb-0 text-center">
                                <button class="btn btn-primary" id="login-btn"> Log In </button>
                            </div>
                            <div id="error-message" class="text-danger mt-3"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <footer class="footer footer-alt">
        <script>document.write(new Date().getFullYear())</script> © PARIKSIT INC.
    </footer>
    <!-- Vendor js -->
    <script src="./vendors/assets/js/vendor.min.js"></script>
    <!-- App js -->
    <script src="./vendors/assets/js/app.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            // Handle login button click
            $('#login-btn').click(function () {
                login();
            });

            // Handle Enter key press
            $('#rfid').keypress(function (event) {
                if (event.which === 13) {
                    event.preventDefault();
                    login();
                }
            });

            // Login function
            function login() {
                var rfid = $('#rfid').val();

                // Send AJAX request to validate the RFID
                $.ajax({
                    url: './api/checkRFID.php',
                    type: 'POST',
                    data: { rfid: rfid },
                    dataType: 'json',
                    success: function (response) {
                        if (response.valid) {
                            // RFID is valid, proceed with login
                            // Send AJAX request to setSession.php
                            $.ajax({
                                url: './api/setSession.php',
                                type: 'POST',
                                data: { rfid: rfid },
                                dataType: 'json',
                                success: function (response) {
                                    if (response.success) {
                                        // Redirect to attendance.php or display success message
                                        window.location.href = './attendance.php';
                                    } else {
                                        $('#error-message').text('Invalid login credentials');
                                    }
                                },
                                error: function () {
                                    $('#error-message').text('An error occurred. Please try again.');
                                }
                            });
                        } else {
                            $('#error-message').text('Invalid RFID');
                        }
                    },
                    error: function () {
                        $('#error-message').text('An error occurred. Please try again.');
                    }
                });
            }
        });
    </script>
</body>

</html>
