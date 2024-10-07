<?php include 'head.php'; ?>

<?php
if (isset($_POST['login'])) {
    // Get the form data and inputs
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = mysqli_real_escape_string($con, $_POST['password']);

    // Query to check if the user exists in the database
    $query = "SELECT * FROM User WHERE Email='$email' LIMIT 1";
    $result = mysqli_query($con, $query);

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);

        // Verify the password
        if (password_verify($password, $user['Password'])) {
            session_start();
            session_regenerate_id(true);

            // Store user info in the session, including the user ID
            $_SESSION['user_id'] = $user['UserID'];    // Store UserID in session
            $_SESSION['username'] = $user['Username'];
            $_SESSION['email'] = $user['Email'];
            $_SESSION['role'] = $user['Role'];
            $_SESSION['loggedin'] = true;

            // Redirect to the dashboard
            header("Location: index.php");
            exit();
        } else {
            // Incorrect password
            $login_error = "Invalid email or password.";
        }
    } else {
        // User not found
        $login_error = "User not found.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Smart Inventory Login</title>
</head>
<body>
<div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
        <div class="content-wrapper d-flex align-items-center auth">
            <div class="row flex-grow">
                <div class="col-lg-4 mx-auto">
                    <div class="auth-form-light text-left p-5">
                        <h4 class="font-weight-bold">Login</h4>
                        <h6 class="font-weight-light">Sign in to continue..</h6>

                        <form class="pt-3" method="POST" action="">
                            <div class="form-group">
                                <input
                                    type="email"
                                    class="form-control form-control-lg"
                                    id="email"
                                    name="email"
                                    placeholder="Email"
                                    required
                                />
                            </div>
                            <div class="form-group">
                                <input
                                    type="password"
                                    class="form-control form-control-lg"
                                    id="password"
                                    name="password"
                                    placeholder="Password"
                                    required
                                />
                            </div>
                            <div class="mt-3 d-grid gap-2">
                                <button type="submit" name="login" class="btn btn-block btn-gradient-primary btn-lg font-weight-medium auth-form-btn">
                                    SIGN IN
                                </button>
                            </div>
                            <div class="text-center mt-4 font-weight-light">
                                Don't have an account?
                                <a href="register.php" class="text-primary">Create</a>
                            </div>
                        </form>

                        <!-- Display error message if login fails -->
                        <?php
                        if (isset($login_error)) {
                            echo "<p class='text-danger text-center mt-3' style='font-size: 1em;'>$login_error</p>";
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
