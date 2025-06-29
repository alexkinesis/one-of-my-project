<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
check_login();
//Delete Customer
if (isset($_GET['delete'])) {
  $id = $_GET['delete'];
  $adn = "DELETE FROM  rpos_customers  WHERE  customer_id = ?";
  $stmt = $mysqli->prepare($adn);
  $stmt->bind_param('s', $id);
  $stmt->execute();
  $stmt->close();
  if ($stmt) {
    $success = "Deleted" && header("refresh:1; url=customes.php");
  } else {
    $err = "Try Again Later";
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
              <div class="d-flex justify-content-between align-items-center">
                <a href="add_customer.php" class="btn btn-outline-success">
                  <i class="fas fa-user-plus"></i>
                  Add New Customer
                </a>
                <form class="form-inline" method="get" action="">
                  <input class="form-control mr-sm-2" type="search" name="search" placeholder="Search Name/Email" aria-label="Search" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                  <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
                </form>
              </div>
            </div>
            <div class="table-responsive">
              <table class="table align-items-center table-flush">
                <thead class="thead-light">
                  <tr>
                    <th scope="col">Full Name</th>
                    <th scope="col">Contact Number</th>
                    <th scope="col">Email</th>
                    <th scope="col">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $search = isset($_GET['search']) ? trim($_GET['search']) : '';
                  if ($search != '') {
                    $search_param = "%{$search}%";
                    $ret = "SELECT * FROM rpos_customers WHERE customer_name LIKE ? OR customer_email LIKE ? OR customer_phoneno LIKE ? ORDER BY `created_at` DESC";
                    $stmt = $mysqli->prepare($ret);
                    $stmt->bind_param('sss', $search_param, $search_param, $search_param);
                  } else {
                    $ret = "SELECT * FROM  rpos_customers  ORDER BY `created_at` DESC ";
                    $stmt = $mysqli->prepare($ret);
                  }
                  $stmt->execute();
                  $res = $stmt->get_result();
                  while ($cust = $res->fetch_object()) {
                  ?>
                    <tr>
                      <td><?php echo $cust->customer_name; ?></td>
                      <td><?php echo $cust->customer_phoneno; ?></td>
                      <td><?php echo $cust->customer_email; ?></td>
                      <td>
                        <a href="customes.php?delete=<?php echo $cust->customer_id; ?>" onclick="return confirm('Are you sure you want to delete this customer?');">
                          <button class="btn btn-sm btn-danger">
                            <i class="fas fa-trash"></i>
                            Delete
                          </button>
                        </a>
                        <a href="update_customer.php?update=<?php echo $cust->customer_id; ?>">
                          <button class="btn btn-sm btn-primary">
                            <i class="fas fa-user-edit"></i>
                            Update
                          </button>
                        </a>
                      </td>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>
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