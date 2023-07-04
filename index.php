<?php

session_start();
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
  header("Location: ./attendance.php");
  exit();
}

if(isset($_POST['login'])){
    $correctUserID = "admin";
    $correctPassword = "123";
    $userid = $_POST['userid'];
    $password = $_POST['password'];
    $stringPass = strval($password);
    if($correctPassword==$stringPass && $correctUserID == $userid){
        $_SESSION['logged_in'] = true;
        header("Location:./attendance.php");
    }else{
        ?>
        <script>alert("Invalid Credentials")</script>
        <?php
    }   
}
?>

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
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xxl-4 col-lg-5">
                    <div class="card">

                        <!-- Logo -->
                        <div class="card-header py-4 text-center bg-primary">
                            <a href="#">
                                <span><img src="https://media.discordapp.net/attachments/1078997396995985408/1115983582838669433/logo.png?width=1170&height=303" alt="logo" height="50"></span>
                            </a>
                        </div>

                        <div class="card-body p-4">

                            <div class="text-center w-75 m-auto">
                                <h4 class="text-dark-50 text-center pb-0 fw-bold">LOG IN</h4>
                                <!-- <p class="text-muted mb-4">Enter your User ID and Password to access the admin panel.</p> -->
                            </div>

                            <form method="POST" action="">

                                <?php if (isset($errorMessage)) { ?>
                                    <div class="alert alert-danger" role="alert">
                                        <?php echo $errorMessage; ?>
                                    </div>
                                <?php } ?>
                                <div class="mb-3">
                                    <label for="userid" class="form-label">User ID</label>
                                    <input class="form-control" type="text" id="userid" name="userid" required="" placeholder="Enter your user ID">
                                </div>

                                <div class="mb-3">
                                    <!-- <a href="pages-recoverpw.html" class="text-muted float-end"><small>Forgot your password?</small></a> -->
                                    <label for="password" class="form-label">Password</label>
                                    <div class="input-group input-group-merge">
                                        <input type="password" id="password" name="password" class="form-control" placeholder="Enter your password">
                                        <!-- <div class="input-group-text" data-password="false">
                                            <span class="password-eye"></span>
                                        </div> -->
                                    </div>
                                </div>

                                <!-- <div class="mb-3 mb-3">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="checkbox-signin" checked>
                                        <label class="form-check-label" for="checkbox-signin">Remember me</label>
                                    </div>
                                </div> -->

                                <div class="mb-3 mb-0 text-center">
                                    <button class="btn btn-primary" type="submit" name = "login"> Log In </button>
                                </div>

                            </form>
                        </div> <!-- end card-body -->
                    </div>
                    <!-- end card -->

                    <!-- <div class="row mt-3">
                        <div class="col-12 text-center">
                            <p class="text-muted">Don't have an account? <a href="pages-register.html" class="text-muted ms-1"><b>Sign Up</b></a></p>
                        </div> 
                    </div> -->
                    <!-- end row -->

                </div> <!-- end col -->
            </div>
            <!-- end row -->
        </div>
        <!-- end container -->
    </div>
    <!-- end page -->

    <footer class="footer footer-alt">
        <script>document.write(new Date().getFullYear())</script> © PARIKSIT INC.
    </footer>
    <!-- Vendor js -->
    <script src="./vendors/assets/js/vendor.min.js"></script>

    <!-- App js -->
    <script src="./vendors/assets/js/app.min.js"></script>

</body>

</html>
