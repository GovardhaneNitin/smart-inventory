<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

// Get the user's role from the session
$role = $_SESSION['role'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <title>Dashboard</title>
    <?php include 'head.php'; ?>
    <style>
    .card:hover {
      transform: scale(1.05);
      transition: transform 0.3s ease;
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
                                <h4 class="font-weight-normal mb-3">Weekly Sales <i class="mdi mdi-chart-line mdi-24px float-end"></i></h4>
                                <h2 class="mb-5">15,0000</h2>
                                <h6 class="card-text">Increased by 60%</h6>
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
                    <div class="col-md-7 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Stock Inventory Status</h4>
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Item Name</th>
                                                <th>Stock Date</th>
                                                <th>Stock Level</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>1</td>
                                                <td>Rice</td>
                                                <td>Sept 01, 2024</td>
                                                <td>
                                                    <div class="progress">
                                                        <div class="progress-bar bg-gradient-success" role="progressbar" style="width: 80%" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>2</td>
                                                <td>Wheat</td>
                                                <td>Sept 05, 2024</td>
                                                <td>
                                                    <div class="progress">
                                                        <div class="progress-bar bg-gradient-warning" role="progressbar" style="width: 60%" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>3</td>
                                                <td>Corn</td>
                                                <td>Sept 10, 2024</td>
                                                <td>
                                                    <div class="progress">
                                                        <div class="progress-bar bg-gradient-info" role="progressbar" style="width: 90%" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>4</td>
                                                <td>Barley</td>
                                                <td>Sept 15, 2024</td>
                                                <td>
                                                    <div class="progress">
                                                        <div class="progress-bar bg-gradient-danger" role="progressbar" style="width: 40%" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>5</td>
                                                <td>Oats</td>
                                                <td>Sept 20, 2024</td>
                                                <td>
                                                    <div class="progress">
                                                        <div class="progress-bar bg-gradient-primary" role="progressbar" style="width: 70%" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>6</td>
                                                <td>Quinoa</td>
                                                <td>Sept 25, 2024</td>
                                                <td>
                                                    <div class="progress">
                                                        <div class="progress-bar bg-gradient-success" role="progressbar" style="width: 85%" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>7</td>
                                                <td>Millet</td>
                                                <td>Sept 30, 2024</td>
                                                <td>
                                                    <div class="progress">
                                                        <div class="progress-bar bg-gradient-secondary" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>8</td>
                                                <td>Sorghum</td>
                                                <td>Oct 05, 2024</td>
                                                <td>
                                                    <div class="progress">
                                                        <div class="progress-bar bg-gradient-dark" role="progressbar" style="width: 75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <!-- More stock items -->
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
