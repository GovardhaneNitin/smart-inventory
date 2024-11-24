<?php include 'auth.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <title>Inventory Management</title>
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
                <i class="mdi mdi-view-list menu-icon"></i>
              </span>
              Inventory Management
            </h3>
          </div>

            <div class="container">
            <?php
            // Check user role and only allow Admin and Manager to add items
            if ($_SESSION['role'] === 'Admin' || $_SESSION['role'] === 'Manager') {
              echo '<div class="mb-3">
                  <a href="add_item.php" class="btn btn-gradient-primary btn-lg">
                  <i class="mdi mdi-plus"></i> Add New Item
                  </a>
                </div>';
            }
            ?>

            <div class="card">
              <div class="card-body">
              <div class="table-responsive">
                <table class="table table-hover table-striped">
                <thead class="bg-gradient-primary text-white">
                  <tr>
                  <th class="font-weight-bold">Name</th>
                  <th class="font-weight-bold">Category</th>
                  <th class="font-weight-bold">Quantity</th>
                  <th class="font-weight-bold">Location</th>
                  <th class="font-weight-bold">Price</th>
                  <th class="font-weight-bold">Last Updated</th>
                  <th class="font-weight-bold">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $search = isset($_GET['search']) ? mysqli_real_escape_string($con, $_GET['search']) : '';

                  if ($_SESSION['role'] === 'Admin' || $_SESSION['role'] === 'Manager' || $_SESSION['role'] === 'Employee') {
                  $query = "SELECT * FROM InventoryItem WHERE ItemName LIKE '%$search%' OR Category LIKE '%$search%' ORDER BY UpdatedAt DESC";
                  }

                  if (isset($query)) {
                  $result = mysqli_query($con, $query);

                  if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr class='align-middle'>";
                    echo "<td class='font-weight-medium'>" . $row['ItemName'] . "</td>";
                    echo "<td><span class='badge bg-gradient-info'>" . $row['Category'] . "</span></td>";
                    echo "<td>" . $row['Quantity'] . "</td>";
                    echo "<td>" . $row['Location'] . "</td>";
                    echo "<td class='text-success font-weight-bold'>₹" . number_format($row['Price'], 2) . "</td>";
                    echo "<td><small class='text-muted'>" . $row['UpdatedAt'] . "</small></td>";

                    echo "<td>";
                    if ($_SESSION['role'] === 'Admin' || $_SESSION['role'] === 'Manager') {
                      echo "<a href='edit_item.php?id=" . $row['ItemID'] . "' class='btn btn-gradient-warning btn-sm me-2'><i class='mdi mdi-pencil'></i></a>";
                      echo "<a href='delete_item.php?id=" . $row['ItemID'] . "' class='btn btn-gradient-danger btn-sm'><i class='mdi mdi-delete'></i></a>";
                    } elseif ($_SESSION['role'] === 'Employee') {
                      echo "<span class='badge bg-gradient-secondary'>View Only</span>";
                    }
                    echo "</td>";
                    echo "</tr>";
                    }
                  } else {
                    echo "<tr><td colspan='7' class='text-center text-muted'>No items found.</td></tr>";
                  }
                  } else {
                  echo "<tr><td colspan='7' class='text-center text-muted'>Query not defined for this user role.</td></tr>";
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
