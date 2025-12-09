<?php 
// Admin/user_manager.php
// # CRUD Interface for managing User accounts (Passengers and Admins)

define('IS_ADMIN', true);
$pageTitle = "User Manager";

// Use the correct path for files located one level deeper (in the Admin folder)
require_once __DIR__ . '/../includes/config.php'; 
include __DIR__ . '/../includes/header.php'; 
include __DIR__ . '/../includes/admin_sidebar.php'; 
?>

<div class="admin-content container-fluid pt-4" style="margin-left: 260px;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><?php echo $pageTitle; ?></h2>
        <!-- ADD BUTTON -->
        <a href="user_add.php" class="btn btn-success shadow-sm">
            <i class="fas fa-plus me-2"></i> Add New User
        </a>
    </div>

    <?php if (isset($conn)): ?>
        <?php
        // Fetch all users
        $sql = "SELECT user_id, name, email, phone, role, created_at FROM users ORDER BY created_at DESC LIMIT 200";
        $res = $conn->query($sql);
        
        if ($res && $res->num_rows):
        ?>
        <div class="table-responsive bg-white p-3 rounded shadow-sm">
            <table class="table table-hover table-striped align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Role</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $res->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['user_id']); ?></td>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td><?php echo htmlspecialchars($row['phone']); ?></td>
                        <td><span class="badge bg-<?php echo ($row['role'] == 'Admin' ? 'danger' : ($row['role'] == 'Super Admin' ? 'warning' : 'info')); ?>"><?php echo htmlspecialchars($row['role']); ?></span></td>
                        <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                        <td>
                            <!-- EDIT BUTTON -->
                            <a href='user_edit.php?id=<?php echo $row['user_id']; ?>' class='btn btn-sm btn-primary me-2'><i class='fas fa-edit'></i> Edit</a>
                            <!-- DELETE BUTTON -->
                            <a href='user_delete.php?id=<?php echo $row['user_id']; ?>' class='btn btn-sm btn-danger' onclick='return confirm("Confirm deletion of user <?php echo $row['name']; ?>?")'><i class='fas fa-trash'></i> Delete</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i> No users found in the database.
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