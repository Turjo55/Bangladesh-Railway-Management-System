<?php
define('IS_ADMIN', true);
$pageTitle = "Train & Route Manager";

require_once __DIR__ . '/../includes/db_connect.php';
include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/admin_sidebar.php';
?>
<div class="admin-content">
  <h2 class="mb-3">Train & Route Manager</h2>

  <?php if (isset($conn)): ?>
    <?php
      $sql = "SELECT id, name, code, start_station, end_station, running_days, status FROM trains ORDER BY id DESC LIMIT 200";
      $res = $conn->query($sql);
      if ($res && $res->num_rows):
    ?>
      <div class="table-responsive">
        <table class="table table-sm table-striped">
          <thead class="table-dark">
            <tr><th>#</th><th>Name</th><th>Code</th><th>Route</th><th>Days</th><th>Status</th></tr>
          </thead>
          <tbody>
            <?php while($r = $res->fetch_assoc()): ?>
              <tr>
                <td><?php echo $r['id']; ?></td>
                <td><?php echo htmlspecialchars($r['name']); ?></td>
                <td><?php echo htmlspecialchars($r['code']); ?></td>
                <td><?php echo htmlspecialchars($r['start_station'] . ' → ' . $r['end_station']); ?></td>
                <td><?php echo htmlspecialchars($r['running_days']); ?></td>
                <td><?php echo htmlspecialchars($r['status']); ?></td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    <?php else: ?>
      <div class="alert alert-info">No trains found. Create trains table or insert sample data.</div>
      <pre class="small bg-light p-2">CREATE TABLE trains (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150),
  code VARCHAR(50),
  start_station VARCHAR(100),
  end_station VARCHAR(100),
  running_days VARCHAR(100),
  status VARCHAR(30) DEFAULT 'Active'
);</pre>
    <?php endif; ?>
  <?php else: ?>
    <div class="alert alert-warning">Database connection missing. Check includes/db_connect.php</div>
  <?php endif; ?>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>