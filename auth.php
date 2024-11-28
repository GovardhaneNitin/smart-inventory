<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

// Debugging: Check if the session role is set correctly
if (!isset($_SESSION['role'])) {
    echo "Role is not set!";
    exit;
}

// List of pages that only Admins or Managers should access
$restricted_pages = ['add_item.php', 'edit_item.php', 'delete_item.php', 'add_supplier.php', 'edit_supplier.php', 'delete_supplier.php', 'admin_manage_roles.php', 'permanent_delete_supplier.php', 'restore_supplier.php', 'edit_sales_transaction.php', 'delete_sales_transaction.php', 'generate_report.php', 'predictive_analysis.php', 'report.php', 'save_prediction.php', 'view_reports.php'];

// Get the current script name
$current_page = basename($_SERVER['PHP_SELF']);

// If the user is on a restricted page and their role is not Admin or Manager, redirect them to no_access.php
if (in_array($current_page, $restricted_pages) && $_SESSION['role'] !== 'Admin' && $_SESSION['role'] !== 'Manager') {
    header("Location: pages/no_access.php");
    exit;
}
?>
