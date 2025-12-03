
<?php
// # Admin Sidebar - used by all admin pages
?>
<nav class="admin-sidebar bg-dark text-white p-3" style="width:260px;min-height:100vh;position:fixed;top:0;left:0;">
  <div class="mb-4 text-center">
    <img src="../assets/img/logo_bd_railway.png" alt="Logo" style="height:46px;">
    <div class="mt-2 fw-bold">Admin Dashboard</div>
  </div>

  <ul class="nav flex-column">
    <li class="nav-item mb-2">
      <a class="nav-link text-white d-flex align-items-center" href="../Admin/train_route_mgr.php">
        <i class="fas fa-train me-2"></i> Train &amp; Route Mgr
      </a>
    </li>
    <li class="nav-item mb-2">
      <a class="nav-link text-white d-flex align-items-center" href="../Admin/booking_manager.php">
        <i class="fas fa-ticket-alt me-2"></i> Booking Manager
      </a>
    </li>
    <li class="nav-item mb-2">
      <a class="nav-link text-white d-flex align-items-center" href="../Admin/user_manager.php">
        <i class="fas fa-users me-2"></i> User Manager
      </a>
    </li>
    <li class="nav-item mb-2">
      <a class="nav-link text-white d-flex align-items-center" href="../Admin/reports.php">
        <i class="fas fa-chart-line me-2"></i> Reports &amp; Analytics
      </a>
    </li>
    <li class="nav-item mt-3">
      <a class="nav-link text-white d-flex align-items-center" href="../Admin/settings.php">
        <i class="fas fa-cogs me-2"></i> System Settings
      </a>
    </li>
  </ul>
</nav>

<!-- spacer for page content -->
<div style="width:260px;flex-shrink:0;"></div>