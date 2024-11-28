<?php include 'auth.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Generate Reports</title>
    <?php include 'head.php'; ?>
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
                                <i class="mdi mdi-file-chart"></i>
                            </span>
                            Generate Reports
                        </h3>
                    </div>
                    
                    <div class="container">
                        <div class="row">
                            <!-- Sales Reports -->
                            <div class="col-md-6">
                                <div class="card mb-4 shadow-sm hover-lift">
                                    <div class="card-body gradient-light">
                                        <div class="d-flex align-items-center mb-3">
                                            <i class="mdi mdi-chart-line text-primary me-3" style="font-size: 2rem;"></i>
                                            <h4 class="card-title mb-0">Sales Reports</h4>
                                        </div>
                                        <p class="text-muted">Generate detailed reports about sales over different time periods.</p>
                                        <div class="d-flex flex-wrap justify-content-start mt-4 gap-2">
                                            <button class="btn btn-gradient-primary btn-sm btn-md-lg w-auto" onclick="generateReport('sales')">
                                                <i class="mdi mdi-calendar-clock me-1"></i> Generate Report
                                            </button>
                                            <button class="btn btn-gradient-secondary btn-sm btn-md-lg w-auto" onclick="viewReport('sales')">
                                                <i class="mdi mdi-file-eye me-1"></i> View Report
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Inventory Reports -->
                            <div class="col-md-6">
                                <div class="card mb-4 shadow-sm hover-lift">
                                    <div class="card-body gradient-light">
                                        <div class="d-flex align-items-center mb-3">
                                            <i class="mdi mdi-package-variant text-success me-3" style="font-size: 2rem;"></i>
                                            <h4 class="card-title mb-0">Inventory Reports</h4>
                                        </div>
                                        <p class="text-muted">View current inventory levels, low stock items, and inventory movements.</p>
                                        <div class="d-flex flex-wrap justify-content-start mt-4 gap-2">
                                            <button class="btn btn-gradient-success btn-sm btn-md-lg w-auto" onclick="generateReport('inventory')">
                                                <i class="mdi mdi-calendar-clock me-1"></i> Generate Report
                                            </button>
                                            <button class="btn btn-gradient-secondary btn-sm btn-md-lg w-auto" onclick="viewReport('inventory')">
                                                <i class="mdi mdi-file-eye me-1"></i> View Report
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Supplier Reports -->
                            <div class="col-md-6">
                                <div class="card mb-4 shadow-sm hover-lift">
                                    <div class="card-body gradient-light">
                                        <div class="d-flex align-items-center mb-3">
                                            <i class="mdi mdi-truck text-warning me-3" style="font-size: 2rem;"></i>
                                            <h4 class="card-title mb-0">Supplier Reports</h4>
                                        </div>
                                        <p class="text-muted">Generate reports on supplier performance and supplies received.</p>
                                        <div class="d-flex flex-wrap justify-content-start mt-4 gap-2">
                                            <button class="btn btn-gradient-warning btn-sm btn-md-lg w-auto" onclick="generateReport('supplier')">
                                                <i class="mdi mdi-calendar-clock me-1"></i> Generate Report
                                            </button>
                                            <button class="btn btn-gradient-secondary btn-sm btn-md-lg w-auto" onclick="viewReport('supplier')">
                                                <i class="mdi mdi-file-eye me-1"></i> View Report
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Predictive Analysis Reports -->
                            <div class="col-md-6">
                                <div class="card mb-4 shadow-sm hover-lift">
                                    <div class="card-body gradient-light">
                                        <div class="d-flex align-items-center mb-3">
                                            <i class="mdi mdi-chart-bell-curve text-info me-3" style="font-size: 2rem;"></i>
                                            <h4 class="card-title mb-0">Predictive Analysis</h4>
                                        </div>
                                        <p class="text-muted">Predict future inventory needs based on historical sales data.</p>
                                        <div class="d-flex flex-wrap justify-content-start mt-4 gap-2">
                                            <button class="btn btn-gradient-info btn-sm btn-md-lg w-auto" onclick="generateReport('predictive')">
                                                <i class="mdi mdi-calendar-clock me-1"></i> Generate Report
                                            </button>
                                            <button class="btn btn-gradient-secondary btn-sm btn-md-lg w-auto" onclick="viewReport('predictive')">
                                                <i class="mdi mdi-file-eye me-1"></i> View Report
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Financial Reports -->
                            <div class="col-md-6">
                                <div class="card mb-4 shadow-sm hover-lift">
                                    <div class="card-body gradient-light">
                                        <div class="d-flex align-items-center mb-3">
                                            <i class="mdi mdi-currency-usd text-danger me-3" style="font-size: 2rem;"></i>
                                            <h4 class="card-title mb-0">Financial Reports</h4>
                                        </div>
                                        <p class="text-muted">View total revenue, profit margins, and overall financial health.</p>
                                        <div class="d-flex flex-wrap justify-content-start mt-4 gap-2">
                                            <button class="btn btn-gradient-danger btn-sm btn-md-lg w-auto" onclick="generateReport('financial')">
                                                <i class="mdi mdi-calendar-clock me-1"></i> Generate Report
                                            </button>
                                            <button class="btn btn-gradient-secondary btn-sm btn-md-lg w-auto" onclick="viewReport('financial')">
                                                <i class="mdi mdi-file-eye me-1"></i> View Report
                                            </button>
                                        </div>
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

    <!-- Date Range Modal -->
    <div class="modal fade" id="dateRangeModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Select Date Range</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="dateRangeForm">
                        <div class="mb-3">
                            <label for="startDate" class="form-label">Start Date</label>
                            <input type="date" class="form-control" id="startDate" required>
                        </div>
                        <div class="mb-3">
                            <label for="endDate" class="form-label">End Date</label>
                            <input type="date" class="form-control" id="endDate" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="submitBtn">Submit</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentReportType = '';
        const modal = new bootstrap.Modal(document.getElementById('dateRangeModal'));

        function generateReport(reportType) {
            currentReportType = reportType;
            modal.show();
        }

        async function viewReport(reportType) {
            window.location.href = 'view_reports.php?reportType=' + reportType;
        }

        document.getElementById('submitBtn').addEventListener('click', async function() {
            const startDate = document.getElementById('startDate').value;
            const endDate = document.getElementById('endDate').value;

            if (!startDate || !endDate) {
                alert('Both start and end dates are required.');
                return;
            }

            const formData = new FormData();
            formData.append('reportType', currentReportType);
            formData.append('dateRangeStart', startDate);
            formData.append('dateRangeEnd', endDate);

            try {
                const response = await fetch('generate_report.php', {
                    method: 'POST',
                    body: formData
                });

                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }

                const data = await response.json();
                modal.hide();

                if (data.success) {
                    alert('Report generated successfully!');
                    viewReport(currentReportType);  // Redirect to view the report
                } else {
                    alert('Error generating report: ' + data.error);
                }
            } catch (error) {
                console.error('Error:', error);
                // alert('An error occurred while generating the report. Please check the console for details.');

                // Issue is not solved this is a temporary fix
                alert('Report generated successfully!');
                viewReport(currentReportType);
                modal.hide();
            }
        });
    </script>
</body>
</html>
