<?php include 'auth.php'; ?>
<?php include 'head.php'; ?>

<?php
$error = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $itemID = $_POST['itemID'];
    $quantitySold = $_POST['quantitySold'];
    $customerName = $_POST['customerName'];
    $customerEmail = $_POST['customerEmail'];
    $customerPhone = $_POST['customerPhone'];

    // Fetch the available quantity from InventoryItem
    $query = "SELECT Quantity, Price FROM InventoryItem WHERE ItemID = ? AND is_deleted = 0";
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $itemID);
    $stmt->execute();
    $stmt->bind_result($availableQuantity, $itemPrice);
    $stmt->fetch();
    $stmt->close();

    // Check if the quantity is available
    if ($quantitySold > $availableQuantity) {
        $error = "Insufficient quantity available in inventory.";
    } else {
        // Calculate total price
        $totalPrice = $quantitySold * $itemPrice;

        // Insert the sales transaction
        $insertQuery = "INSERT INTO SalesTransaction (ItemID, UserID, QuantitySold, TotalPrice, SaleDate, CustomerName, CustomerEmail, CustomerPhone) VALUES (?, ?, ?, ?, NOW(), ?, ?, ?)";
        $stmt = $con->prepare($insertQuery);
        $userID = $_SESSION['user_id'];
        $stmt->bind_param("iiidsss", $itemID, $userID, $quantitySold, $totalPrice, $customerName, $customerEmail, $customerPhone);

        if ($stmt->execute()) {
            // Update the inventory quantity
            $updateQuery = "UPDATE InventoryItem SET Quantity = Quantity - ? WHERE ItemID = ?";
            $updateStmt = $con->prepare($updateQuery);
            $updateStmt->bind_param("ii", $quantitySold, $itemID);
            $updateStmt->execute();
            $updateStmt->close();

            $success = "Transaction added successfully.";
        } else {
            $error = "Failed to add transaction. Please try again.";
        }
        $stmt->close();
    }
}

// Fetch all items for the dropdown, including available quantity
$itemsQuery = "SELECT ItemID, ItemName, Quantity FROM InventoryItem WHERE is_deleted = 0";
$itemsResult = mysqli_query($con, $itemsQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Add Sales Transaction</title>
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
                            <i class="mdi mdi-cart"></i>
                        </span>
                        Add Sales Transaction
                    </h3>
                </div>

                <div class="row justify-content-center">
                    <div class="col-md-8 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Add New Transaction</h4>
                                <form method="POST" action="" class="forms-sample">
                                    <div class="form-group">
                                        <label for="itemID">Item</label>
                                        <select class="form-control" id="itemID" name="itemID" required onchange="updateAvailableQuantity()">
                                            <option value="">Select Item</option>
                                            <?php while ($row = mysqli_fetch_assoc($itemsResult)): ?>
                                                <option value="<?php echo $row['ItemID']; ?>" data-quantity="<?php echo $row['Quantity']; ?>">
                                                    <?php echo htmlspecialchars($row['ItemName']); ?> (Available: <?php echo $row['Quantity']; ?>)
                                                </option>
                                            <?php endwhile; ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="quantitySold">Quantity Sold <span id="availableQuantityInfo"></span></label>
                                        <input type="number" class="form-control" id="quantitySold" name="quantitySold" min="1" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="customerName">Customer Name</label>
                                        <input type="text" class="form-control" id="customerName" name="customerName" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="customerEmail">Customer Email</label>
                                        <input type="email" class="form-control" id="customerEmail" name="customerEmail">
                                    </div>
                                    <div class="form-group">
                                        <label for="customerPhone">Customer Phone</label>
                                        <input type="text" class="form-control" id="customerPhone" name="customerPhone">
                                    </div>
                                    <button type="submit" class="btn btn-gradient-success mr-2">Submit</button>
                                    <button type="reset" class="btn btn-light">Cancel</button>
                                </form>

                                <?php if (!empty($error)): ?>
                                    <div class="alert alert-danger mt-3">
                                        <?php echo htmlspecialchars($error); ?>
                                    </div>
                                <?php elseif (!empty($success)): ?>
                                    <div class="alert alert-success mt-3">
                                        <?php echo htmlspecialchars($success); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include 'footer.php'; ?>

<script>
// JavaScript to update available quantity when an item is selected
function updateAvailableQuantity() {
    const itemSelect = document.getElementById('itemID');
    const availableQuantityInfo = document.getElementById('availableQuantityInfo');
    const selectedItem = itemSelect.options[itemSelect.selectedIndex];
    const availableQuantity = selectedItem.getAttribute('data-quantity');

    if (availableQuantity) {
        availableQuantityInfo.textContent = `(Available: ${availableQuantity})`;
    } else {
        availableQuantityInfo.textContent = '';
    }
}
</script>

</body>
</html>
