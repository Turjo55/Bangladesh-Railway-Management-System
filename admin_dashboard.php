<?php 
// admin_dashboard.php
// # Main Admin Dashboard with Analytics and Navigation
$pageTitle = "Admin Dashboard";
include 'includes/config.php';

// # Simple role check (redirect if not Admin/Super Admin)
if (!isset($_SESSION['user_role']) || !in_array($_SESSION['user_role'], ['Admin', 'Super Admin'])) {
    header("Location: admin_login.php");
    exit;
}

// Custom Admin Header (No main navigation bar)
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
            background-color: #34495e; /* Primary Dark Color */
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
            border-left: 5px solid #f1c40f; /* Accent color */
            color: #fff;
        }
    </style>
</head>
<body>
<div class="d-flex admin-dashboard-bg">
    <div class="sidebar position-fixed">
        <h4 class="text-center text-white mb-4"><i class="fas fa-train me-2"></i> BR Admin</h4>
        <a class="active" href="admin_dashboard.php"><i class="fas fa-tachometer-alt me-2"></i> Dashboard</a>
        <a href="admin_train_manager.php"><i class="fas fa-route me-2"></i> Train & Route Mgr</a>
        <a href="booking_manager.php"><i class="fas fa-ticket-alt me-2"></i> Booking Manager</a>
        <a href="user_manager.php"><i class="fas fa-users-cog me-2"></i> User Manager</a>
        <a href="reports.php"><i class="fas fa-chart-line me-2"></i> Reports & Analytics</a>
        <a href="settings.php"><i class="fas fa-cogs me-2"></i> System Settings</a>
        <a href="logout.php" class="text-danger"><i class="fas fa-sign-out-alt me-2"></i> Logout</a>
    </div>

    <div class="content-wrapper w-100">
        <div class="admin-card-overlay">
            <h1 class="mb-4 text-primary">System Dashboard</h1>
            
            <div class="row g-4 mb-5">
                <div class="col-md-3">
                    <div class="card p-3 shadow-sm bg-success text-white">
                        <div class="card-body">
                            <h5 class="card-title text-white">Daily Sales (BDT)</h5>
                            <h1 class="display-4 fw-bold">45,800</h1>
                            <p class="mb-0">+12% vs Yesterday</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card p-3 shadow-sm bg-info text-white">
                        <div class="card-body">
                            <h5 class="card-title text-white">Total Tickets Sold</h5>
                            <h1 class="display-4 fw-bold">954</h1>
                            <p class="mb-0">Intercity: 600 | Express: 354</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card p-3 shadow-sm bg-warning text-dark">
                        <div class="card-body">
                            <h5 class="card-title text-dark">Average Occupancy</h5>
                            <h1 class="display-4 fw-bold">78%</h1>
                            <p class="mb-0">Target: 85%</p>
                        </div>
                    </div>
                </div>
                 <div class="col-md-3">
                    <div class="card p-3 shadow-sm bg-secondary text-white">
                        <div class="card-body">
                            <h5 class="card-title text-white">Registered Users</h5>
                            <h1 class="display-4 fw-bold">12,450</h1>
                            <p class="mb-0">Active: 8,900</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row g-4">
                <div class="col-lg-6">
                    <h4 class="text-secondary mb-3">Recent Bookings</h4>
                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            BRTICKET12345 (Rahim A.) - Suborno Express 
                            <span class="badge rounded-pill bg-success">Confirmed</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            BRTICKET99887 (Karim H.) - Turna Express
                            <span class="badge rounded-pill bg-warning">Pending Payment</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            BRTICKET55443 (Sonia B.) - Refund Approved
                            <span class="badge rounded-pill bg-info">Refunded</span>
                        </li>
                    </ul>
                </div>
                <div class="col-lg-6">
                    <h4 class="text-secondary mb-3">Quick Actions</h4>
                    <div class="d-grid gap-2">
                        <a href="admin_train_manager.php" class="btn btn-primary btn-lg"><i class="fas fa-plus-circle me-2"></i> Add New Train/Route</a>
                        <button class="btn btn-warning btn-lg"><i class="fas fa-exclamation-triangle me-2"></i> Post Service Alert</button>
                        <button class="btn btn-info btn-lg"><i class="fas fa-headset me-2"></i> View Support Tickets</button>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.1.0/mdb.min.js"></script>
</body>
</html>