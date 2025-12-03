<?php
define('IS_ADMIN', true);
$pageTitle = "System Settings";

require_once __DIR__ . '/../includes/db_connect.php';
include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/admin_sidebar.php';
?>
<div class="admin-content">
  <h2 class="mb-3">System Settings</h2>

  <?php if (isset($conn)): ?>
    <?php
      $settings = [];
      $res = $conn->query("SELECT name, value FROM settings");
      if ($res && $res->num_rows) {
        while($s = $res->fetch_assoc()) $settings[$s['name']] = $s['value'];
      }
    ?>
    <form method="post" action="settings_save.php" class="row g-3">
      <div class="col-md-6">
        <label class="form-label">Site Title</label>
        <input name="site_title" class="form-control" value="<?php echo htmlspecialchars($settings['site_title'] ?? 'Bangladesh Railway'); ?>">
      </div>
      <div class="col-md-6">
        <label class="form-label">Contact Email</label>
        <input name="contact_email" class="form-control" value="<?php echo htmlspecialchars($settings['contact_email'] ?? ''); ?>">
      </div>
      <div class="col-12 text-end">
        <button class="btn btn-primary">Save Settings</button>
      </div>
    </form>

    <?php if (empty($res) || $res->num_rows == 0): ?>
      <div class="alert alert-info mt-3">No settings found. Create settings table if needed:
      <pre class="small bg-light p-2">CREATE TABLE settings (name VARCHAR(100) PRIMARY KEY, value TEXT);</pre>
      </div>
    <?php endif; ?>

  <?php else: ?>
    <div class="alert alert-warning">DB connection not available.</div>
  <?php endif; ?>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
