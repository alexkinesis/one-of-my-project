<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
check_login();

// Update Profile
if (isset($_POST['ChangeProfile'])) {
  $admin_id = $_SESSION['admin_id'];
  $admin_name = $_POST['admin_name'];
  $admin_email = $_POST['admin_email'];
  $Qry = "UPDATE rpos_admin SET admin_name =?, admin_email =? WHERE admin_id =?";
  $postStmt = $mysqli->prepare($Qry);
  $rc = $postStmt->bind_param('sss', $admin_name, $admin_email, $admin_id);
  $postStmt->execute();
  if ($postStmt) {
    $success = "Account Updated" && header("refresh:1; url=dashboard.php");
  } else {
    $err = "Please Try Again Or Try Later";
  }
}

// Change Password
if (isset($_POST['changePassword'])) {
  $error = 0;
  if (empty($_POST['old_password'])) {
    $error = 1;
    $err = "Old Password Cannot Be Empty";
  } else {
    $old_password = sha1(md5($_POST['old_password']));
  }
  if (empty($_POST['new_password'])) {
    $error = 1;
    $err = "New Password Cannot Be Empty";
  } else {
    $new_password = sha1(md5($_POST['new_password']));
  }
  if (empty($_POST['confirm_password'])) {
    $error = 1;
    $err = "Confirmation Password Cannot Be Empty";
  } else {
    $confirm_password = sha1(md5($_POST['confirm_password']));
  }

  if (!$error) {
    $admin_id = $_SESSION['admin_id'];
    $sql = "SELECT * FROM rpos_admin WHERE admin_id = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param('s', $admin_id);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res->num_rows > 0) {
      $row = $res->fetch_assoc();
      if ($old_password != $row['admin_password']) {
        $err =  "Please Enter Correct Old Password";
      } elseif ($new_password != $confirm_password) {
        $err = "Confirmation Password Does Not Match";
      } else {
        $query = "UPDATE rpos_admin SET admin_password =? WHERE admin_id =?";
        $stmt2 = $mysqli->prepare($query);
        $stmt2->bind_param('ss', $new_password, $admin_id);
        $stmt2->execute();
        if ($stmt2) {
          $success = "Password Changed" && header("refresh:1; url=dashboard.php");
        } else {
          $err = "Please Try Again Or Try Later";
        }
        $stmt2->close();
      }
    }
    $stmt->close();
  }
}
require_once('partials/_head.php');
?>

<body>
  <!-- Sidenav -->
  <?php require_once('partials/_sidebar.php'); ?>
  <!-- Main content -->
  <div class="main-content">
    <!-- Top navbar -->
    <?php
    require_once('partials/_topnav.php');
    $admin_id = $_SESSION['admin_id'];
    $ret = "SELECT * FROM  rpos_admin  WHERE admin_id = ?";
    $stmt = $mysqli->prepare($ret);
    $stmt->bind_param('s', $admin_id);
    $stmt->execute();
    $res = $stmt->get_result();
    while ($admin = $res->fetch_object()) {
    ?>
      <!-- Header -->
      <div class="header pb-8 pt-5 pt-lg-8 d-flex align-items-center" style="min-height: 600px; background-image: url(assets/img/theme/restro00.jpg); background-size: cover; background-position: center top;">
        <span class="mask bg-gradient-default opacity-8"></span>
        <div class="container-fluid d-flex align-items-center">
          <div class="row">
            <div class="col-lg-7 col-md-10">
              <h1 class="display-2 text-white">Hello <?php echo $admin->admin_name; ?></h1>
              <p class="text-white mt-0 mb-5">This is your profile page. You can customize your profile as you want and also change password too.</p>
            </div>
          </div>
        </div>
      </div>
      <!-- Page content -->
      <div class="container-fluid mt--8">
        <div class="row">
          <div class="col-xl-4 order-xl-2 mb-5 mb-xl-0">
            <div class="card card-profile shadow">
              <div class="row justify-content-center">
                <div class="col-lg-3 order-lg-2">
                  <div class="card-profile-image">
                    <a href="#">
                      <img src="assets/img/theme/user-a-min.png" class="rounded-circle">
                    </a>
                  </div>
                </div>
              </div>
              <div class="card-header text-center border-0 pt-8 pt-md-4 pb-0 pb-md-4">
                <div class="d-flex justify-content-between"></div>
              </div>
              <div class="card-body pt-0 pt-md-4">
                <div class="row">
                  <div class="col">
                    <div class="card-profile-stats d-flex justify-content-center mt-md-5"></div>
                  </div>
                </div>
                <div class="text-center">
                  <h3><?php echo $admin->admin_name; ?></h3>
                  <div class="h5 font-weight-300">
                    <i class="ni location_pin mr-2"></i><?php echo $admin->admin_email; ?>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-xl-8 order-xl-1">
            <div class="card bg-secondary shadow">
              <div class="card-header bg-white border-0">
                <div class="row align-items-center">
                  <div class="col-8">
                    <h3 class="mb-0">My account</h3>
                  </div>
                  <div class="col-4 text-right"></div>
                </div>
              </div>
              <div class="card-body">
                <form method="post">
                  <h6 class="heading-small text-muted mb-4">User information</h6>
                  <div class="pl-lg-4">
                    <div class="row">
                      <div class="col-lg-6">
                        <div class="form-group">
                          <label class="form-control-label" for="input-username">User Name</label>
                          <input type="text" name="admin_name" value="<?php echo $admin->admin_name; ?>" id="input-username" class="form-control form-control-alternative">
                        </div>
                      </div>
                      <div class="col-lg-6">
                        <div class="form-group">
                          <label class="form-control-label" for="input-email">Email address</label>
                          <input type="email" id="input-email" value="<?php echo $admin->admin_email; ?>" name="admin_email" class="form-control form-control-alternative">
                        </div>
                      </div>
                      <div class="col-lg-12">
                        <div class="form-group">
                          <input type="submit" name="ChangeProfile" class="btn btn-success form-control-alternative" value="Submit">
                        </div>
                      </div>
                    </div>
                  </div>
                </form>
                <hr>
                <form method="post">
                  <h6 class="heading-small text-muted mb-4">Change Password</h6>
                  <div class="pl-lg-4">
                    <div class="row">
                      <div class="col-lg-12">
                        <div class="form-group">
                          <label class="form-control-label" for="input-old-password">Old Password</label>
                          <input type="password" name="old_password" id="input-old-password" class="form-control form-control-alternative">
                        </div>
                      </div>
                      <div class="col-lg-12">
                        <div class="form-group">
                          <label class="form-control-label" for="input-new-password">New Password</label>
                          <input type="password" name="new_password" id="input-new-password" class="form-control form-control-alternative">
                        </div>
                      </div>
                      <div class="col-lg-12">
                        <div class="form-group">
                          <label class="form-control-label" for="input-confirm-password">Confirm New Password</label>
                          <input type="password" name="confirm_password" id="input-confirm-password" class="form-control form-control-alternative">
                        </div>
                      </div>
                      <div class="col-lg-12">
                        <div class="form-group">
                          <input type="submit" name="changePassword" class="btn btn-success form-control-alternative" value="Change Password">
                        </div>
                      </div>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
        <!-- Footer -->
        <?php require_once('partials/_footer.php'); ?>
      <?php } // end while ?>
      </div>
  </div>
  <!-- Argon Scripts -->
  <?php require_once('partials/_scripts.php'); ?>
</body>
</html>