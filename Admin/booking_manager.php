<?php
define('IS_ADMIN', true);
$pageTitle = "Booking Manager";

require_once 'includes/config.php'; // OR 'includes/db_connect.php'
include 'includes/header.php';
include 'includes/admin_sidebar.php';
?>
<div class="admin-content">
  <h2 class="mb-3">Booking Manager</h2>require_once __DIR__ . '/../includes/config.php'; // Change db_connect to config

  <?php if (isset($conn)): ?>
    <?php
      $sql = "SELECT id, booking_code, train_code, passenger_name, seats, total_amount, status, created_at FROM bookings ORDER BY created_at DESC LIMIT 200";
      $res = $conn->query($sql);
      if ($res && $res->num_rows):
    ?>
      <div class="table-responsive">
        <table class="table table-sm table-striped">
          <thead class="table-dark">
            <tr><th>#</th><th>Booking Code</th><th>Train</th><th>Passenger</th><th>Seats</th><th>Amount</th><th>Status</th><th>Date</th></tr>
          </thead>
          <tbody>
            <?php while($row = $res->fetch_assoc()): ?>
              <tr>
                <td><?php echo htmlspecialchars($row['id']); ?></td>
                <td><?php echo htmlspecialchars($row['booking_code']); ?></td>
                <td><?php echo htmlspecialchars($row['train_code']); ?></td>
                <td><?php echo htmlspecialchars($row['passenger_name']); ?></td>
                <td><?php echo htmlspecialchars($row['seats']); ?></td>
                <td><?php echo number_format($row['total_amount'],0); ?></td>
                <td><?php echo htmlspecialchars($row['status']); ?></td>
                <td><?php echo htmlspecialchars($row['created_at']); ?></td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    <?php else: ?>
      <div class="alert alert-info">No bookings found. Example SQL to create bookings table:</div>
      <pre class="small bg-light p-2">CREATE TABLE bookings (
  id INT AUTO_INCREMENT PRIMARY KEY,
  booking_code VARCHAR(60),
  train_code VARCHAR(50),
  passenger_name VARCHAR(150),
  seats VARCHAR(255),
  total_amount DECIMAL(10,2),
  status VARCHAR(50),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);</pre>
    <?php endif; ?>
  <?php else: ?>
    <div class="alert alert-warning">Database connection not available. Check includes/db_connect.php</div>
  <?php endif; ?>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>