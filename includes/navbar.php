<?php 
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
  header("Location: index.php");
  exit();
	}
?>
<nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container-fluid">
    <img src="https://cdn.discordapp.com/attachments/1078997396995985408/1115983582050140160/logo-dark.png" alt="Logo" class="navbar-brand" style="height:50px;width:150px">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
      <div class="navbar-nav me-auto">
        <a class="nav-link" href="./attendance.php" aria-current="page">Mark Attendance</a>
        <a class="nav-link" href="./viewAttendance.php" aria-current="page">View Attendance</a>
        <a class="nav-link" href="./registration.php" aria-current="page">Registrations</a>
      </div>
      <div class="navbar-nav">
        <a class="nav-link" href="./api/logout.php" aria-current="page">Logout</a>
      </div>
    </div>
  </div>
</nav>
