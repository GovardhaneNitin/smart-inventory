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
                            <div class="card card3">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-4">
                                        <img src="assets/images/InventoryItems/<?php echo htmlspecialchars($row['Image']); ?>" alt="<?php echo htmlspecialchars($row['ItemName']); ?>" class="img-fluid" style="width: 70px; height: 70px; object-fit: cover;">
                                        <div class="ms-3">
                                            <h4 class="card-title mb-0"><?php echo htmlspecialchars($row['ItemName']); ?></h4>
                                            <p class="text-muted"><?php echo htmlspecialchars($row['Category']); ?></p>
                                        </div>
                                    </div>
                                    <ul class="list-unstyled">
                                        <li><i class="mdi mdi-cash text-primary"></i> Total Revenue: <strong>₹<?php echo number_format($row['TotalRevenue'], 2); ?></strong></li>
                                        <li><i class="mdi mdi-chart-line text-primary"></i> Total Sold: <strong><?php echo htmlspecialchars($row['TotalSold']); ?></strong></li>
                                        <li><i class="mdi mdi-cube-outline text-primary"></i> Quantity Left: <strong><?php echo htmlspecialchars($row['Quantity']); ?></strong></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Rest of the Products in Table Format -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Product Details</h4>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>Image</th>
                                                <th>Product Name</th>
                                                <th>Total Sold</th>
                                                <th>Quantity Left</th>
                                                <th>Total Revenue</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($restProducts as $product): ?>
                                                <tr>
                                                    <td>
                                                        <img src="assets/images/InventoryItems/<?php echo htmlspecialchars($product['Image']); ?>" class="img-fluid rounded" style="width: 50px; height: 50px; object-fit: cover;" alt="Product Image">
                                                    </td>
                                                    <td><?php echo htmlspecialchars($product['ItemName']); ?></td>
                                                    <td><?php echo $product['TotalSold']; ?></td>
                                                    <td><?php echo $product['Quantity']; ?></td>
                                                    <td>₹<?php echo number_format($product['TotalRevenue'], 2); ?></td>
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
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Total Items Sold</h4>
                                <canvas id="totalSoldChart"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Quantity Left in Inventory</h4>
                                <canvas id="quantityLeftChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Total Revenue per Product</h4>
                                <canvas id="totalRevenueChart"></canvas>
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