<?php
define('IS_ADMIN', true);
$pageTitle = "User Manager";

require_once 'includes/config.php'; // OR 'includes/db_connect.php'
include 'includes/header.php';
include 'includes/admin_sidebar.php';
?>
<div class="admin-content">
  <h2 class="mb-3">User Manager</h2>

  <?php if (isset($conn)): ?>
    <?php
      $sql = "SELECT id, name, email, phone, created_at FROM users ORDER BY created_at DESC LIMIT 200";
      $res = $conn->query($sql);
      if ($res && $res->num_rows):
    ?>
      <table class="table table-sm table-hover">
        <thead class="table-light"><tr><th>#</th><th>Name</th><th>Email</th><th>Phone</th><th>Joined</th></tr></thead>
        <tbody>
          <?php while($u = $res->fetch_assoc()): ?>
            <tr>
              <td><?php echo $u['id']; ?></td>
              <td><?php echo htmlspecialchars($u['name']); ?></td>
              <td><?php echo htmlspecialchars($u['email']); ?></td>
              <td><?php echo htmlspecialchars($u['phone']); ?></td>
              <td><?php echo $u['created_at']; ?></td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    <?php else: ?>
      <div class="alert alert-info">No users found. Create users table or add sample users.</div>
    <?php endif; ?>
  <?php else: ?>
    <div class="alert alert-warning">DB connection missing. Check includes/db_connect.php</div>
  <?php endif; ?>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>