<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>PARIKSIT RFID READER</title>
  <script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
  <!-- Font Awesome -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet" />
  <!-- MDB -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.3.0/mdb.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">

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
    <h1 style = "text-align:center;margin-top:2.5%">Register RFID</h1>
    <div class="container">
      <div class="row my-5 mx-5">
        <div class="col col-lg-6">
          <div class="form-outline">
            <input type="text" id="rnoInput" autofocus class="form-control" />
            <label class="form-label" for="rnoInput">ROLL NUMBER</label>
          </div>
        </div>
        <div class="col col-lg-6">
          <button class="btn btn-primary btn-rounded" id="fetchDetails">FETCH DETAILS</button>
        </div>
      </div>
      <div class="row my-5 mx-5" id="details">
        <div class="col col-lg-3">
          <div class="form-outline">
            <input type="text" readonly id="name" class="form-control" />
          </div>
        </div>
        <div class="col col-lg-3">
          <div class="form-outline">
            <input type="text" readonly id="batch" class="form-control" />
          </div>
        </div>
        <div class="col col-lg-3">
          <div class="form-outline">
            <input type="text" readonly id="phoneNo" class="form-control" />
          </div>
        </div>
      </div>
      <div class="row my-5 mx-5">
        <div class="col col-lg-6">
          <div class="form-outline">
            <input type="text" id="rfid_id" class="form-control" />
          </div>
        </div>
        <div class="col col-lg-6">
          <button class="btn btn-primary btn-rounded" id="rfid_btn">SUBMIT</button>
        </div>
      </div>
    </div>
    <?php include "includes/footer.php" ?>
  </div>
  <!-- MDB -->
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.3.0/mdb.min.js"></script>
  <!-- bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js" integrity="sha384-fbbOQedDUMZZ5KreZpsbe1LCZPVmfTnH7ois6mU1QK+m14rQ1l2bGBq41eYeM/fS" crossorigin="anonymous"></script>
  <script>
    $(document).ready(() => {
      $("#rfid_id").attr("readonly", true);
      $("#fetchDetails").on('click', () => {
        var rno = $("#rnoInput").val();
        if (rno == "") {
          Swal.fire({
            position: 'center',
            icon: 'error',
            title: "Please enter a valid Roll Number",
            showConfirmButton: false,
            timer: 1000
          })
        } else {
          $.ajax({
            url: "./api/getStudentDetails.php",
            method: "POST",
            data: {
              "rno": rno
            },
            success: function(response) {
              var student = JSON.parse(response);
              if (student.status == "OK") {
                $("#name").val(student.username);
                $("#batch").val(student.studentgroup);
                $("#phoneNo").val(student.phnumber);
                if (student.rfid_id != "0") {
                  $("#rfid_id").attr("readonly", true);
                  $("#rfid_id").val(student.rfid_id);
                } else {
                  $("#rfid_id").attr("readonly", false);
                  $("#rfid_id").val("");
                }
              } else {
                Swal.fire(
                  "INVALID ROLL NUMBER",
                  "",
                  "warning"
                );
                $("#rnoInput").val("");
                $("#name").val("");
                $("#group").val("");
                $("#phoneNo").val("");
                $("#rfid_id").val("");
              }
            }
          });
        }
      });
    });
  </script>
  <script>
    $(document).ready(() => {
      $("#rfid_btn").on('click', () => {
        var rfid_id = $("#rfid_id").val();
        var rno = $("#rnoInput").val();
        if (rfid_id == "") {
          Swal.fire({
            position: 'center',
            icon: 'error',
            title: "Please enter a valid RFID ID",
            showConfirmButton: false,
            timer: 1500
          })
        } else {
          $.ajax({
            url: "./api/insertRFID.php",
            method: "POST",
            data: {
              "rfid_id": rfid_id,
              "rno": rno
            },
            success: function(response) {
              var result = JSON.parse(response);
              if (result.status == "OK") {
                Swal.fire({
                  position: 'center',
                  icon: 'success',
                  title: 'RFID INSERTED SUCCESSFULLY',
                  showConfirmButton: false,
                  timer: 1500
                })
                $("#rfid_id").attr("readonly", true);
                $("#rnoInput").val("");
                $("#name").val("");
                $("#group").val("");
                $("#phoneNo").val("");
                $("#rfid_id").val("");
              } else {
                Swal.fire(
                  'Error inserting RFID ID',
                  '',
                  'error'
                );
              }
            }
          });
        }
      });
    });
  </script>
  <script src="sweetalert2.all.min.js"></script>
</body>

</html>
