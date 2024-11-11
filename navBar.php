<?php
include 'dropdown.php';
include 'head.php';

// Fetch user details if logged in
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
  $userID = $_SESSION['user_id'];
  $stmt = $con->prepare("SELECT ProfileImage, Username FROM User WHERE UserID = ?");
  $stmt->bind_param("i", $userID);
  $stmt->execute();
  $stmt->bind_result($profileImage, $username);
  $stmt->fetch();
  $stmt->close();
}

?>

<style>
  .navbar-profile-pic-container img {
        width: 150px;
        height: 150px;
        object-fit: cover;
        border-radius: 50%;
        border: 3px solid #007bff;
    }
</style>

<nav class="navbar default-layout-navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
  <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start">
    <a class="navbar-brand brand-logo" href="index.php">
      <img src="assets/images/smart-inventory.svg" alt="logo" style="width: 250px; height: auto;" />
    </a>
    <a class="navbar-brand brand-logo-mini" href="index.php">
      <img src="assets/images/smart-inventory.png" alt="mini logo" style="width: 100px; height: auto;" />
    </a>
  </div>
  <div class="navbar-menu-wrapper d-flex align-items-stretch">
    <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
      <span class="mdi mdi-menu"></span>
    </button>

    <!-- Show the search bar on inventory.php, supplier.php, and sales_transaction.php -->
    <?php if (basename($_SERVER['PHP_SELF']) === 'inventory.php' || basename($_SERVER['PHP_SELF']) === 'supplier.php' || basename($_SERVER['PHP_SELF']) === 'sales_transaction.php'): ?>
      <div class="search-field d-none d-md-block">
      <form id="searchForm" class="d-flex align-items-center h-100" method="GET" action="<?php echo basename($_SERVER['PHP_SELF']); ?>">
        <div class="input-group">
        <div class="input-group-prepend bg-transparent">
          <i class="input-group-text border-0 mdi mdi-magnify"></i>
        </div>
        <input type="text" id="search" name="search" class="form-control bg-transparent border-0" placeholder="Search" />
        </div>
      </form>
      </div>
    <?php endif; ?>

    <ul class="navbar-nav navbar-nav-right d-none d-lg-flex">
      <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" id="profileDropdown" href="#" data-toggle="dropdown" aria-expanded="false">
            <div class="navbar-profile-pic-container">
              <?php if (!empty($profileImage)): ?>
                <img src="assets/images/Profile-pics/<?php echo htmlspecialchars($profileImage); ?>" class="rounded-circle" alt="Profile Image" style="width: 30px; height: 30px; object-fit: cover; margin-right: 6px;">
              <?php else: ?>
                <img src="assets/images/Profile-pics/default-profile.png" class="rounded-circle" alt="Default Profile Image" style="width: 30px; height: 30px; object-fit: cover; margin-right: 6px;">
              <?php endif; ?>
            </div>  
            <span class="text-primary"><?php echo htmlspecialchars($username); ?></span>
          </a>
          <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
            <a class="dropdown-item" href="profile.php">
              <i class="mdi mdi-account-circle text-primary"></i>
              &nbsp;Profile
            </a>
            <a class="dropdown-item" href="setting.php">
              <i class="mdi mdi-cog text-primary"></i>
              &nbsp;Settings
            </a>
          </div>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="logout.php">
            <button class="btn btn-danger" style="margin-right: -30px;">Logout</button>
          </a>
        </li>
      <?php endif; ?>
    </ul>

    <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
      <span class="mdi mdi-menu"></span>
    </button>
  </div>
</nav>
