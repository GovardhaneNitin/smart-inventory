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
            if ($_SESSION['role'] === 'Admin' || $_SESSION['role'] === 'Manager') {
              echo '<div class="mb-3">
                      <a href="add_sales_transaction.php" class="btn btn-primary btn-block d-md-inline-block d-block">Add New Transaction</a>
                    </div>';
            }
            ?>

            <div class="table-responsive">
              <table class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>Transaction ID</th>
                    <th>Item Name</th>
                    <th>Quantity Sold</th>
                    <th>Total Price</th>
                    <th>Sale Date</th>
                    <th>Customer Name</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  // Check if the search parameter is present
                  $search = isset($_GET['search']) ? mysqli_real_escape_string($con, $_GET['search']) : '';

                  // SQL query to fetch sales transactions based on user role and search term
                  if ($_SESSION['role'] === 'Admin' || $_SESSION['role'] === 'Manager' || $_SESSION['role'] === 'Employee') {
                    $query = "SELECT st.*, ii.ItemName FROM SalesTransaction st 
                              LEFT JOIN InventoryItem ii ON st.ItemID = ii.ItemID 
                              WHERE ii.ItemName LIKE '%$search%' OR st.CustomerName LIKE '%$search%' 
                              ORDER BY st.UpdatedAt DESC";
                  }

                  if (isset($query)) {
                    $result = mysqli_query($con, $query);

                    // Display the results in the table
                    if (mysqli_num_rows($result) > 0) {
                      while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . $row['TransactionID'] . "</td>";
                        echo "<td>" . $row['ItemName'] . "</td>";
                        echo "<td>" . $row['QuantitySold'] . "</td>";
                        echo "<td>₹" . number_format($row['TotalPrice'], 2) . "</td>";
                        echo "<td>" . $row['SaleDate'] . "</td>";
                        echo "<td>" . $row['CustomerName'] . "</td>";

                        // Action buttons based on user role
                        echo "<td>";
                        if ($_SESSION['role'] === 'Admin' || $_SESSION['role'] === 'Manager') {
                          echo "<a href='edit_sales_transaction.php?id=" . $row['TransactionID'] . "' class='btn btn-warning btn-sm'>Edit</a>
                                <a href='delete_sales_transaction.php?id=" . $row['TransactionID'] . "' class='btn btn-danger btn-sm'>Delete</a>";
                        } elseif ($_SESSION['role'] === 'Employee') {
                          echo "View Only";  // Employee can only view
                        }
                        echo "</td>";
                        echo "</tr>";
                      }
                    } else {
                      echo "<tr><td colspan='7'>No transactions found.</td></tr>";
                    }
                  } else {
                    echo "<tr><td colspan='7'>Query not defined for this user role.</td></tr>";
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
  <?php include 'footer.php'; ?>
</body>
</html>