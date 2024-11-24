<?php include 'auth.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <title>Supplier Management</title>
  <?php include 'head.php'; ?>
  <style>
    .card:hover {
      transform: scale(1.05);
      transition: transform 0.3s ease;
    }
    .card:hover .mdi-truck {
      animation: truckAnimation 1s infinite;
    }
    @keyframes truckAnimation {
      0% {
      transform: translateX(0);
      }
      50% {
      transform: translateX(15px);
      }
      100% {
      transform: translateX(0);
      }
    }
  </style>
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
                <i class="mdi mdi-truck menu-icon"></i>
              </span>
              Supplier Management
            </h3>
          </div>

          <div class="container">
            <!-- Only Admin and Manager can add suppliers -->
            <?php
            if ($_SESSION['role'] === 'Admin' || $_SESSION['role'] === 'Manager') {
              echo '<div class="mb-4 text-center">
                      <a href="add_supplier.php" class="btn btn-primary btn-lg">Add New Supplier</a>
                    </div>';
            }
            ?>

            <div class="row">
              <?php
              // To fetch non-deleted supplier data
              $search = isset($_GET['search']) ? mysqli_real_escape_string($con, $_GET['search']) : '';
              $query = "SELECT * FROM Supplier WHERE (SupplierName LIKE '%$search%' OR ContactPerson LIKE '%$search%') AND is_deleted = 0 ORDER BY UpdatedAt DESC";
              $result = mysqli_query($con, $query);
              if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                  $modalID = "supplierModal" . $row['SupplierID'];

                  // Card structure for each supplier
                  echo '<div class="col-md-4 stretch-card grid-margin">';
                  echo '  <div class="card bg-gradient-info card-img-holder text-white">';
                  echo '    <div class="card-body">';
                  echo '      <img src="assets/images/dashboard/circle.svg" class="card-img-absolute" alt="circle-image"/>';
                  echo '      <h4 class="font-weight-normal mb-3">' . $row['SupplierName'] . ' <i class="mdi mdi-truck mdi-24px float-end"></i></h4>';
                  echo '      <button type="button" class="btn btn-outline-light btn-sm" data-bs-toggle="modal" data-bs-target="#' . $modalID . '">View Details</button>';
                  echo '    </div>';
                  echo '  </div>';
                  echo '</div>';

                  // Modal structure for each supplier
                  echo '
                  <div class="modal fade" id="' . $modalID . '" tabindex="-1" aria-labelledby="modalLabel' . $row['SupplierID'] . '" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                      <div class="modal-content">
                        <div class="modal-header bg-info text-white">
                          <h5 class="modal-title text-center w-100" id="modalLabel' . $row['SupplierID'] . '">Supplier Details - ' . $row['SupplierName'] . '</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                          <p><strong>Contact Person :</strong> ' . $row['ContactPerson'] . '</p>
                          <p><strong>Email :</strong> ' . $row['ContactEmail'] . '</p>
                          <p><strong>Phone :</strong> ' . $row['ContactPhone'] . '</p>
                          <p><strong>Address :</strong> ' . $row['Address'] . '</p>
                          <p><small class="text-muted">Last updated on ' . $row['UpdatedAt'] . '</small></p>
                        </div>
                        <div class="modal-footer">
                          ';

                  // Action buttons based on user role
                  if ($_SESSION['role'] === 'Admin' || $_SESSION['role'] === 'Manager') {
                    echo '<a href="edit_supplier.php?id=' . $row['SupplierID'] . '" class="btn btn-warning">Edit</a>';
                    echo '<a href="delete_supplier.php?id=' . $row['SupplierID'] . '" class="btn btn-danger">Delete</a>';
                  }

                  echo '
                        </div>
                      </div>
                    </div>
                  </div>';
                }
              } else {
                echo '<p class="text-center">No suppliers found.</p>';
              }
              ?>
            </div>

            <?php if ($_SESSION['role'] === 'Admin' || $_SESSION['role'] === 'Manager') : ?>
            <!-- Toggle to show deleted suppliers -->
            <div class="mt-4 text-center">
              <button type="button" class="btn btn-secondary" data-bs-toggle="collapse" data-bs-target="#deletedSuppliers" aria-expanded="false" aria-controls="deletedSuppliers">
              Show Deleted Suppliers
              </button>
            </div>

            <div class="collapse mt-4" id="deletedSuppliers">
              <h3>Deleted Suppliers</h3>
              <div class="row">
              <?php
              // To fetch deleted supplier data
              $deletedQuery = "SELECT * FROM Supplier WHERE is_deleted = 1 ORDER BY UpdatedAt DESC";
              $deletedResult = mysqli_query($con, $deletedQuery);
              if (mysqli_num_rows($deletedResult) > 0) {
                while ($row = mysqli_fetch_assoc($deletedResult)) {
                $modalID = "deletedSupplierModal" . $row['SupplierID'];

                // Card structure for each deleted supplier
                echo '<div class="col-md-4 stretch-card grid-margin">';
                echo '  <div class="card bg-gradient-secondary card-img-holder text-white">';
                echo '    <div class="card-body">';
                echo '      <img src="assets/images/dashboard/circle.svg" class="card-img-absolute" alt="circle-image"/>';
                echo '      <h4 class="font-weight-normal mb-3">' . $row['SupplierName'] . ' <i class="mdi mdi-truck mdi-24px float-end"></i></h4>';
                echo '      <button type="button" class="btn btn-outline-light btn-sm" data-bs-toggle="modal" data-bs-target="#' . $modalID . '">View Details</button>';
                echo '    </div>';
                echo '  </div>';
                echo '</div>';

                // Modal structure for each deleted supplier
                echo '
                <div class="modal fade" id="' . $modalID . '" tabindex="-1" aria-labelledby="modalLabel' . $row['SupplierID'] . '" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered">
                  <div class="modal-content">
                    <div class="modal-header bg-secondary text-white">
                    <h5 class="modal-title text-center w-100" id="modalLabel' . $row['SupplierID'] . '">Deleted Supplier Details - ' . $row['SupplierName'] . '</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                    <p><strong>Contact Person :</strong> ' . $row['ContactPerson'] . '</p>
                    <p><strong>Email :</strong> ' . $row['ContactEmail'] . '</p>
                    <p><strong>Phone :</strong> ' . $row['ContactPhone'] . '</p>
                    <p><strong>Address :</strong> ' . $row['Address'] . '</p>
                    <p><small class="text-muted">Last updated on ' . $row['UpdatedAt'] . '</small></p>
                    </div>
                    <div class="modal-footer">
                    ';
                    
                if ($_SESSION['role'] === 'Admin') {
                  // Restore option
                  echo '<a href="restore_supplier.php?id=' . $row['SupplierID'] . '" class="btn btn-warning">Restore</a>';
                  
                  // Permanent Delete option
                  echo '<a href="permanent_delete_supplier.php?id=' . $row['SupplierID'] . '" class="btn btn-danger">Permanent Delete</a>';
                }

                echo '
                    </div>
                  </div>
                  </div>
                </div>';

                }
              } else {
                echo '<p class="text-center">No deleted suppliers found.</p>';
              }
              ?>
              </div>
            </div>
            <?php endif; ?>
          </div>

            <div class="container mt-4">
            <div class="card">
              <div class="card-header bg-gradient-primary text-white">
              <h3 class="mb-0">
              <i class="mdi mdi-history me-2"></i>
              Supplier Activity Log
              </h3>
              </div>
              <div class="card-body">
              <div class="table-responsive">
              <table class="table table-striped">
              <thead class="bg-light">
                <tr>
                <th>
                <i class="mdi mdi-truck me-1"></i>
                Supplier Name
                </th>
                <th>
                <i class="mdi mdi-clipboard-text me-1"></i>
                Action
                </th>
                <th>
                <i class="mdi mdi-account-circle me-1"></i>
                Role
                </th>
                <th>
                <i class="mdi mdi-clock-outline me-1"></i>
                Timestamp
                </th>
                </tr>
              </thead>
              <tbody>
                <?php
                $logQuery = "SELECT s.SupplierName, l.action, l.role, l.timestamp 
                   FROM supplier_activity_log l
                   JOIN Supplier s ON l.supplier_id = s.SupplierID
                   ORDER BY l.timestamp DESC";
                $logResult = mysqli_query($con, $logQuery);
                if (mysqli_num_rows($logResult) > 0) {
                while ($log = mysqli_fetch_assoc($logResult)) {
                // Define action color
                $actionColor = 'text-info';
                if (strpos(strtolower($log['action']), 'delete') !== false) {
                $actionColor = 'text-danger';
                } elseif (strpos(strtolower($log['action']), 'add') !== false) {
                $actionColor = 'text-success';
                } elseif (strpos(strtolower($log['action']), 'update') !== false) {
                $actionColor = 'text-warning';
                }
                
                echo '<tr class="align-middle">';
                echo '<td>' . $log['SupplierName'] . '</td>';
                echo '<td class="' . $actionColor . '">' . $log['action'] . '</td>';
                echo '<td><span class="badge bg-gradient-info">' . $log['role'] . '</span></td>';
                echo '<td><small class="text-muted">' . date('F j, Y g:i A', strtotime($log['timestamp'])) . '</small></td>';
                echo '</tr>';
                }
                } else {
                echo '<tr><td colspan="4" class="text-center text-muted">
                  <i class="mdi mdi-alert-circle-outline me-1"></i>
                  No activity logs available.
                  </td></tr>';
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
