<?php
include 'auth.php';
include 'head.php';

// Product and Sales details
$productQuery = "
    SELECT i.ItemID, i.ItemName, i.Category, i.Quantity, i.Price, i.Image, COALESCE(SUM(s.QuantitySold), 0) AS TotalSold, COALESCE(SUM(s.TotalPrice), 0) AS TotalRevenue
    FROM InventoryItem i
    LEFT JOIN SalesTransaction s ON i.ItemID = s.ItemID
    WHERE i.is_deleted = 0
    GROUP BY i.ItemID
    ORDER BY i.ItemName ASC
";
$productResult = mysqli_query($con, $productQuery);

// Data for charts and display
$products = [];
while ($row = mysqli_fetch_assoc($productResult)) {
    $products[] = $row;
}

// Sort products by TotalRevenue in descending order
usort($products, function($a, $b) {
    return $b['TotalRevenue'] <=> $a['TotalRevenue'];
});

// Split products into top 3 and the rest
$topProducts = array_slice($products, 0, 3);
$restProducts = array_slice($products, 3);

// Prepare data for charts
$productNames = [];
$totalSold = [];
$quantityLeft = [];
$totalRevenue = [];

foreach ($products as $product) {
    $productNames[] = $product['ItemName'];
    $totalSold[] = $product['TotalSold'];
    $quantityLeft[] = $product['Quantity'];
    $totalRevenue[] = $product['TotalRevenue'];
}

// Convert data arrays to JSON for chart.js
$productNamesJson = json_encode($productNames);
$totalSoldJson = json_encode($totalSold);
$quantityLeftJson = json_encode($quantityLeft);
$totalRevenueJson = json_encode($totalRevenue);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <title>Product and Sales Details</title>
</head>
<style>
    .card3:hover {
        transform: scale(1.05);
        transition: transform 0.3s ease;
    }
</style>
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
                            <i class="mdi mdi-cube"></i>
                        </span>
                        Product and Sales Details
                    </h3>
                </div>

                <!-- Product Overview Cards (Top 3 Most Revenue Products) -->
                <div class="row">
                    <?php foreach ($topProducts as $row): ?>
                        <div class="col-md-4 stretch-card grid-margin">
                            <div class="card card3 bg-gradient-light shadow">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-4">
                                        <img src="<?php echo htmlspecialchars($row['Image']); ?>" 
                                             alt="<?php echo htmlspecialchars($row['ItemName']); ?>" 
                                             class="img-fluid rounded-circle border border-primary p-2" 
                                             style="width: 80px; height: 80px; object-fit: cover;">
                                        <div class="ms-3">
                                            <h4 class="card-title mb-0 text-primary"><?php echo htmlspecialchars($row['ItemName']); ?></h4>
                                            <p class="text-muted"><i class="mdi mdi-tag"></i> <?php echo htmlspecialchars($row['Category']); ?></p>
                                        </div>
                                    </div>
                                    <ul class="list-unstyled">
                                        <li class="mb-3 p-2 bg-light rounded d-flex justify-content-between align-items-center">
                                            <span><i class="mdi mdi-cash text-success"></i> Total Revenue:</span>
                                            <span class="text-success">₹<?php echo number_format($row['TotalRevenue'], 2); ?></span>
                                        </li>
                                        <li class="mb-3 p-2 bg-light rounded d-flex justify-content-between align-items-center">
                                            <span><i class="mdi mdi-chart-line text-info"></i> Total Sold:</span>
                                            <span class="text-info"><?php echo htmlspecialchars($row['TotalSold']); ?></span>
                                        </li>
                                        <li class="p-2 bg-light rounded d-flex justify-content-between align-items-center">
                                            <span><i class="mdi mdi-cube-outline text-warning"></i> Quantity Left:</span>
                                            <span class="text-warning"><?php echo htmlspecialchars($row['Quantity']); ?></span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Rest of the Products in Table Format -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h4 class="card-title mb-4">
                                    <i class="mdi mdi-format-list-bulleted text-primary"></i>
                                    Other Products Performance
                                </h4>
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead class="bg-gradient-primary text-white">
                                            <tr>
                                                <th class="align-middle text-center">Image</th>
                                                <th class="align-middle">Product Name</th>
                                                <th class="align-middle text-center">
                                                    <i class="mdi mdi-chart-line"></i> Total Sold
                                                </th>
                                                <th class="align-middle text-center">
                                                    <i class="mdi mdi-cube-outline"></i> In Stock
                                                </th>
                                                <th class="align-middle text-center">
                                                    <i class="mdi mdi-currency-inr"></i> Revenue
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($restProducts as $product): ?>
                                                <tr class="align-middle">
                                                    <td class="text-center">
                                                        <img src="<?php echo htmlspecialchars($product['Image']); ?>" 
                                                             class="rounded-circle border border-primary p-1" 
                                                             style="width: 50px; height: 50px; object-fit: cover;" 
                                                             alt="<?php echo htmlspecialchars($product['ItemName']); ?>">
                                                    </td>
                                                    <td>
                                                        <?php echo htmlspecialchars($product['ItemName']); ?>
                                                        <br>
                                                        <small class="text-muted"><?php echo htmlspecialchars($product['Category']); ?></small>
                                                    </td>
                                                    <td class="text-center">
                                                        <span class="badge bg-info">
                                                            <?php echo $product['TotalSold']; ?>
                                                        </span>
                                                    </td>
                                                    <td class="text-center">
                                                        <span class="badge bg-warning">
                                                            <?php echo $product['Quantity']; ?>
                                                        </span>
                                                    </td>
                                                    <td class="text-center text-success">
                                                        ₹<?php echo number_format($product['TotalRevenue'], 2); ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sales Summary Charts -->
                <div class="row mt-4">
                    <div class="col-md-6 grid-margin stretch-card">
                        <div class="card shadow-lg border-0 rounded-lg">
                            <div class="card-body bg-gradient-light">
                                <h4 class="card-title mb-4">
                                    <i class="mdi mdi-chart-bar text-primary me-2"></i>
                                    Total Items Sold
                                </h4>
                                <div class="p-3 bg-white rounded shadow-sm">
                                    <canvas id="totalSoldChart" height="300"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 grid-margin stretch-card">
                        <div class="card shadow-lg border-0 rounded-lg">
                            <div class="card-body bg-gradient-light">
                                <h4 class="card-title mb-4">
                                    <i class="mdi mdi-package-variant text-warning me-2"></i>
                                    Quantity Left in Inventory
                                </h4>
                                <div class="p-3 bg-white rounded shadow-sm">
                                    <canvas id="quantityLeftChart" height="300"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-md-12 grid-margin stretch-card">
                        <div class="card shadow-lg border-0 rounded-lg">
                            <div class="card-body bg-gradient-light">
                                <h4 class="card-title mb-4">
                                    <i class="mdi mdi-chart-line text-success me-2"></i>
                                    Revenue Analysis
                                </h4>
                                <div class="p-4 bg-white rounded shadow-sm">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span class="text-muted">Product Revenue Distribution</span>
                                        <div class="badge bg-success px-3 py-2">
                                            <i class="mdi mdi-currency-inr"></i>
                                            Total Revenue: ₹<?php echo number_format(array_sum($totalRevenue), 2); ?>
                                        </div>
                                    </div>
                                    <canvas id="totalRevenueChart" height="250"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include 'footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Chart.js - Total Items Sold
    var ctxTotalSold = document.getElementById('totalSoldChart').getContext('2d');
    new Chart(ctxTotalSold, {
        type: 'bar',
        data: {
            labels: <?php echo $productNamesJson; ?>,
            datasets: [{
                label: 'Total Items Sold',
                data: <?php echo $totalSoldJson; ?>,
                backgroundColor: 'rgba(54, 162, 235, 0.7)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Chart.js - Quantity Left in Inventory
    var ctxQuantityLeft = document.getElementById('quantityLeftChart').getContext('2d');
    new Chart(ctxQuantityLeft, {
        type: 'bar',
        data: {
            labels: <?php echo $productNamesJson; ?>,
            datasets: [{
                label: 'Quantity Left',
                data: <?php echo $quantityLeftJson; ?>,
                backgroundColor: 'rgba(255, 206, 86, 0.7)',
                borderColor: 'rgba(255, 206, 86, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Chart.js - Total Revenue per Product
    var ctxTotalRevenue = document.getElementById('totalRevenueChart').getContext('2d');
    new Chart(ctxTotalRevenue, {
        type: 'line',
        data: {
            labels: <?php echo $productNamesJson; ?>,
            datasets: [{
                label: 'Total Revenue',
                data: <?php echo $totalRevenueJson; ?>,
                backgroundColor: 'rgba(75, 192, 192, 0.4)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 2,
                fill: true
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
</body>
</html>