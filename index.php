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
if ($role === 'Admin' || $role === 'Manager') {
    $activeUserCount = mysqli_query($con, "SELECT COUNT(*) AS count FROM User");
    $activeUserCount = mysqli_fetch_assoc($activeUserCount)['count'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <title>Dashboard</title>
    <style>
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
                        <?php echo htmlspecialchars($role); ?> Dashboard
                    </h3>
                </div>

                <!-- Common Dashboard Content -->
                <div class="row">
                    <div class="col-md-4 stretch-card grid-margin">
                        <div class="card shadow bg-gradient-danger card-img-holder text-white" onclick="window.location.href='sales_transaction.php';" style="cursor: pointer;">
                            <div class="card-body">
                                <img src="assets/images/dashboard/circle.svg" class="card-img-absolute" alt="circle-image"/>
                                <h4 class="font-weight-normal mb-3">Total Sales <i class="mdi mdi-cash mdi-24px float-end"></i></h4>
                                <h2 class="mb-5">₹<?php echo number_format($totalSales, 2); ?></h2>
                                <h6 class="card-text">Total sales Amount</h6>
                            </div>
                        </div>
                    </div>
                    <!-- Admin-specific Dashboard Content -->
                    <?php if ($role === 'Admin'): ?>
                        <div class="col-md-4 stretch-card grid-margin">
                            <div class="card shadow bg-gradient-success card-img-holder text-white" onclick="window.location.href='admin_manage_roles.php';" style="cursor: pointer;">
                                <div class="card-body">
                                    <img src="assets/images/dashboard/circle.svg" class="card-img-absolute" alt="circle-image"/>
                                    <h4 class="font-weight-normal mb-3">Active Users <i class="mdi mdi-account-group mdi-24px float-end"></i></h4>
                                    <h2 class="mb-5"><?php echo $activeUserCount; ?></h2>
                                    <h6 class="card-text">Total number of active users in the system</h6>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    <div class="col-md-4 stretch-card grid-margin">
                        <div class="card shadow bg-gradient-info card-img-holder text-white" onclick="window.location.href='sales_transaction.php';" style="cursor: pointer;">
                            <div class="card-body">
                                <img src="assets/images/dashboard/circle.svg" class="card-img-absolute" alt="circle-image"/>
                                <h4 class="font-weight-normal mb-3">Sales Transactions <i class="mdi mdi-cart mdi-24px float-end"></i></h4>
                                <h2 class="mb-5"><?php echo $salesTransactionCount; ?></h2>
                                <h6 class="card-text">Total number of sales transactions</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 stretch-card grid-margin">
                        <div class="card shadow bg-gradient-primary card-img-holder text-white" onclick="window.location.href='inventory.php';" style="cursor: pointer;">
                            <div class="card-body">
                                <img src="assets/images/dashboard/circle.svg" class="card-img-absolute" alt="circle-image"/>
                                <h4 class="font-weight-normal mb-3">Inventory Items <i class="mdi mdi-cube mdi-24px float-end"></i></h4>
                                <h2 class="mb-5"><?php echo $inventoryCount; ?></h2>
                                <h6 class="card-text">Total number of inventory items</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 stretch-card grid-margin">
                        <div class="card shadow bg-gradient-secondary card-img-holder text-white" onclick="window.location.href='supplier.php';" style="cursor: pointer;">
                            <div class="card-body">
                                <img src="assets/images/dashboard/circle.svg" class="card-img-absolute" alt="circle-image"/>
                                <h4 class="font-weight-normal mb-3">Suppliers <i class="mdi mdi-truck mdi-24px float-end"></i></h4>
                                <h2 class="mb-5"><?php echo $supplierCount; ?></h2>
                                <h6 class="card-text">Total number of suppliers</h6>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions Section -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card shadow border-0" style="border-radius: 15px;">
                            <div class="card-body">
                                <h4 class="card-title text-primary mb-4">
                                    <i class="mdi mdi-lightning-bolt text-warning me-2"></i>
                                    Quick Actions
                                </h4>
                                <div class="row">
                                    <div class="col-md-3 mb-3">
                                        <a href="add_sales_transaction.php" class="text-decoration-none">
                                            <div class="card bg-gradient-success text-white shadow-sm hover-card">
                                                <div class="card-body text-center p-3">
                                                    <i class="mdi mdi-cart-plus mdi-36px mb-2"></i>
                                                    <h6 class="mb-0">New Sale</h6>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <a href="add_item.php" class="text-decoration-none">
                                            <div class="card bg-gradient-info text-white shadow-sm hover-card">
                                                <div class="card-body text-center p-3">
                                                    <i class="mdi mdi-package-variant-closed mdi-36px mb-2"></i>
                                                    <h6 class="mb-0">Add Inventory</h6>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <a href="add_supplier.php" class="text-decoration-none">
                                            <div class="card bg-gradient-warning text-white shadow-sm hover-card">
                                                <div class="card-body text-center p-3">
                                                    <i class="mdi mdi-account-plus mdi-36px mb-2"></i>
                                                    <h6 class="mb-0">Add Supplier</h6>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <a href="report.php" class="text-decoration-none">
                                            <div class="card bg-gradient-primary text-white shadow-sm hover-card">
                                                <div class="card-body text-center p-3">
                                                    <i class="mdi mdi-file-document mdi-36px mb-2"></i>
                                                    <h6 class="mb-0">Generate Report</h6>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Common content continues below -->
                <div class="row">
                    <!-- Low Stock Items Section -->
                    <div class="col-md-5 grid-margin stretch-card">
                        <div class="card shadow border-0" style="border-radius: 15px;">
                            <div class="card-body">
                                <h4 class="card-title text-primary mb-4">
                                    <i class="mdi mdi-alert-circle text-danger me-2"></i>
                                    Low Stock Inventory Items
                                </h4>
                                <div class="low-stock-items">
                                    <?php
                                    $lowStockQuery = "SELECT * FROM InventoryItem WHERE Quantity < 10 AND is_deleted = 0 ORDER BY Quantity ASC LIMIT 5";
                                    $lowStockResult = mysqli_query($con, $lowStockQuery);
                                    
                                    if (mysqli_num_rows($lowStockResult) > 0) {
                                        while ($row = mysqli_fetch_assoc($lowStockResult)) {
                                            echo '<div class="card mb-3 border-0 bg-light">';
                                            echo '<div class="card-body p-3">';
                                            echo '<div class="d-flex justify-content-between align-items-center">';
                                            echo '<div>';
                                            echo '<h6 class="mb-1">' . htmlspecialchars($row['ItemName']) . '</h6>';
                                            echo '<small class="text-muted">Location: ' . htmlspecialchars($row['Location']) . '</small>';
                                            echo '</div>';
                                            echo '<span class="badge bg-danger">' . htmlspecialchars($row['Quantity']) . ' items</span>';
                                            echo '</div>';
                                            echo '</div>';
                                            echo '</div>';
                                        }
                                    } else {
                                        echo '<div class="text-center text-success p-3">All items are sufficiently stocked.</div>';
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Sales Transactions Section -->
                    <div class="col-md-7 grid-margin stretch-card">
                        <div class="card shadow border-0" style="border-radius: 15px;">
                            <div class="card-body">
                                <h4 class="card-title text-primary mb-4">
                                    <i class="mdi mdi-cash-multiple text-success me-2"></i>
                                    Recent Sales Transactions
                                </h4>
                                <div class="recent-sales">
                                    <?php
                                    $recentSalesQuery = "SELECT s.TransactionID, i.ItemName, s.QuantitySold, s.SaleDate, s.TotalPrice
                                                        FROM SalesTransaction s
                                                        JOIN InventoryItem i ON s.ItemID = i.ItemID
                                                        ORDER BY s.SaleDate DESC
                                                        LIMIT 5";
                                    $recentSalesResult = mysqli_query($con, $recentSalesQuery);

                                    if (mysqli_num_rows($recentSalesResult) > 0) {
                                        while ($row = mysqli_fetch_assoc($recentSalesResult)) {
                                            echo '<div class="card mb-3 border-0 bg-light">';
                                            echo '<div class="card-body p-3">';
                                            echo '<div class="d-flex justify-content-between align-items-center">';
                                            echo '<div>';
                                            echo '<h6 class="mb-1">' . htmlspecialchars($row['ItemName']) . '</h6>';
                                            echo '<small class="text-muted">Date: ' . date('M d, Y', strtotime($row['SaleDate'])) . '</small>';
                                            echo '</div>';
                                            echo '<div class="text-end">';
                                            echo '<span class="badge bg-success">' . htmlspecialchars($row['QuantitySold']) . ' units</span>';
                                            echo '<div class="mt-1"><small class="text-primary">₹' . number_format($row['TotalPrice'], 2) . '</small></div>';
                                            echo '</div>';
                                            echo '</div>';
                                            echo '</div>';
                                            echo '</div>';
                                        }
                                    } else {
                                        echo '<div class="text-center text-muted p-3">No recent sales transactions.</div>';
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Supplier Updates Section -->
                <div class="row">
                    <div class="col-md-7 grid-margin stretch-card">
                        <div class="card shadow border-0" style="border-radius: 15px;">
                            <div class="card-body">
                                <h4 class="card-title text-primary mb-4">
                                    <i class="mdi mdi-truck-delivery text-info me-2"></i>
                                    Recent Supplier Updates
                                </h4>
                                <div class="supplier-updates">
                                    <?php
                                    // Fetch recent supplier updates
                                    $supplierUpdateQuery = "SELECT * FROM Supplier WHERE is_deleted = 0 ORDER BY UpdatedAt DESC LIMIT 5";
                                    $supplierUpdateResult = mysqli_query($con, $supplierUpdateQuery);

                                    if (mysqli_num_rows($supplierUpdateResult) > 0) {
                                        while ($row = mysqli_fetch_assoc($supplierUpdateResult)) {
                                            echo '<div class="card mb-3 border-0 bg-light hover-effect">';
                                            echo '<div class="card-body p-3">';
                                            echo '<div class="d-flex justify-content-between align-items-center">';
                                            echo '<div>';
                                            echo '<h6 class="mb-1 text-primary">' . htmlspecialchars($row['SupplierName']) . '</h6>';
                                            echo '<small class="text-muted">Contact: ' . htmlspecialchars($row['ContactPerson']) . '</small>';
                                            echo '</div>';
                                            echo '<div class="text-end">';
                                            echo '<small class="text-success">Last Updated</small>';
                                            echo '<div class="mt-1"><small class="text-muted">' . date('M d, Y', strtotime($row['UpdatedAt'])) . '</small></div>';
                                            echo '</div>';
                                            echo '</div>';
                                            echo '</div>';
                                            echo '</div>';
                                        }
                                    } else {
                                        echo '<div class="text-center text-muted p-3">No recent updates on suppliers.</div>';
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5 grid-margin stretch-card">
                        <div class="card shadow border-0" style="border-radius: 15px;">
                            <div class="card-body">
                                <h4 class="card-title text-primary">
                                    <i class="mdi mdi-chart-bar text-warning me-2"></i>
                                    Inventory Status Overview
                                </h4>
                                <canvas id="inventoryChart" style="height: 250px;"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Inventory Status Chart Section -->
                <div class="row mt-6">
                    <div class="col-md-6 grid-margin stretch-card">
                        <div class="card shadow border-0" style="border-radius: 15px;">
                            <div class="card-body">
                                <h4 class="card-title text-primary">
                                    <i class="mdi mdi-trending-up text-success me-2"></i>
                                    Monthly Sales Trend
                                </h4>
                                <canvas id="salesChart" style="height: 250px;"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- More common content -->
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Initialize charts
document.addEventListener('DOMContentLoaded', function() {
    // Inventory Status Chart
    new Chart(document.getElementById('inventoryChart'), {
        type: 'doughnut',
        data: {
            labels: ['In Stock', 'Low Stock', 'Out of Stock'],
            datasets: [{
                data: [65, 25, 10],
                backgroundColor: [
                    '#4CAF50',
                    '#FFC107', 
                    '#F44336'
                ]
            }]
        }
    });

    // Sales Trend Chart  
    new Chart(document.getElementById('salesChart'), {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            datasets: [{
                label: 'Sales',
                data: [30, 45, 35, 50, 40, 60],
                borderColor: '#2196F3',
                tension: 0.4
            }]
        }
    });
});
</script>
<?php include 'footer.php'; ?>
</body>
</html>