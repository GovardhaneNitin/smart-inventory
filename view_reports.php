<?php
include 'auth.php';
include 'head.php';

// Get the report type from the query parameter
$requestedReportType = isset($_GET['reportType']) ? $_GET['reportType'] : null;

// Fetch all available reports from the "reports" directory that match the requested type
$reportDirectory = 'reports/';
$reportFiles = array_diff(scandir($reportDirectory), array('.', '..'));
$filteredReportFiles = [];

if ($requestedReportType) {
    foreach ($reportFiles as $reportFile) {
        if (strpos($reportFile, $requestedReportType) !== false) {
            $filteredReportFiles[] = $reportFile;
        }
    }
}

$successMessage = '';
$errorMessage = '';

// Check for success or error messages in the query parameters
if (isset($_GET['success']) && $_GET['success'] === 'true' && isset($_GET['reportFile'])) {
    $successMessage = "Report generated successfully: " . htmlspecialchars($_GET['reportFile']);
}
if (isset($_GET['error'])) {
    switch ($_GET['error']) {
        case 'invalid_parameters':
            $errorMessage = "Invalid parameters provided for report generation.";
            break;
        case 'db_connection_failed':
            $errorMessage = "Database connection failed.";
            break;
        case 'invalid_report_type':
            $errorMessage = "Invalid report type.";
            break;
        case 'sql_prepare_failed':
            $errorMessage = "Failed to prepare the SQL query.";
            break;
        case 'sql_execution_failed':
            $errorMessage = "SQL query execution failed.";
            break;
        case 'report_insert_failed':
            $errorMessage = "Failed to insert report details into the database.";
            break;
        case 'report_save_failed':
            $errorMessage = "Failed to save the report.";
            break;
        default:
            $errorMessage = "An unknown error occurred.";
            break;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>View Reports</title>
    <?php include 'head.php'; ?>
    <style>
        .report-container {
            background: #ffffff;
            padding: 25px;
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .report-title {
            font-size: 1.8rem;
            color: #2d3436;
            margin-bottom: 20px;
            font-weight: 600;
            border-bottom: 2px solid #6c5ce7;
            padding-bottom: 10px;
        }
        .report-content {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            border: 1px solid #e9ecef;
            font-family: 'Courier New', monospace;
            max-height: 500px;
            overflow-y: auto;
        }
        .print-btn {
            margin-top: 20px;
            padding: 10px 25px;
            border-radius: 25px;
            transition: all 0.3s ease;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .print-btn:hover {
            transform: scale(1.05);
            box-shadow: 0 5px 15px rgba(108, 92, 231, 0.3);
        }
        .print-btn i {
            font-size: 1.2rem;
        }
        .page-title {
            font-size: 2.2rem;
            margin-bottom: 30px;
            color: #2d3436;
        }
        .page-title-icon {
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
        }
        .alert {
            border-radius: 10px;
            padding: 15px 25px;
            margin-bottom: 25px;
            border: none;
        }
        @media print {
            .no-print {
                display: none;
            }
            .report-container {
                page-break-before: always;
                box-shadow: none;
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
                                <i class="mdi mdi-file-document"></i>
                            </span>
                            View Reports
                        </h3>
                    </div>

                    <div class="container">
                        <?php if (!empty($successMessage)) : ?>
                            <div class="alert alert-success animate__animated animate__fadeIn">
                                <i class="mdi mdi-check-circle me-2"></i><?php echo $successMessage; ?>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($errorMessage)) : ?>
                            <div class="alert alert-danger animate__animated animate__fadeIn">
                                <i class="mdi mdi-alert-circle me-2"></i><?php echo $errorMessage; ?>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($filteredReportFiles)) : ?>
                            <div class="row">
                            <?php foreach ($filteredReportFiles as $reportFile) : ?>
                                <?php
                                $filePath = $reportDirectory . $reportFile;
                                $fileContents = file_get_contents($filePath);
                                ?>
                                <div class="col-12">
                                    <div class="report-container animate__animated animate__fadeInUp">
                                        <div class="report-title">
                                            <i class="mdi mdi-file-document-outline me-2"></i>
                                            <?php echo htmlspecialchars($reportFile); ?>
                                        </div>
                                        <pre class="report-content"><?php echo htmlspecialchars($fileContents); ?></pre>
                                        <div class="d-flex justify-content-end no-print">
                                            <button class="btn btn-gradient-success print-btn" onclick="printThisReport(this)">
                                                <i class="mdi mdi-printer me-2"></i> Print Report
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                            </div>
                        <?php else : ?>
                            <div class="text-center mt-5">
                                <i class="mdi mdi-file-document-outline" style="font-size: 5rem; color: #dfe6e9;"></i>
                                <p class="text-muted mt-3">No reports available to display for the selected type.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include 'footer.php'; ?>

    <script>
        function printThisReport(button) {
            const reportContainer = button.closest('.report-container');
            const originalContents = document.body.innerHTML;
            const printContents = reportContainer.outerHTML;

            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
            
            window.location.reload();
        }
    </script>
</body>
</html>
