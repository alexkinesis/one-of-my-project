<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
check_login();

$search = '';
if (isset($_GET['search'])) {
  $search = trim($_GET['search']);
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
            <div class="card-header border-0 d-flex justify-content-between align-items-center">
              <span>Select On Any Product To Make An Order</span>
              <form class="form-inline" method="get" action="orders.php">
                <input class="form-control mr-sm-2" type="search" name="search" placeholder="Search by name or code" value="<?php echo htmlspecialchars($search); ?>">
                <button class="btn btn-outline-primary my-2 my-sm-0" type="submit">Search</button>
              </form>
            </div>
            <div class="table-responsive">
              <table class="table align-items-center table-flush">
                <thead class="thead-light">
                  <tr>
                    <th scope="col"><b>Image</b></th>
                    <th scope="col"><b>Product Code</b></th>
                    <th scope="col"><b>Name</b></th>
                    <th scope="col"><b>Price</b></th>
                    <th scope="col"><b>Action</b></th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  if ($search) {
                    $ret = "SELECT * FROM rpos_products WHERE prod_name LIKE ? OR prod_code LIKE ? ORDER BY prod_name ASC";
                    $param = '%' . $search . '%';
                    $stmt = $mysqli->prepare($ret);
                    $stmt->bind_param('ss', $param, $param);
                  } else {
                    $ret = "SELECT * FROM rpos_products ORDER BY prod_name ASC";
                    $stmt = $mysqli->prepare($ret);
                  }
                  $stmt->execute();
                  $res = $stmt->get_result();
                  while ($prod = $res->fetch_object()) {
                  ?>
                    <tr>
                      <td>
                        <?php
                        if ($prod->prod_img) {
                          echo "<img src='assets/img/products/$prod->prod_img' height='60' width='60' class='img-thumbnail'>";
                        } else {
                          echo "<img src='assets/img/products/default.jpg' height='60' width='60' class='img-thumbnail'>";
                        }
                        ?>
                      </td>
                      <td><?php echo htmlspecialchars($prod->prod_code); ?></td>
                      <td><?php echo htmlspecialchars($prod->prod_name); ?></td>
                      <td>₱ <?php echo htmlspecialchars($prod->prod_price); ?></td>
                      <td>
                        <a href="make_oder.php?prod_id=<?php echo $prod->prod_id; ?>&prod_name=<?php echo urlencode($prod->prod_name); ?>&prod_price=<?php echo $prod->prod_price; ?>">
                          <button class="btn btn-sm btn-warning">
                            <i class="fas fa-cart-plus"></i>
                            Place Order
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