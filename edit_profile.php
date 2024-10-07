<?php
include 'auth.php';  
include 'head.php';  

$userID = $_SESSION['user_id'];

// Fetch employee profile information from the database
$query = "SELECT * FROM User WHERE UserID = ?";
$stmt = $con->prepare($query);
$stmt->bind_param("i", $userID);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $phoneNumber = $_POST['phone_number'];
    $address = $_POST['address'];
    $dateOfBirth = $_POST['date_of_birth'];
    $jobTitle = $_POST['job_title'];
    $department = $_POST['department'];
    $gender = $_POST['gender'];

    // Update profile information in the database
    $updateQuery = "UPDATE User SET FirstName = ?, LastName = ?, PhoneNumber = ?, Address = ?, DateOfBirth = ?, JobTitle = ?, Department = ?, Gender = ? WHERE UserID = ?";
    $stmt = $con->prepare($updateQuery);
    $stmt->bind_param("ssssssssi", $firstName, $lastName, $phoneNumber, $address, $dateOfBirth, $jobTitle, $department, $gender, $userID);
    
    if ($stmt->execute()) {
        // Profile updated successfully, redirect back to profile page
        header("Location: profile.php");
        exit;
    } else {
        $error = "Failed to update profile.";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <title>Edit Profile</title>
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
                <i class="mdi mdi-pencil"></i>
              </span>
              Edit Profile Information
            </h3>
          </div>

          <div class="row justify-content-center">
            <div class="col-md-8">
              <div class="card shadow-lg">
                <div class="card-header bg-gradient-primary text-white">
                  <h4 class="text-center">Update Your Information</h4>
                </div>
                <div class="card-body">
                  <?php if (!empty($error)): ?>
                    <div class="alert alert-danger">
                      <?php echo htmlspecialchars($error); ?>
                    </div>
                  <?php endif; ?>

                  <form method="POST">
                    <div class="mb-3">
                      <label for="first_name" class="form-label">First Name</label>
                      <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo htmlspecialchars($user['FirstName']); ?>" required>
                    </div>
                    <div class="mb-3">
                      <label for="last_name" class="form-label">Last Name</label>
                      <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo htmlspecialchars($user['LastName']); ?>" required>
                    </div>
                    <div class="mb-3">
                      <label for="phone_number" class="form-label">Phone Number</label>
                      <input type="text" class="form-control" id="phone_number" name="phone_number" value="<?php echo htmlspecialchars($user['PhoneNumber']); ?>">
                    </div>
                    <div class="mb-3">
                      <label for="address" class="form-label">Address</label>
                      <textarea class="form-control" id="address" name="address" rows="3"><?php echo htmlspecialchars($user['Address']); ?></textarea>
                    </div>
                    <div class="mb-3">
                      <label for="date_of_birth" class="form-label">Date of Birth</label>
                      <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" value="<?php echo htmlspecialchars($user['DateOfBirth']); ?>">
                    </div>
                    <div class="mb-3">
                      <label for="job_title" class="form-label">Job Title</label>
                      <input type="text" class="form-control" id="job_title" name="job_title" value="<?php echo htmlspecialchars($user['JobTitle']); ?>">
                    </div>
                    <div class="mb-3">
                      <label for="department" class="form-label">Department</label>
                      <input type="text" class="form-control" id="department" name="department" value="<?php echo htmlspecialchars($user['Department']); ?>">
                    </div>
                    <div class="mb-3">
                      <label for="gender" class="form-label">Gender</label>
                      <select class="form-select" id="gender" name="gender" required>
                        <option value="Male" <?php echo ($user['Gender'] == 'Male') ? 'selected' : ''; ?>>Male</option>
                        <option value="Female" <?php echo ($user['Gender'] == 'Female') ? 'selected' : ''; ?>>Female</option>
                        <option value="Other" <?php echo ($user['Gender'] == 'Other') ? 'selected' : ''; ?>>Other</option>
                      </select>
                    </div>
                    <div class="text-center">
                      <button type="submit" class="btn btn-gradient-primary">Save Changes</button>
                      <a href="profile.php" class="btn btn-secondary">Cancel</a>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
  <?php include 'footer.php'; ?>
</body>
</html>
