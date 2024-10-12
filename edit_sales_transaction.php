<?php
include 'auth.php';
include 'head.php';

// Get Transaction ID from the URL
if (!isset($_GET['id'])) {
    header("Location: sales_transaction.php");
    exit;
}
$transactionID = $_GET['id'];

// Fetch transaction details
$query = "SELECT * FROM SalesTransaction WHERE TransactionID = ?";
$stmt = $con->prepare($query);
$stmt->bind_param("i", $transactionID);
$stmt->execute();
$result = $stmt->get_result();
$transaction = $result->fetch_assoc();
$stmt->close();

if (!$transaction) {
    header("Location: sales_transaction.php");
    exit;
}

// Fetch inventory items for the dropdown
$query = "SELECT ItemID, ItemName, Price FROM InventoryItem WHERE Quantity > 0 AND is_deleted = 0";
$result = mysqli_query($con, $query);
$items = [];
while ($row = mysqli_fetch_assoc($result)) {
    $items[] = $row;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $itemID = $_POST['itemID'];
    $quantitySold = $_POST['quantitySold'];
    $saleDate = $_POST['saleDate'];
    $customerName = $_POST['customerName'];
    $customerEmail = $_POST['customerEmail'];
    $customerPhone = $_POST['customerPhone'];

    // Fetch the current quantity in inventory
    $query = "SELECT Quantity, Price FROM InventoryItem WHERE ItemID = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $itemID);
    $stmt->execute();
    $stmt->bind_result($dbQuantity, $itemPrice);
    $stmt->fetch();
    $stmt->close();

    // Check if sufficient quantity is available
    if ($quantitySold > $dbQuantity) {
        $error = "Not enough quantity available in inventory.";
    } else {
        // Update the inventory quantity
        $newQuantity = $dbQuantity - $quantitySold;
        $updateQuery = "UPDATE InventoryItem SET Quantity = ? WHERE ItemID = ?";
        $stmt = $con->prepare($updateQuery);
        $stmt->bind_param("ii", $newQuantity, $itemID);
        $stmt->execute();
        $stmt->close();

        // Calculate the new total price
        $totalPrice = $itemPrice * $quantitySold;

        // Update the sales transaction
        $updateQuery = "UPDATE SalesTransaction SET ItemID = ?, QuantitySold = ?, TotalPrice = ?, SaleDate = ?, CustomerName = ?, CustomerEmail = ?, CustomerPhone = ? WHERE TransactionID = ?";
        $stmt = $con->prepare($updateQuery);
        $stmt->bind_param("iidssssi", $itemID, $quantitySold, $totalPrice, $saleDate, $customerName, $customerEmail, $customerPhone, $transactionID);

        if ($stmt->execute()) {
            header("Location: sales_transaction.php?message=Transaction+updated+successfully");
            exit;
        } else {
            $error = "Failed to update transaction.";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Edit Sales Transaction</title>
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
                            <i class="mdi mdi-pencil menu-icon"></i>
                        </span>
                        Edit Sales Transaction
                    </h3>
                </div>

                <div class="container">
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger">
                            <?php echo $error; ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="">
                        <div class="form-group">
                            <label for="itemID">Item</label>
                            <select name="itemID" id="itemID" class="form-control" required>
                                <?php foreach ($items as $item): ?>
                                    <option value="<?php echo $item['ItemID']; ?>" <?php echo ($transaction['ItemID'] == $item['ItemID']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($item['ItemName']); ?> (Price: ₹<?php echo number_format($item['Price'], 2); ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="quantitySold">Quantity Sold</label>
                            <input type="number" name="quantitySold" id="quantitySold" class="form-control" value="<?php echo $transaction['QuantitySold']; ?>" required />
                        </div>

                        <div class="form-group">
                            <label for="saleDate">Sale Date</label>
                            <input type="date" name="saleDate" id="saleDate" class="form-control" value="<?php echo $transaction['SaleDate']; ?>" required />
                        </div>

                        <div class="form-group">
                            <label for="customerName">Customer Name</label>
                            <input type="text" name="customerName" id="customerName" class="form-control" value="<?php echo htmlspecialchars($transaction['CustomerName']); ?>" />
                        </div>

                        <div class="form-group">
                            <label for="customerEmail">Customer Email</label>
                            <input type="email" name="customerEmail" id="customerEmail" class="form-control" value="<?php echo htmlspecialchars($transaction['CustomerEmail']); ?>" />
                        </div>

                        <div class="form-group">
                            <label for="customerPhone">Customer Phone</label>
                            <input type="text" name="customerPhone" id="customerPhone" class="form-control" value="<?php echo htmlspecialchars($transaction['CustomerPhone']); ?>" />
                        </div>

                        <button type="submit" class="btn btn-gradient-success mt-3">Update Transaction</button>
                        <a href="sales_transaction.php" class="btn btn-light mt-3">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include 'footer.php'; ?>
</body>
</html>