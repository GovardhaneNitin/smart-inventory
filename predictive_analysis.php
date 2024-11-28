<?php include 'auth.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <title>Predictive Analysis</title>
  <?php include 'head.php'; ?>
  <script>
    function showAlert(message) {
      alert(message);
    }
  </script>
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
                <i class="mdi mdi-chart-line"></i>
              </span>
              Predictive Analysis
            </h3>
          </div>
          <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <?php
                  $output = [];
                  $return_var = 0;

                  // Commenting out the exec method to prevent the script from running automatically
                  // exec('python data_analysis.py 2>&1', $output, $return_var); // Capture errors as well

                  // Check if predictions file exists instead of running the script automatically
                  $predictions_file = 'predictions/predicted_restock.json';
                  if (file_exists($predictions_file)) {
                    echo "<h4>Predictive Analysis Results</h4>";

                    // Display graphs and charts
                    echo "<div class='text-center'>";
                    if (file_exists('plots/sales_predictions.html')) {
                      echo "<iframe src='plots/sales_predictions.html' width='100%' height='500' class='embed-responsive-item'></iframe><br/><br/>";
                    }
                    if (file_exists('plots/cumulative_sales.html')) {
                      echo "<iframe src='plots/cumulative_sales.html' width='100%' height='500' class='embed-responsive-item'></iframe><br/><br/>";
                    }
                    if (file_exists('plots/sales_distribution.html')) {
                      echo "<h5>Sales Distribution by Product</h5>";
                      echo "<iframe src='plots/sales_distribution.html' width='100%' height='500' class='embed-responsive-item'></iframe><br/><br/>";
                    }
                    echo "</div>";

                    // Load predicted restock data from JSON
                    $predictions_json = file_get_contents($predictions_file);
                    $predictions = json_decode($predictions_json, true);

                    if (!empty($predictions)) {
                      echo "<h4 class='mb-4 text-primary'>Predicted Restock Requirements</h4>";
                      echo "<div class='table-responsive'>";
                      echo "<table class='table table-striped table-bordered'>";
                      echo "<thead class='bg-gradient-primary text-white'>
                          <tr>
                          <th class='text-center' style='font-size: 16px; padding: 15px;'>Item Name</th>
                          <th class='text-center' style='font-size: 16px; padding: 15px;'>Predicted Restock Date</th>
                          <th class='text-center' style='font-size: 16px; padding: 15px;'>Predicted Quantity Needed</th>
                          </tr>
                        </thead>
                        <tbody>";

                      foreach ($predictions as $prediction) {
                      echo "<tr class='align-middle'>
                          <td class='text-center' style='padding: 12px;'>" . htmlspecialchars($prediction['ItemName']) . "</td>
                          <td class='text-center' style='padding: 12px;'>" . htmlspecialchars($prediction['PredictedRestockDate']) . "</td>
                          <td class='text-center' style='padding: 12px;'><span class='badge bg-info'>" . htmlspecialchars($prediction['PredictedQuantity']) . "</span></td>
                          </tr>";
                      }

                      echo "</tbody></table></div>";

                      // Form to submit prediction data to save in the database
                      echo "<form method='post' action='save_prediction.php' onsubmit='return confirm(\"Are you sure you want to save these predictions?\");'>";
                      echo "<input type='hidden' name='data' value='" . htmlspecialchars($predictions_json) . "'>";
                      echo "<button type='submit' class='btn btn-gradient-primary mt-4'>Save Predictions</button>";
                      echo "</form>";
                    } else {
                      echo "<p class='text-warning'>No predictions available to display.</p>";
                    }
                    } else {
                    echo "<p class='text-warning'>No predictions file found. Please run the analysis script first.</p>";
                  }
                  ?>
                </div>
              </div>
            </div>
          </div>
        </div>
        <?php include 'footer.php'; ?>
      </div>
    </div>
  </div>
</body>
</html>
