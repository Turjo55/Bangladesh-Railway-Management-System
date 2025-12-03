<?php 
// admin_train_manager.php
// # Admin Section for Managing Trains, Routes, and Fares
$pageTitle = "Train Manager";
include 'includes/config.php';

// # Simple role check (redirect if not Admin/Super Admin)
if (!isset($_SESSION['user_role']) || !in_array($_SESSION['user_role'], ['Admin', 'Super Admin'])) {
    header("Location: admin_login.php");
    exit;
}

// Fetch list of all trains
$trains = [];
$result = $conn->query("SELECT train_id, train_code, name, type, departure_time, arrival_time FROM trains");
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $trains[] = $row;
    }
}

// Reusing Admin Header/Sidebar setup from admin_dashboard.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - <?php echo $pageTitle; ?></title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.1.0/mdb.min.css" rel="stylesheet"/>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .sidebar {
            width: 250px;
            background-color: #34495e; 
            min-height: 100vh;
            padding-top: 20px;
        }
        .content-wrapper {
            margin-left: 250px;
            padding: 20px;
        }
        .sidebar a {
            color: #ecf0f1;
            padding: 15px 20px;
            display: block;
            text-decoration: none;
            transition: background-color 0.3s;
        }
        .sidebar a:hover, .sidebar a.active {
            background-color: #2c3e50;
            border-left: 5px solid #f1c40f; 
            color: #fff;
        }
        /* Highlight current page */
        .sidebar a[href='admin_train_manager.php'] {
            background-color: #2c3e50;
            border-left: 5px solid #f1c40f;
            color: #fff;
        }
    </style>
</head>
<body>
<div class="d-flex">
    <div class="sidebar position-fixed">
        <h4 class="text-center text-white mb-4"><i class="fas fa-train me-2"></i> BR Admin</h4>
        <a href="admin_dashboard.php"><i class="fas fa-tachometer-alt me-2"></i> Dashboard</a>
        <a class="active" href="admin_train_manager.php"><i class="fas fa-route me-2"></i> Train & Route Mgr</a>
        <a href="#"><i class="fas fa-ticket-alt me-2"></i> Booking Manager</a>
        <a href="#"><i class="fas fa-users-cog me-2"></i> User Manager</a>
        <a href="#"><i class="fas fa-chart-line me-2"></i> Reports & Analytics</a>
        <a href="#"><i class="fas fa-cogs me-2"></i> System Settings</a>
        <a href="logout.php" class="text-danger"><i class="fas fa-sign-out-alt me-2"></i> Logout</a>
    </div>

    <div class="content-wrapper w-100 admin-card-overlay" style="background-color: var(--background-color);">
        <h1 class="mb-4 text-primary">Train & Route Management</h1>

        <div class="card mb-4">
            <div class="card-header bg-primary text-white" data-mdb-toggle="collapse" href="#addTrainForm" role="button" aria-expanded="false" aria-controls="addTrainForm" style="cursor: pointer;">
                <h5 class="mb-0"><i class="fas fa-plus-circle me-2"></i> Add New Train</h5>
            </div>
            <div class="collapse" id="addTrainForm">
                <div class="card-body">
                    <form>
                        <div class="row g-3">
                            <div class="col-md-4"><div class="form-outline"><input type="text" id="trainName" class="form-control" /><label class="form-label" for="trainName">Train Name</label></div></div>
                            <div class="col-md-4"><div class="form-outline"><input type="text" id="trainCode" class="form-control" /><label class="form-label" for="trainCode">Train Code</label></div></div>
                            <div class="col-md-4">
                                <select class="form-select">
                                    <option>Intercity</option><option>Mail Express</option>
                                </select>
                            </div>
                        </div>
                        <div class="mt-4 text-center"><button type="submit" class="btn btn-success btn-lg"><i class="fas fa-save me-2"></i> Save Train</button></div>
                    </form>
                </div>
            </div>
        </div>

        <h4 class="text-secondary mb-3">Existing Trains (<?php echo count($trains); ?>)</h4>
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle">
                <thead class="bg-light">
                    <tr>
                        <th>Code / Name</th>
                        <th>Type</th>
                        <th>Departure</th>
                        <th>Arrival</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($trains) > 0): ?>
                        <?php foreach ($trains as $train): ?>
                        <tr>
                            <td>
                                <strong><?php echo htmlspecialchars($train['train_code']); ?></strong><br>
                                <small class="text-muted"><?php echo htmlspecialchars($train['name']); ?></small>
                            </td>
                            <td><?php echo htmlspecialchars($train['type']); ?></td>
                            <td><?php echo date('H:i', strtotime($train['departure_time'])); ?></td>
                            <td><?php echo date('H:i', strtotime($train['arrival_time'])); ?></td>
                            <td>
                                <button class="btn btn-sm btn-info me-2" title="Edit Train"><i class="fas fa-edit"></i></button>
                                <button class="btn btn-sm btn-warning me-2" title="Manage Coaches/Fares"><i class="fas fa-chair"></i></button>
                                <button class="btn btn-sm btn-danger" title="Delete Train"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                         <tr><td colspan="5" class="text-center">No trains found. Add one above.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
    </div>
</div>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.1.0/mdb.min.js"></script>
</body>
</html>