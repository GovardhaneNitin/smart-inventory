<?php
include 'auth.php';
include 'head.php';

$userID = $_SESSION['user_id'];
$error = '';  // To store error messages

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $currentPassword = $_POST['currentPassword'];
    $newPassword = $_POST['newPassword'];
    $confirmPassword = $_POST['confirmPassword'];

    // Fetch the current password from the database
    $stmt = $con->prepare("SELECT Password FROM User WHERE UserID = ?");
    $stmt->bind_param("i", $userID);
    $stmt->execute();
    $stmt->bind_result($passwordHash);
    $stmt->fetch();
    $stmt->close();

    // Verify current password
    if (!password_verify($currentPassword, $passwordHash)) {
        $error = 'Current password is incorrect.';
    } else if ($newPassword != $confirmPassword) {
        $error = 'New password does not match the confirmation password.';
    } else if (strlen($newPassword) < 8) {  // Ensure strong passwords
        $error = 'Password must be at least 8 characters long.';
    } else {
        // Update the password in the database
        $newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);
        $updateStmt = $con->prepare("UPDATE User SET Password = ? WHERE UserID = ?");
        $updateStmt->bind_param("si", $newPasswordHash, $userID);
        if ($updateStmt->execute()) {
            $error = 'Password updated successfully.';
        } else {
            $error = 'Failed to update password.';
        }
        $updateStmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Change Password</title>
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
                                <i class="mdi mdi-key-change"></i>
                            </span>
                            Change Password
                        </h3>
                    </div>

                    <div class="row justify-content-center">
                        <div class="col-md-6 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <form action="settings.php" method="POST" class="forms-sample">
                                        <div class="form-group">
                                            <label for="currentPassword">Current Password</label>
                                            <input type="password" class="form-control" id="currentPassword" name="currentPassword" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="newPassword">New Password</label>
                                            <input type="password" class="form-control" id="newPassword" name="newPassword" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="confirmPassword">Confirm New Password</label>
                                            <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" required>
                                        </div>
                                        <button type="submit" class="btn btn-gradient-success mr-2">Submit</button>
                                        <button type="reset" class="btn btn-danger">Cancel</button>
                                    </form>
                                    <?php if (!empty($error)): ?>
                                        <div class="alert alert-danger mt-3"><?php echo $error; ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php include 'footer.php'; ?>
    </div>
</body>
</html>
