<?php
session_start();
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    header("Location: ./attendance.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Login Selection</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="shortcut icon" href="https://media.discordapp.net/attachments/1078997396995985408/1115983582331146352/logo-dark-sm.png">
    <style>
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }
    </style>
</head>

<body>
    <div class="text-center">
        <div class="row justify-content-center mt-6">
            <div class="col-6">
                <a href="loginUserid.php" class="btn btn-primary btn-lg">UserID</a>
            </div>
        </div>
        <div class="row justify-content-center mt-4">
            <div class="col-6">
                <a href="loginRfid.php" class="btn btn-primary btn-lg">RFID</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
