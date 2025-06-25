<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
include('config/code-generator.php');
check_login();
//Add Customer
if (isset($_POST['addCustomer'])) {
  //Prevent Posting Blank Values
  if (empty($_POST["customer_phoneno"]) || empty($_POST["customer_name"]) || empty($_POST['customer_email']) || empty($_POST['customer_password'])) {
    $err = "Blank Values Not Accepted";
  } else {
    $customer_name = $_POST['customer_name'];
    $customer_phoneno = $_POST['customer_phoneno'];
    $customer_email = $_POST['customer_email'];
    $customer_password = sha1(md5($_POST['customer_password'])); //Hash This 
    $customer_id = $_POST['customer_id'];

    // Validate phone number: must be 11 digits, start with 09, and only numbers
    if (!preg_match('/^09\d{9}$/', $customer_phoneno)) {
      $err = "Phone number must be 11 digits, start with 09, and contain only numbers.";
    } else {
      //Insert Captured information to a database table
      $postQuery = "INSERT INTO rpos_customers (customer_id, customer_name, customer_phoneno, customer_email, customer_password) VALUES(?,?,?,?,?)";
      $postStmt = $mysqli->prepare($postQuery);
      //bind paramaters
      $rc = $postStmt->bind_param('sssss', $customer_id, $customer_name, $customer_phoneno, $customer_email, $customer_password);
      $postStmt->execute();
      //declare a varible which will be passed to alert function
      if ($postStmt) {
        $success = "Customer Added" && header("refresh:1; url=customes.php");
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
                    <label>Customer Name</label>
                    <input type="text" name="customer_name" class="form-control" value="<?php echo isset($_POST['customer_name']) ? htmlspecialchars($_POST['customer_name']) : ''; ?>">
                    <input type="hidden" name="customer_id" value="<?php echo $cus_id; ?>" class="form-control">
                  </div>
                  <div class="col-md-6">
                    <label>Customer Phone Number</label>
                    <input 
                      type="text" 
                      name="customer_phoneno" 
                      maxlength="11" 
                      pattern="^09\d{9}$" 
                      title="Enter a valid 11-digit number starting with 09" 
                      class="form-control" 
                      value="<?php echo isset($_POST['customer_phoneno']) ? htmlspecialchars($_POST['customer_phoneno']) : ''; ?>" 
                      required
                      inputmode="numeric"
                      oninput="this.value=this.value.replace(/[^0-9]/g,'').slice(0,11)">
                  </div>
                </div>
                <hr>
                <div class="form-row">
                  <div class="col-md-6">
                    <label>Customer Email</label>
                    <input type="email" name="customer_email" class="form-control" value="<?php echo isset($_POST['customer_email']) ? htmlspecialchars($_POST['customer_email']) : ''; ?>">
                  </div>
                  <div class="col-md-6">
                    <label>Customer Password</label>
                    <input type="password" name="customer_password" class="form-control" value="">
                  </div>
                </div>
                <br>
                <div class="form-row">
                  <div class="col-md-6">
                    <input type="submit" name="addCustomer" value="Add Customer" class="btn btn-success" value="">
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
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