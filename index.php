<?php
include 'head.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

// Get the user's role from the session
$role = $_SESSION['role'];

// Get summary data for dashboard
$inventoryCount = mysqli_query($con, "SELECT COUNT(*) AS count FROM InventoryItem WHERE is_deleted = 0");
$inventoryCount = mysqli_fetch_assoc($inventoryCount)['count'];

$supplierCount = mysqli_query($con, "SELECT COUNT(*) AS count FROM Supplier WHERE is_deleted = 0");
$supplierCount = mysqli_fetch_assoc($supplierCount)['count'];

$totalSales = mysqli_query($con, "SELECT SUM(TotalPrice) AS total FROM SalesTransaction");
$totalSales = mysqli_fetch_assoc($totalSales)['total'];

$salesTransactionCount = mysqli_query($con, "SELECT COUNT(*) AS count FROM SalesTransaction");
$salesTransactionCount = mysqli_fetch_assoc($salesTransactionCount)['count'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <title>Dashboard</title>
    <style>
    .card:hover {
      transform: scale(1.05);
      transition: transform 0.3s ease;
    }
    .card:hover i {
        animation: bounce 1s ease-in-out;
    }

    @keyframes bounce {
        0%, 100% {
            transform: translateY(0);
        }
        50% {
            transform: translateY(-10px);
        }
    }

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
                            <i class="mdi mdi-home"></i>
                        </span>
                        Dashboard
                    </h3>
                </div>

                <!-- Common Dashboard Content -->
                <div class="row">
                    <div class="col-md-4 stretch-card grid-margin">
                        <div class="card bg-gradient-danger card-img-holder text-white">
                            <div class="card-body">
                                <img src="assets/images/dashboard/circle.svg" class="card-img-absolute" alt="circle-image"/>
                                <h4 class="font-weight-normal mb-3">Total Sales <i class="mdi mdi-cash mdi-24px float-end"></i></h4>
                                <h2 class="mb-5">₹<?php echo number_format($totalSales, 2); ?></h2>
                                <h6 class="card-text">Total sales Amount</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 stretch-card grid-margin">
                        <div class="card bg-gradient-info card-img-holder text-white">
                            <div class="card-body">
                                <img src="assets/images/dashboard/circle.svg" class="card-img-absolute" alt="circle-image"/>
                                <h4 class="font-weight-normal mb-3">Sales Transactions <i class="mdi mdi-cart mdi-24px float-end"></i></h4>
                                <h2 class="mb-5"><?php echo $salesTransactionCount; ?></h2>
                                <h6 class="card-text">Total number of sales transactions</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 stretch-card grid-margin">
                        <div class="card bg-gradient-primary card-img-holder text-white">
                            <div class="card-body">
                                <img src="assets/images/dashboard/circle.svg" class="card-img-absolute" alt="circle-image"/>
                                <h4 class="font-weight-normal mb-3">Inventory Items <i class="mdi mdi-cube mdi-24px float-end"></i></h4>
                                <h2 class="mb-5"><?php echo $inventoryCount; ?></h2>
                                <h6 class="card-text">Total number of inventory items</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 stretch-card grid-margin">
                        <div class="card bg-gradient-secondary card-img-holder text-white">
                            <div class="card-body">
                                <img src="assets/images/dashboard/circle.svg" class="card-img-absolute" alt="circle-image"/>
                                <h4 class="font-weight-normal mb-3">Suppliers <i class="mdi mdi-truck mdi-24px float-end"></i></h4>
                                <h2 class="mb-5"><?php echo $supplierCount; ?></h2>
                                <h6 class="card-text">Total number of suppliers</h6>
                            </div>
                        </div>
                    </div>
                    <!-- Other sections common to all users -->
                </div>

                <!-- Section for Admin (Admin can view Admin, Manager, and Employee details) -->
                <?php if ($role === 'Admin'): ?>
                    <div class="row">
                        <!-- Admin-specific data -->
                        <div class="col-md-4 stretch-card grid-margin">
                            <div class="card bg-gradient-info card-img-holder text-white">
                                <div class="card-body">
                                    <img src="assets/images/dashboard/circle.svg" class="card-img-absolute" alt="circle-image"/>
                                    <h4 class="font-weight-normal mb-3">Admin Reports <i class="mdi mdi-bookmark-outline mdi-24px float-end"></i></h4>
                                    <h2 class="mb-5">Admin Data</h2>
                                    <h6 class="card-text">Detailed report for Admins</h6>
                                </div>
                            </div>
                        </div>

                        <!-- Manager-specific data (visible to Admin as well) -->
                        <div class="col-md-4 stretch-card grid-margin">
                            <div class="card bg-gradient-success card-img-holder text-white">
                                <div class="card-body">
                                    <img src="assets/images/dashboard/circle.svg" class="card-img-absolute" alt="circle-image"/>
                                    <h4 class="font-weight-normal mb-3">Manager Reports <i class="mdi mdi-diamond mdi-24px float-end"></i></h4>
                                    <h2 class="mb-5">Manager Data</h2>
                                    <h6 class="card-text">Manager-specific details</h6>
                                </div>
                            </div>
                        </div>

                        <!-- Employee-specific data (visible to Admin as well) -->
                        <div class="col-md-4 stretch-card grid-margin">
                            <div class="card bg-gradient-warning card-img-holder text-white">
                                <div class="card-body">
                                    <img src="assets/images/dashboard/circle.svg" class="card-img-absolute" alt="circle-image"/>
                                    <h4 class="font-weight-normal mb-3">Employee Details <i class="mdi mdi-account mdi-24px float-end"></i></h4>
                                    <h2 class="mb-5">Employee Data</h2>
                                    <h6 class="card-text">Employee-specific information</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Section for Manager (Manager can view Manager and Employee details only) -->
                <?php if ($role === 'Manager'): ?>
                    <div class="row">
                        <!-- Manager-specific data -->
                        <div class="col-md-4 stretch-card grid-margin">
                            <div class="card bg-gradient-success card-img-holder text-white">
                                <div class="card-body">
                                    <img src="assets/images/dashboard/circle.svg" class="card-img-absolute" alt="circle-image"/>
                                    <h4 class="font-weight-normal mb-3">Manager Reports <i class="mdi mdi-diamond mdi-24px float-end"></i></h4>
                                    <h2 class="mb-5">Manager Data</h2>
                                    <h6 class="card-text">Manager-specific details</h6>
                                </div>
                            </div>
                        </div>

                        <!-- Employee-specific data (visible to Manager as well) -->
                        <div class="col-md-4 stretch-card grid-margin">
                            <div class="card bg-gradient-warning card-img-holder text-white">
                                <div class="card-body">
                                    <img src="assets/images/dashboard/circle.svg" class="card-img-absolute" alt="circle-image"/>
                                    <h4 class="font-weight-normal mb-3">Employee Details <i class="mdi mdi-account mdi-24px float-end"></i></h4>
                                    <h2 class="mb-5">Employee Data</h2>
                                    <h6 class="card-text">Employee-specific information</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Section for Employee (Employee can view only his own details) -->
                <?php if ($role === 'Employee'): ?>
                    <div class="row">
                        <!-- Employee-specific data -->
                        <div class="col-md-4 stretch-card grid-margin">
                            <div class="card bg-gradient-warning card-img-holder text-white">
                                <div class="card-body">
                                    <img src="assets/images/dashboard/circle.svg" class="card-img-absolute" alt="circle-image"/>
                                    <h4 class="font-weight-normal mb-3">Your Details <i class="mdi mdi-account mdi-24px float-end"></i></h4>
                                    <h2 class="mb-5">Your Data</h2>
                                    <h6 class="card-text">Details specific to your account</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Common content continues below -->
                <div class="row">
                    <!-- Low Stock Items Section -->
                    <div class="col-md-7 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Low Stock Inventory Items</h4>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Item Name</th>
                                                <th>Quantity</th>
                                                <th>Location</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            // Fetch low stock items
                                            $lowStockQuery = "SELECT * FROM InventoryItem WHERE Quantity < 10 AND is_deleted = 0 ORDER BY Quantity ASC LIMIT 5";
                                            $lowStockResult = mysqli_query($con, $lowStockQuery);
                                            
                                            if (mysqli_num_rows($lowStockResult) > 0) {
                                                $counter = 1;
                                                while ($row = mysqli_fetch_assoc($lowStockResult)) {
                                                    echo "<tr>";
                                                    echo "<td>" . $counter++ . "</td>";
                                                    echo "<td>" . htmlspecialchars($row['ItemName']) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row['Quantity']) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row['Location']) . "</td>";
                                                    echo "</tr>";
                                                }
                                            } else {
                                                echo "<tr><td colspan='4' class='text-center'>All items are sufficiently stocked.</td></tr>";
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Sales Transactions Section -->
                    <div class="col-md-5 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Recent Sales Transactions</h4>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Item</th>
                                                <th>Quantity Sold</th>
                                                <th>Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            // Fetch recent sales transactions
                                            $recentSalesQuery = "SELECT s.TransactionID, i.ItemName, s.QuantitySold, s.SaleDate
                                                                 FROM SalesTransaction s
                                                                 JOIN InventoryItem i ON s.ItemID = i.ItemID
                                                                 ORDER BY s.SaleDate DESC
                                                                 LIMIT 5";
                                            $recentSalesResult = mysqli_query($con, $recentSalesQuery);

                                            if (mysqli_num_rows($recentSalesResult) > 0) {
                                                $counter = 1;
                                                while ($row = mysqli_fetch_assoc($recentSalesResult)) {
                                                    echo "<tr>";
                                                    echo "<td>" . $counter++ . "</td>";
                                                    echo "<td>" . htmlspecialchars($row['ItemName']) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row['QuantitySold']) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row['SaleDate']) . "</td>";
                                                    echo "</tr>";
                                                }
                                            } else {
                                                echo "<tr><td colspan='4' class='text-center'>No recent sales transactions.</td></tr>";
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Supplier Updates Section -->
                <div class="row">
                    <div class="col-md-7 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Recent Supplier Updates</h4>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Supplier Name</th>
                                                <th>Contact Person</th>
                                                <th>Last Updated</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            // Fetch recent supplier updates
                                            $supplierUpdateQuery = "SELECT * FROM Supplier WHERE is_deleted = 0 ORDER BY UpdatedAt DESC LIMIT 5";
                                            $supplierUpdateResult = mysqli_query($con, $supplierUpdateQuery);

                                            if (mysqli_num_rows($supplierUpdateResult) > 0) {
                                                $counter = 1;
                                                while ($row = mysqli_fetch_assoc($supplierUpdateResult)) {
                                                    echo "<tr>";
                                                    echo "<td>" . $counter++ . "</td>";
                                                    echo "<td>" . htmlspecialchars($row['SupplierName']) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row['ContactPerson']) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row['UpdatedAt']) . "</td>";
                                                    echo "</tr>";
                                                }
                                            } else {
                                                echo "<tr><td colspan='4' class='text-center'>No recent updates on suppliers.</td></tr>";
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- More common content -->
            </div>
        </div>
    </div>
</div>
<?php include 'footer.php'; ?>
</body>
</html>
