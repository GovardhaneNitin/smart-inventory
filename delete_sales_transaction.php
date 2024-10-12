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

// Update inventory quantity after deleting sales transaction
$query = "SELECT Quantity FROM InventoryItem WHERE ItemID = ?";
$stmt = $con->prepare($query);
$stmt->bind_param("i", $transaction['ItemID']);
$stmt->execute();
$stmt->bind_result($dbQuantity);
$stmt->fetch();
$stmt->close();

$newQuantity = $dbQuantity + $transaction['QuantitySold'];
$updateQuery = "UPDATE InventoryItem SET Quantity = ? WHERE ItemID = ?";
$stmt = $con->prepare($updateQuery);
$stmt->bind_param("ii", $newQuantity, $transaction['ItemID']);
$stmt->execute();
$stmt->close();

// Delete the sales transaction
$deleteQuery = "DELETE FROM SalesTransaction WHERE TransactionID = ?";
$stmt = $con->prepare($deleteQuery);
$stmt->bind_param("i", $transactionID);

if ($stmt->execute()) {
    header("Location: sales_transaction.php?message=Transaction+deleted+successfully");
} else {
    header("Location: sales_transaction.php?error=Failed+to+delete+transaction");
}
$stmt->close();
exit;
?>
