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

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['profile_image'])) {
  $profileImage = $_FILES['profile_image'];

  // Check for upload errors
  if ($profileImage['error'] === 0) {
    // Set a unique name for the file and move it to the uploads folder
    $fileName = $userID . '_' . time() . '_' . basename($profileImage['name']);
    $fileDestination = 'assets/images/Profile-pics/' . $fileName;

    if (move_uploaded_file($profileImage['tmp_name'], $fileDestination)) {
      // Update the ProfileImage in the database
      $updateQuery = "UPDATE User SET ProfileImage = ? WHERE UserID = ?";
      $stmt = $con->prepare($updateQuery);
      $stmt->bind_param("si", $fileName, $userID);
      $stmt->execute();
      $stmt->close();

      // Refresh the page to show the new image
      header("Location: profile.php");
      exit;
    } else {
      $error = "Failed to upload profile image.";
    }
  } else {
    $error = "Error uploading the file.";
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <title>Profile</title>
  <style>
    /* Profile Picture Hover Effect */
    .profile-pic-container {
      position: relative;
      width: 150px;
      margin: auto;
      transition: transform 0.3s ease-in-out;
    }
    .profile-pic-container:hover {
      transform: scale(1.1);
    }
    .profile-pic-container img {
      width: 150px;
      height: 150px;
      object-fit: cover;
      border-radius: 50%;
      border: 3px solid #007bff;
    }
    .profile-pic-container:hover .edit-overlay {
      display: block;
      opacity: 1;
    }
    .edit-overlay {
      display: none;
      opacity: 0;
      transition: opacity 0.3s ease;
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.6);
      border-radius: 50%;
      color: white;
      font-size: 16px;
      text-align: center;
      padding-top: 60px;
      cursor: pointer;
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
                <i class="mdi mdi-view-list menu-icon"></i>
              </span>
              Profile Information
            </h3>
          </div>

            <div class="row justify-content-center">
            <div class="col-md-8">
              <div class="card shadow-lg">
              <div class="card-header p-3 bg-gradient-primary text-white">
                <h3 class="text-center text-uppercase font-weight-bold mt-2">
                <?php echo !empty($user['FirstName']) ? htmlspecialchars($user['FirstName']) : '<span class="text-secondary">Add your First Name</span>'; ?>
                <?php echo !empty($user['LastName']) ? htmlspecialchars($user['LastName']) : '<span class="text-secondary">Add your Last Name</span>'; ?>
                </h3>
              </div>
              <div class="card-body">
                <div class="text-center mb-4">
                <div class="profile-pic-container">
                  <?php if (!empty($user['ProfileImage'])): ?>
                  <img src="assets/images/Profile-pics/<?php echo htmlspecialchars($user['ProfileImage']); ?>" class="rounded-circle" alt="Profile Image">
                  <?php else: ?>
                  <img src="assets/images/Profile-pics/default-profile.png" class="rounded-circle" alt="Default Profile Image">
                  <?php endif; ?>
                  <div class="edit-overlay" onclick="document.getElementById('profilePicForm').style.display='block';">
                  <?php echo !empty($user['ProfileImage']) ? 'Edit Profile Picture' : 'Add Profile Picture'; ?>
                  </div>
                </div>
                </div>

                <!-- Profile Picture Upload Form -->
                <form id="profilePicForm" method="POST" enctype="multipart/form-data" style="display:none;">
                <div class="form-group">
                  <label for="profile_image">Choose Profile Picture</label>
                  <input type="file" class="form-control" name="profile_image" required>
                </div>
                <div class="text-center mt-3">
                  <button type="submit" class="btn btn-primary">Upload</button>
                  <button type="button" class="btn btn-secondary" onclick="document.getElementById('profilePicForm').style.display='none';">Cancel</button>
                </div>
                </form>

                <!-- Error message if upload fails -->
                <?php if (!empty($error)): ?>
                <div class="alert alert-danger mt-3"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>

                <div class="row mt-4">
                <!-- Username Card -->
                <div class="col-md-6 col-12 mb-3">
                  <div class="card p-3 shadow-sm">
                  <div class="d-flex align-items-center">
                    <i class="mdi mdi-account me-3 text-primary"></i>
                    <div>
                    <h5 class="mb-0">Username</h5>
                    <p class="mb-0 text-secondary">
                      <?php echo !empty($user['Username']) ? htmlspecialchars($user['Username']) : '<span class="text-secondary">Add your Username</span>'; ?>
                    </p>
                    </div>
                  </div>
                  </div>
                </div>

                <!-- Email Card -->
                <div class="col-md-6 col-12 mb-3">
                  <div class="card p-3 shadow-sm">
                  <div class="d-flex align-items-center">
                    <i class="mdi mdi-email me-3 text-primary"></i>
                    <div>
                    <h5 class="mb-0">Email</h5>
                    <p class="mb-0 text-secondary">
                      <?php echo !empty($user['Email']) ? htmlspecialchars($user['Email']) : '<span class="text-secondary">Add your Email</span>'; ?>
                    </p>
                    </div>
                  </div>
                  </div>
                </div>

                <!-- First Name Card -->
                <div class="col-md-6 col-12 mb-3">
                  <div class="card p-3 shadow-sm">
                  <div class="d-flex align-items-center">
                    <i class="mdi mdi-account-circle me-3 text-primary"></i>
                    <div>
                    <h5 class="mb-0">First Name</h5>
                    <p class="mb-0 text-secondary">
                      <?php echo !empty($user['FirstName']) ? htmlspecialchars($user['FirstName']) : '<span class="text-secondary">Add your First Name</span>'; ?>
                    </p>
                    </div>
                  </div>
                  </div>
                </div>

                <!-- Last Name Card -->
                <div class="col-md-6 col-12 mb-3">
                  <div class="card p-3 shadow-sm">
                  <div class="d-flex align-items-center">
                    <i class="mdi mdi-account-circle-outline me-3 text-primary"></i>
                    <div>
                    <h5 class="mb-0">Last Name</h5>
                    <p class="mb-0 text-secondary">
                      <?php echo !empty($user['LastName']) ? htmlspecialchars($user['LastName']) : '<span class="text-secondary">Add your Last Name</span>'; ?>
                    </p>
                    </div>
                  </div>
                  </div>
                </div>

                <!-- Phone Number Card -->
                <div class="col-md-6 col-12 mb-3">
                  <div class="card p-3 shadow-sm">
                  <div class="d-flex align-items-center">
                    <i class="mdi mdi-phone me-3 text-primary"></i>
                    <div>
                    <h5 class="mb-0">Phone Number</h5>
                    <p class="mb-0 text-secondary">
                      <?php echo !empty($user['PhoneNumber']) ? htmlspecialchars($user['PhoneNumber']) : '<span class="text-secondary">Add your Phone Number</span>'; ?>
                    </p>
                    </div>
                  </div>
                  </div>
                </div>

                <!-- Address Card -->
                <div class="col-md-6 col-12 mb-3">
                  <div class="card p-3 shadow-sm">
                  <div class="d-flex align-items-center">
                    <i class="mdi mdi-map-marker me-3 text-primary"></i>
                    <div>
                    <h5 class="mb-0">Address</h5>
                    <p class="mb-0 text-secondary">
                      <?php echo !empty($user['Address']) ? htmlspecialchars($user['Address']) : '<span class="text-secondary">Add your Address</span>'; ?>
                    </p>
                    </div>
                  </div>
                  </div>
                </div>

                <!-- Gender Card -->
                <div class="col-md-6 col-12 mb-3">
                  <div class="card p-3 shadow-sm">
                  <div class="d-flex align-items-center">
                    <i class="mdi mdi-gender-male-female me-3 text-primary"></i>
                    <div>
                    <h5 class="mb-0">Gender</h5>
                    <p class="mb-0 text-secondary">
                      <?php echo !empty($user['Gender']) ? htmlspecialchars($user['Gender']) : '<span class="text-secondary">Add your Gender</span>'; ?>
                    </p>
                    </div>
                  </div>
                  </div>
                </div>

                <!-- Date of Birth Card -->
                <div class="col-md-6 col-12 mb-3">
                  <div class="card p-3 shadow-sm">
                  <div class="d-flex align-items-center">
                    <i class="mdi mdi-calendar me-3 text-primary"></i>
                    <div>
                    <h5 class="mb-0">Date of Birth</h5>
                    <p class="mb-0 text-secondary">
                      <?php echo !empty($user['DateOfBirth']) ? htmlspecialchars($user['DateOfBirth']) : '<span class="text-secondary">Add your Date of Birth</span>'; ?>
                    </p>
                    </div>
                  </div>
                  </div>
                </div>

                <!-- Job Title Card -->
                <div class="col-md-6 col-12 mb-3">
                  <div class="card p-3 shadow-sm">
                  <div class="d-flex align-items-center">
                    <i class="mdi mdi-briefcase me-3 text-primary"></i>
                    <div>
                    <h5 class="mb-0">Job Title</h5>
                    <p class="mb-0 text-secondary">
                      <?php echo !empty($user['JobTitle']) ? htmlspecialchars($user['JobTitle']) : '<span class="text-secondary">Add your Job Title</span>'; ?>
                    </p>
                    </div>
                  </div>
                  </div>
                </div>

                <!-- Department Card -->
                <div class="col-md-6 col-12 mb-3">
                  <div class="card p-3 shadow-sm">
                  <div class="d-flex align-items-center">
                    <i class="mdi mdi-office-building me-3 text-primary"></i>
                    <div>
                    <h5 class="mb-0">Department</h5>
                    <p class="mb-0 text-secondary">
                      <?php echo !empty($user['Department']) ? htmlspecialchars($user['Department']) : '<span class="text-secondary">Add your Department</span>'; ?>
                    </p>
                    </div>
                  </div>
                  </div>
                </div>

                <!-- Profile Created At Card -->
                <div class="col-md-6 col-12 mb-3">
                  <div class="card p-3 shadow-sm">
                  <div class="d-flex align-items-center">
                    <i class="mdi mdi-calendar-check me-3 text-primary"></i>
                    <div>
                    <h5 class="mb-0">Profile Created At</h5>
                    <p class="mb-0 text-secondary">
                      <?php echo !empty($user['CreatedAt']) ? htmlspecialchars($user['CreatedAt']) : '<span class="text-secondary">Not Available</span>'; ?>
                    </p>
                    </div>
                  </div>
                  </div>
                </div>

                <!-- Profile Last Updated Card -->
                <div class="col-md-6 col-12 mb-3">
                  <div class="card p-3 shadow-sm">
                  <div class="d-flex align-items-center">
                    <i class="mdi mdi-calendar-edit me-3 text-primary"></i>
                    <div>
                    <h5 class="mb0">Profile Last Updated</h5>
                    <p class="mb-0 text-secondary">
                      <?php echo !empty($user['UpdatedAt']) ? htmlspecialchars($user['UpdatedAt']) : '<span class="text-secondary">Not Available</span>'; ?>
                    </p>
                    </div>
                  </div>
                  </div>
                </div>
                </div>

                <div class="text-center mt-4">
                <a href="edit_profile.php" class="btn btn-warning"><i class="mdi mdi-pencil"></i> Edit Profile</a>
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
</body>
</html>
