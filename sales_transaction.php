<?php include 'auth.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <title>Sales Transactions</title>
  <?php include 'head.php'; ?>
</head>
<body>
  <div class="container-scroller">
    <?php include 'navBar.php'; ?>
    <div class="container-fluid page-body-wrapper">
      <?php include 'sidebar.php'; ?>
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="page-header">
            <h3 class="page-title">
              <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-cart menu-icon"></i>
              </span>
              Sales Transactions
            </h3>
          </div>

            <div class="container">
            <?php
            // Check user role and only allow Admin and Manager to add sales transactions
            if ($_SESSION['role'] === 'Admin' || $_SESSION['role'] === 'Manager' || $_SESSION['role'] === 'Employee') {
              echo '<div class="mb-3">
                  <a href="add_sales_transaction.php" class="btn btn-gradient-primary btn-lg">
                  <i class="mdi mdi-plus"></i> Add New Transaction
                  </a>
                </div>';
            }
            ?>

            <div class="card">
              <div class="card-body">
              <div class="table-responsive">
                <table class="table table-hover table-striped">
                <thead>
                  <tr class="bg-gradient-primary text-white">
                  <th class="font-weight-bold">Transaction ID</th>
                  <th class="font-weight-bold">Item Name</th>
                  <th class="font-weight-bold">Quantity Sold</th>
                  <th class="font-weight-bold">Total Price</th>
                  <th class="font-weight-bold">Sale Date</th>
                  <th class="font-weight-bold">Customer Name</th>
                  <th class="font-weight-bold">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $search = isset($_GET['search']) ? mysqli_real_escape_string($con, $_GET['search']) : '';

                  if ($_SESSION['role'] === 'Admin' || $_SESSION['role'] === 'Manager' || $_SESSION['role'] === 'Employee') {
                  $query = "SELECT st.*, ii.ItemName FROM SalesTransaction st 
                        LEFT JOIN InventoryItem ii ON st.ItemID = ii.ItemID 
                        WHERE ii.ItemName LIKE '%$search%' OR st.CustomerName LIKE '%$search%' 
                        ORDER BY st.UpdatedAt DESC";
                  }

                  if (isset($query)) {
                  $result = mysqli_query($con, $query);

                  if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr class='align-middle'>";
                    echo "<td><span class='badge badge-primary'>" . $row['TransactionID'] . "</span></td>";
                    echo "<td>" . $row['ItemName'] . "</td>";
                    echo "<td><span class='badge badge-success'>" . $row['QuantitySold'] . "</span></td>";
                    echo "<td><span class='text-success font-weight-bold'>₹" . number_format($row['TotalPrice'], 2) . "</span></td>";
                    echo "<td>" . date('d M Y', strtotime($row['SaleDate'])) . "</td>";
                    echo "<td>" . $row['CustomerName'] . "</td>";

                    echo "<td>";
                    if ($_SESSION['role'] === 'Admin' || $_SESSION['role'] === 'Manager') {
                      echo "<a href='edit_sales_transaction.php?id=" . $row['TransactionID'] . "' class='btn btn-gradient-warning btn-sm me-2'>
                          <i class='mdi mdi-pencil'></i> Edit
                        </a>
                        <a href='delete_sales_transaction.php?id=" . $row['TransactionID'] . "' class='btn btn-gradient-danger btn-sm'>
                          <i class='mdi mdi-delete'></i> Delete
                        </a>";
                    } elseif ($_SESSION['role'] === 'Employee') {
                      echo "<span class='badge badge-info'>View Only</span>";
                    }
                    echo "</td>";
                    echo "</tr>";
                    }
                  } else {
                    echo "<tr><td colspan='7' class='text-center text-muted'>No transactions found.</td></tr>";
                  }
                  } else {
                  echo "<tr><td colspan='7' class='text-center text-danger'>Query not defined for this user role.</td></tr>";
                  }
                  ?>
                </tbody>
                </table>
              </div>
              </div>
            </div>
            </div>
        </div>
      </div>
    </div>
  </div>
  <?php include 'footer.php'; ?>
</body>
</html>