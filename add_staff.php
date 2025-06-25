<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
include('config/code-generator.php');

check_login();
//Add Staff
if (isset($_POST['addStaff'])) {
  //Prevent Posting Blank Values
  if (empty($_POST["staff_number"]) || empty($_POST["staff_name"]) || empty($_POST['staff_email']) || empty($_POST['staff_password'])) {
    $err = "Blank Values Not Accepted";
  } else {
    $staff_number = $_POST['staff_number'];
    $staff_name = $_POST['staff_name'];
    $staff_email = $_POST['staff_email'];
    $staff_password = sha1(md5($_POST['staff_password']));

    // Validate staff number: must be 11 digits, start with 09, and only numbers
    if (!preg_match('/^09\d{9}$/', $staff_number)) {
      $err = "Staff number must be 11 digits, start with 09, and contain only numbers.";
    } else {
      //Insert Captured information to a database table
      $postQuery = "INSERT INTO rpos_staff (staff_number, staff_name, staff_email, staff_password) VALUES(?,?,?,?)";
      $postStmt = $mysqli->prepare($postQuery);
      //bind paramaters
      $rc = $postStmt->bind_param('ssss', $staff_number, $staff_name, $staff_email, $staff_password);
      $postStmt->execute();
      //declare a varible which will be passed to alert function
      if ($postStmt) {
        $success = "Staff Added" && header("refresh:1; url=hrm.php");
      } else {
        $err = "Please Try Again Or Try Later";
      }
    }
  }
}
require_once('partials/_head.php');
?>

<body>
  <!-- Sidenav -->
  <?php
  require_once('partials/_sidebar.php');
  ?>
  <!-- Main content -->
  <div class="main-content">
    <!-- Top navbar -->
    <?php
    require_once('partials/_topnav.php');
    ?>
    <!-- Header -->
    <div style="background-image: url(assets/img/theme/restro00.jpg); background-size: cover;" class="header  pb-8 pt-5 pt-md-8">
    <span class="mask bg-gradient-dark opacity-8"></span>
      <div class="container-fluid">
        <div class="header-body">
        </div>
      </div>
    </div>
    <!-- Page content -->
    <div class="container-fluid mt--8">
      <!-- Table -->
      <div class="row">
        <div class="col">
          <div class="card shadow">
            <div class="card-header border-0">
              <h3>Please Fill All Fields</h3>
              <?php if (isset($err)) { ?>
                <div class="alert alert-danger"><?php echo $err; ?></div>
              <?php } ?>
              <?php if (isset($success)) { ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
              <?php } ?>
            </div>
            <div class="card-body">
              <form method="POST">
                <div class="form-row">
                  <div class="col-md-6">
                    <label>Staff Number</label>
                    <input 
                      type="text" 
                      name="staff_number" 
                      maxlength="11" 
                      pattern="^09\d{9}$" 
                      title="Enter a valid 11-digit number starting with 09" 
                      class="form-control" 
                      value="<?php echo isset($_POST['staff_number']) ? htmlspecialchars($_POST['staff_number']) : ''; ?>" 
                      required
                      inputmode="numeric"
                      oninput="this.value=this.value.replace(/[^0-9]/g,'').slice(0,11)">
                  </div>
                  <div class="col-md-6">
                    <label>Staff Name</label>
                    <input type="text" name="staff_name" class="form-control" value="<?php echo isset($_POST['staff_name']) ? htmlspecialchars($_POST['staff_name']) : ''; ?>">
                  </div>
                </div>
                <hr>
                <div class="form-row">
                  <div class="col-md-6">
                    <label>Staff Email</label>
                    <input type="email" name="staff_email" class="form-control" value="<?php echo isset($_POST['staff_email']) ? htmlspecialchars($_POST['staff_email']) : ''; ?>">
                  </div>
                  <div class="col-md-6">
                    <label>Staff Password</label>
                    <input type="password" name="staff_password" class="form-control" value="">
                  </div>
                </div>
                <br>
                <div class="form-row">
                  <div class="col-md-6">
                    <input type="submit" name="addStaff" value="Add Staff" class="btn btn-success" value="">
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
      <!-- Footer -->
      <?php
      require_once('partials/_footer.php');
      ?>
    </div>
  </div>
  <!-- Argon Scripts -->
  <?php
  require_once('partials/_scripts.php');
  ?>
</body>

</html>