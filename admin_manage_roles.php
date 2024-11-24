<?php
include 'head.php'; 

// Check if the logged-in user is admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    header('Location: pages/no_access.php');
    exit();
}

$adminId = 1;  // Default admin ID

// Handle promotion, demotion, and user removal
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];
    $userId = intval($_POST['user_id']);

    if ($action === 'promote') {
        // Promote user to Manager
        $query = "UPDATE User SET Role = 'Manager', role_assigned_by = ?, role_assigned_at = NOW() WHERE UserID = ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param('ii', $adminId, $userId);
        if ($stmt->execute()) {
            // Log the action
            $logQuery = "INSERT INTO activity_log (user_id, action, performed_by) VALUES (?, 'Promoted to Manager', ?)";
            $logStmt = $con->prepare($logQuery);
            $logStmt->bind_param('ii', $userId, $adminId);
            $logStmt->execute();
            echo "<script>alert('User promoted to Manager');</script>";
        } else {
            echo "<script>alert('Error promoting user: " . $stmt->error . "');</script>";
        }
    } elseif ($action === 'demote') {
        // Demote user to Employee
        $query = "UPDATE User SET Role = 'Employee', role_assigned_by = ?, role_assigned_at = NOW() WHERE UserID = ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param('ii', $adminId, $userId);
        if ($stmt->execute()) {
            // Log the action
            $logQuery = "INSERT INTO activity_log (user_id, action, performed_by) VALUES (?, 'Demoted to Employee', ?)";
            $logStmt = $con->prepare($logQuery);
            $logStmt->bind_param('ii', $userId, $adminId);
            $logStmt->execute();
            echo "<script>alert('User demoted to Employee');</script>";
        } else {
            echo "<script>alert('Error demoting user: " . $stmt->error . "');</script>";
        }
    } elseif ($action === 'remove') {
        // Remove user
        $query = "DELETE FROM User WHERE UserID = ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param('i', $userId);
        if ($stmt->execute()) {
            // Log the action
            $logQuery = "INSERT INTO activity_log (user_id, action, performed_by) VALUES (?, 'User Removed', ?)";
            $logStmt = $con->prepare($logQuery);
            $logStmt->bind_param('ii', $userId, $adminId);
            $logStmt->execute();
            echo "<script>alert('User removed successfully');</script>";
        } else {
            echo "<script>alert('Error removing user: " . $stmt->error . "');</script>";
        }
    }
}

// Fetch all users except admin
$usersQuery = "SELECT UserID, Username, Email, Role FROM User WHERE Role != 'Admin'";
$usersResult = mysqli_query($con, $usersQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Admin - Manage Roles</title>
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
                                <i class="mdi mdi-account-cog menu-icon"></i>
                            </span>
                            Manage Users
                        </h3>
                    </div>
                    <div class="container">
                        <div class="card">
                            <div class="card-body">
                                <table class="table table-striped">
                                    <thead class="bg-gradient-primary text-white">
                                        <tr>
                                            <th>Username</th>
                                            <th>Email</th>
                                            <th>Role</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($user = mysqli_fetch_assoc($usersResult)): ?>
                                            <tr>
                                                <td class="align-middle"><?php echo $user['Username']; ?></td>
                                                <td class="align-middle"><?php echo $user['Email']; ?></td>
                                                <td class="align-middle">
                                                    <span class="badge <?php echo $user['Role'] === 'Manager' ? 'bg-success' : 'bg-info'; ?>">
                                                        <?php echo $user['Role']; ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <?php if ($user['Role'] === 'Employee'): ?>
                                                            <form method="POST" action="" class="me-2">
                                                                <input type="hidden" name="user_id" value="<?php echo $user['UserID']; ?>">
                                                                <input type="hidden" name="action" value="promote">
                                                                <button type="submit" class="btn btn-gradient-success btn-sm">Promote to Manager</button>
                                                            </form>
                                                        <?php elseif ($user['Role'] === 'Manager'): ?>
                                                            <form method="POST" action="" class="me-2">
                                                                <input type="hidden" name="user_id" value="<?php echo $user['UserID']; ?>">
                                                                <input type="hidden" name="action" value="demote">
                                                                <button type="submit" class="btn btn-gradient-warning btn-sm">Demote to Employee</button>
                                                            </form>
                                                        <?php endif; ?>
                                                        
                                                        <form method="POST" action="">
                                                            <input type="hidden" name="user_id" value="<?php echo $user['UserID']; ?>">
                                                            <input type="hidden" name="action" value="remove">
                                                            <button type="submit" class="btn btn-gradient-danger btn-sm">Remove</button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                        <!-- Activity Log -->
                        <div class="card mt-4">
                            <div class="card-body">
                                <h4 class="card-title">Activity Log</h4>
                                <table class="table table-striped table-hover">
                                    <thead class="bg-gradient-primary text-white">
                                        <tr>
                                            <th>Username</th> 
                                            <th>Action</th>
                                            <th>Performed By</th>
                                            <th>Timestamp</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $logQuery = "
                                            SELECT activity_log.*, User.Username 
                                            FROM activity_log 
                                            INNER JOIN User ON activity_log.user_id = User.UserID 
                                            ORDER BY timestamp DESC";
                                        $logResult = mysqli_query($con, $logQuery);
                                        while ($log = mysqli_fetch_assoc($logResult)): ?>
                                            <tr>
                                                <td><?php echo $log['Username']; ?></td>
                                                <td><span class="badge bg-primary"><?php echo $log['action']; ?></span></td>
                                                <td>Admin</td>
                                                <td><?php echo date('M d, Y H:i:s', strtotime($log['timestamp'])); ?></td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
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