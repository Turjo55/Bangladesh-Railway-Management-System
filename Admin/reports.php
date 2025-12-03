<?php
define('IS_ADMIN', true);
$pageTitle = "Reports & Analytics";

require_once __DIR__ . '/../includes/db_connect.php';
include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/admin_sidebar.php';
?>
<div class="admin-content">
  <h2 class="mb-3">Reports & Analytics</h2>

  <?php if (isset($conn)): ?>
    <?php
      $totUsers = $conn->query("SELECT COUNT(*) AS c FROM users")->fetch_assoc()['c'] ?? 0;
      $totBookings = $conn->query("SELECT COUNT(*) AS c FROM bookings")->fetch_assoc()['c'] ?? 0;
      $sumRevenue = $conn->query("SELECT SUM(total_amount) AS s FROM bookings")->fetch_assoc()['s'] ?? 0;
    ?>
    <div class="row g-3 mb-4">
      <div class="col-md-4"><div class="card p-3"><h6>Users</h6><p class="display-6 mb-0"><?php echo number_format($totUsers); ?></p></div></div>
      <div class="col-md-4"><div class="card p-3"><h6>Total Bookings</h6><p class="display-6 mb-0"><?php echo number_format($totBookings); ?></p></div></div>
      <div class="col-md-4"><div class="card p-3"><h6>Revenue (BDT)</h6><p class="display-6 mb-0"><?php echo number_format($sumRevenue,0); ?></p></div></div>
    </div>

    <h5 class="mb-3">Latest Bookings</h5>
    <?php
      $res = $conn->query("SELECT booking_code, train_code, passenger_name, total_amount, status, created_at FROM bookings ORDER BY created_at DESC LIMIT 8");
      if ($res && $res->num_rows):
    ?>
      <ul class="list-group">
        <?php while($r = $res->fetch_assoc()): ?>
          <li class="list-group-item d-flex justify-content-between align-items-center">
            <div>
              <strong><?php echo htmlspecialchars($r['booking_code']); ?></strong> — <?php echo htmlspecialchars($r['passenger_name']); ?> <small class="text-muted"> (<?php echo $r['train_code']; ?>)</small>
            </div>
            <div><span class="badge bg-success">BDT <?php echo number_format($r['total_amount'],0); ?></span></div>
          </li>
        <?php endwhile; ?>
      </ul>
    <?php else: ?>
      <div class="alert alert-info">No recent bookings to show.</div>
    <?php endif; ?>

  <?php else: ?>
    <div class="alert alert-warning">Database connection not available.</div>
  <?php endif; ?>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>