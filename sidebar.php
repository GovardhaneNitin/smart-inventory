<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
  <title>Smart Inventory</title>
  <?php include 'head.php'; ?>

  <style>
    #sidebar-logout {
      display: none;
    }

    @media (max-width: 991px) {
      #sidebar-logout {
        display: block;
      }
    }
  </style>
</head>
<body>
<nav class="sidebar sidebar-offcanvas" id="sidebar">
  <ul class="nav">
    <li class="nav-item">
      <a class="nav-link" href="index.php">
        <span class="menu-title">Dashboard</span>
        <i class="mdi mdi-home menu-icon"></i>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="admin_manage_roles.php">
        <span class="menu-title">Roles</span>
        <i class="mdi mdi-account-multiple menu-icon"></i>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="inventory.php">
        <span class="menu-title">Inventory</span>
        <i class="mdi mdi-view-list menu-icon"></i>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="supplier.php">
        <span class="menu-title">Suppliers</span>
        <i class="mdi mdi-truck menu-icon"></i>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="pages/error-404.php">
        <span class="menu-title">Sales Management</span>
        <i class="mdi mdi-cash-multiple menu-icon"></i>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="pages/error-404.php">
        <span class="menu-title">Product</span>
        <i class="mdi mdi-cube-outline menu-icon"></i>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="pages/error-404.php">
        <span class="menu-title">Reports</span>
        <i class="mdi mdi-chart-bar menu-icon"></i>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" data-bs-toggle="collapse" href="#auth" aria-expanded="false" aria-controls="auth">
        <span class="menu-title">Pages</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse" id="auth">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item">
            <a class="nav-link" href="login.php"> Login </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="register.php"> Register </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="pages/error-404.php"> 404 </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="pages/error-500.php"> 500 </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="pages/no_access.php"> 403 </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="profile.php"> Profile </a>
          </li>
        </ul>
      </div>
    </li>

    <!-- Account only shown on small screens -->
    <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
      <li class="nav-item d-lg-none">
        <a class="nav-link" data-bs-toggle="collapse" href="#profileMenu" aria-expanded="false" aria-controls="profileMenu">
          <div class="d-flex align-items-center">
            <i class="mdi mdi-account-circle menu-icon me-2"></i>
            <span class="menu-title">Account</span>
          </div>
        </a>
        <div class="collapse" id="profileMenu">
          <ul class="nav flex-column sub-menu">
            <li class="nav-item">
              <a class="nav-link d-flex align-items-center" href="profile.php">
                <i class="mdi mdi-account-circle me-2"></i>
                <span>Profile</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link d-flex align-items-center" href="setting.php">
                <i class="mdi mdi-cog me-2"></i>
                <span>Settings</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link d-flex align-items-center" href="logout.php">
                <i class="mdi mdi-logout me-2"></i>
                <span>Logout</span>
              </a>
            </li>
          </ul>
        </div>
      </li>
    <?php endif; ?>
  </ul>
</nav>
</body>
</html>
