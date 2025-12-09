<?php 
// Admin/reports.php
// # Displays key operational reports and analytics

define('IS_ADMIN', true);
$pageTitle = "Reports & Analytics";

// IMPORTANT: Use the correct path for files located one level deeper (in the Admin folder)
require_once __DIR__ . '/../includes/config.php'; 
include __DIR__ . '/../includes/header.php'; 
include __DIR__ . '/../includes/admin_sidebar.php'; 
?>

<div class="admin-content container-fluid pt-4" style="margin-left: 260px;">
    <h2><?php echo $pageTitle; ?></h2>
    <p class="text-muted">View sales, occupancy, and financial reports.</p>
    
    <div class="row mt-4">
        <div class="col-md-6 mb-4">
            <div class="card p-3 shadow-sm border-start border-primary border-5">
                <h4>Total Revenue This Month</h4>
                <p class="fs-3 fw-bold text-primary">BDT 5,450,200</p>
                <small class="text-success"><i class="fas fa-arrow-up"></i> 12.5% vs last month</small>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="card p-3 shadow-sm border-start border-info border-5">
                <h4>Total Bookings</h4>
                <p class="fs-3 fw-bold text-info">9,876</p>
                <small class="text-muted">For the current running quarter.</small>
            </div>
        </div>
    </div>
    
    <hr>
    
    <div class="alert alert-warning">
        <i class="fas fa-chart-bar me-2"></i> Placeholder: Charts and dynamic data visualization modules (e.g., occupancy rate by train) would be implemented here using Chart.js or D3.js.
    </div>
    
</div>

<?php 
include __DIR__ . '/../includes/footer.php'; 
?>