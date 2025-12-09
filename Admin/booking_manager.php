<?php 
// Admin/booking_manager.php
// # CRUD Interface for managing Booking records

define('IS_ADMIN', true);
$pageTitle = "Booking Manager";

// IMPORTANT: Use the correct path for files located one level deeper (in the Admin folder)
require_once __DIR__ . '/../includes/config.php'; 
include __DIR__ . '/../includes/header.php'; 
include __DIR__ . '/../includes/admin_sidebar.php'; 
?>

<!-- The main content area starts here, offset by the fixed sidebar -->
<div class="admin-content container-fluid pt-4" style="margin-left: 260px;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><?php echo $pageTitle; ?></h2>
        <!-- ADD BUTTON -->
        <a href="booking_add.php" class="btn btn-success shadow-sm">
            <i class="fas fa-plus me-2"></i> Add New Booking
        </a>
    </div>

    <?php 
    // Check if the database connection object ($conn) is available
    if (isset($conn)): 
    ?>
    
        <?php
        // Fetch bookings with necessary JOINs to display passenger and train info
        $sql = "SELECT 
            b.booking_id,
            b.pnr_number,
            b.journey_date,
            b.seats_booked,
            b.total_fare,
            b.booking_status,
            u.name AS passenger_name,
            t.train_code AS train_code_ref,
            t.name AS train_name
        FROM bookings b
        JOIN users u ON b.user_id = u.user_id
        JOIN trains t ON b.train_id = t.train_id
        ORDER BY b.booked_at DESC 
        LIMIT 200";

        $res = $conn->query($sql);
        
        if ($res && $res->num_rows):
        ?>
        
        <div class="table-responsive bg-white p-3 rounded shadow-sm">
            <table class="table table-hover table-striped align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>PNR Number</th>
                        <th>Train</th>
                        <th>Passenger</th>
                        <th>Seats</th>
                        <th>Fare (BDT)</th>
                        <th>Status</th>
                        <th>Journey Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $res->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['booking_id']); ?></td>
                        <td class="fw-bold text-primary"><?php echo htmlspecialchars($row['pnr_number']); ?></td>
                        <td><?php echo htmlspecialchars($row['train_code_ref']); ?> (<?php echo htmlspecialchars($row['train_name']); ?>)</td>
                        <td><?php echo htmlspecialchars($row['passenger_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['seats_booked']); ?></td>
                        <td class="text-end">BDT <?php echo number_format($row['total_fare'], 0); ?></td>
                        <td><span class="badge bg-<?php echo ($row['booking_status'] == 'Confirmed' ? 'success' : ($row['booking_status'] == 'Cancelled' ? 'danger' : 'warning')); ?>"><?php echo htmlspecialchars($row['booking_status']); ?></span></td>
                        <td><?php echo htmlspecialchars($row['journey_date']); ?></td>
                        <td>
                            <!-- Action Buttons -->
                            <a href='booking_edit.php?id=<?php echo $row['booking_id']; ?>' class='btn btn-sm btn-primary me-2'><i class='fas fa-edit'></i> Edit</a>
                            <a href='booking_delete.php?id=<?php echo $row['booking_id']; ?>' class='btn btn-sm btn-danger' onclick='return confirm("Confirm deletion of booking <?php echo $row['pnr_number']; ?>?")'><i class='fas fa-trash'></i> Delete</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        
        <?php else: ?>
        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i> No bookings found in the database.
        </div>
        <?php endif; ?>
        
    <?php else: ?>
        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle me-2"></i> Database connection not available. Please check <code>includes/config.php</code>.
        </div>
    <?php endif; ?>
</div>

<?php 
include __DIR__ . '/../includes/footer.php'; 
?>