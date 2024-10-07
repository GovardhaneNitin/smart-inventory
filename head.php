<?php 
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

$con = mysqli_connect("localhost", "root", "", "smart_inventory_db");
if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  exit();
}
?>

<style>
  /* Page Title Animation */
  .page-title {
        animation: fadeInDown 1s ease-in-out;
    }

    @keyframes fadeInDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="assets/vendors/mdi/css/materialdesignicons.min.css" />
<link rel="stylesheet" href="assets/vendors/ti-icons/css/themify-icons.css" />
<link rel="stylesheet" href="assets/vendors/css/vendor.bundle.base.css" />
<link rel="stylesheet" href="assets/vendors/font-awesome/css/font-awesome.min.css" />


<link rel="stylesheet" href="assets/vendors/font-awesome/css/font-awesome.min.css" />
<link rel="stylesheet" href="assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css" />

<link rel="stylesheet" href="assets/css/style.css" />

<link rel="shortcut icon" href="assets/images/smart-inventory.png" />