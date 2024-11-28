<?php
include 'auth.php';
include 'head.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');

// Check if the logged-in user is allowed to generate reports
if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['Admin', 'Manager'])) {
    echo json_encode(["error" => "Unauthorized access"]);
    exit();
}

// Fetch the report type from the request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $reportType = $_POST['reportType'];
    $userID = $_SESSION['user_id']; // Assuming you store logged-in user ID in the session
    $dateRangeStart = $_POST['dateRangeStart'];
    $dateRangeEnd = $_POST['dateRangeEnd'];

    if (empty($reportType) || empty($dateRangeStart) || empty($dateRangeEnd)) {
        echo json_encode(["error" => "Invalid parameters"]);
        exit();
    }

    // Initialize the report data
    $con = new mysqli('localhost', 'root', '', 'smart_inventory_db');
    if ($con->connect_error) {
        echo json_encode(["error" => "Database connection failed: " . $con->connect_error]);
        exit();
    }

    // Determine the correct query based on report type
    switch ($reportType) {
        case 'sales':
            $query = "SELECT * FROM SalesTransaction WHERE SaleDate BETWEEN ? AND ?";
            $reportName = "Sales Report";
            break;
        case 'inventory':
            $query = "SELECT * FROM InventoryItem WHERE UpdatedAt BETWEEN ? AND ?";
            $reportName = "Inventory Report";
            break;
        case 'supplier':
            $query = "SELECT * FROM Supplier WHERE UpdatedAt BETWEEN ? AND ?";
            $reportName = "Supplier Report";
            break;
        case 'predictive':
            $query = "SELECT * FROM Prediction WHERE UpdatedAt BETWEEN ? AND ?";
            $reportName = "Predictive Analysis Report";
            break;
        case 'financial':
            $query = "SELECT * FROM SalesTransaction WHERE SaleDate BETWEEN ? AND ?";
            $reportName = "Financial Report";
            break;
        default:
            echo json_encode(["error" => "Invalid report type"]);
            exit();
    }

    // Prepare and execute the query
    $stmt = $con->prepare($query);
    if (!$stmt) {
        echo json_encode(["error" => "Failed to prepare SQL query: " . $con->error]);
        exit();
    }

    $stmt->bind_param("ss", $dateRangeStart, $dateRangeEnd);
    if (!$stmt->execute()) {
        echo json_encode(["error" => "SQL Execution Error: " . $stmt->error]);
        exit();
    }

    $result = $stmt->get_result();

    // Fetch report data and save it to a file
    $timestamp = date('Y_m_d_H_i_s');
    $fileName = $reportType . '_report_' . $timestamp . '.txt';
    $filePath = 'reports/' . $fileName;  // Generate a filename based on the report type and current timestamp

    $file = fopen($filePath, 'w');
    if (!$file) {
        echo json_encode(["error" => "Failed to open file for writing"]);
        exit();
    }

    while ($row = $result->fetch_assoc()) {
        $reportData[] = $row;
        foreach ($row as $key => $value) {
            fwrite($file, "$key: $value\n");
        }
        fwrite($file, "\n--------------------\n");
    }
    fclose($file);

    // Store the report details in the Report table
    $reportFullName = $fileName; 

    $stmtInsert = $con->prepare("INSERT INTO Report (UserID, ReportType, DateRangeStart, DateRangeEnd) VALUES (?, ?, ?, ?)");
    if (!$stmtInsert) {
        echo json_encode(["error" => "Failed to prepare statement for report insert: " . $con->error]);
        exit();
    }

    $stmtInsert->bind_param("isss", $userID, $reportFullName, $dateRangeStart, $dateRangeEnd);

    if ($stmtInsert->execute()) {
        $reportID = $stmtInsert->insert_id;
        echo json_encode(["success" => true, "reportID" => $reportID, "reportFile" => $filePath]);
    } else {
        echo json_encode(["error" => "Failed to save the report: " . $stmtInsert->error]);
    }

    // Close connections
    $stmtInsert->close();
    $stmt->close();
    $con->close();
} else {
    echo json_encode(["error" => "Invalid request"]);
}