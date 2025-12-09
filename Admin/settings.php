<?php 
// Admin/settings.php
// # Configuration settings for the Railway System

define('IS_ADMIN', true);
$pageTitle = "System Settings";

// IMPORTANT: Use the correct path for files located one level deeper (in the Admin folder)
require_once __DIR__ . '/../includes/config.php'; 
include __DIR__ . '/../includes/header.php'; 
include __DIR__ . '/../includes/admin_sidebar.php'; 
?>

<div class="admin-content container-fluid pt-4" style="margin-left: 260px;">
    <h2><?php echo $pageTitle; ?></h2>
    <p class="text-muted">Configure base fares, classes, and system parameters.</p>
    
    <div class="card p-4 shadow-sm mb-4">
        <h4 class="card-title text-secondary">Fare Multiplier Settings</h4>
        <p class="text-muted small">Manage the base fare multiplier for each class type.</p>
        <form action="process_settings.php" method="POST">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="ac_berth">AC Berth Multiplier</label>
                    <input type="number" step="0.01" class="form-control" id="ac_berth" name="ac_berth_multiplier" value="2.00" required>
                </div>
                 <div class="col-md-4 mb-3">
                    <label for="snigdha">Snigdha Multiplier</label>
                    <input type="number" step="0.01" class="form-control" id="snigdha" name="snigdha_multiplier" value="1.50" required>
                </div>
                 <div class="col-md-4 mb-3">
                    <label for="shovan">Shovan Chair Multiplier</label>
                    <input type="number" step="0.01" class="form-control" id="shovan" name="shovan_multiplier" value="1.00" required>
                </div>
            </div>
            <button type="submit" class="btn btn-primary mt-3"><i class="fas fa-save me-2"></i> Save Fare Settings</button>
        </form>
    </div>
    
    <div class="card p-4 shadow-sm">
        <h4 class="card-title text-secondary">System Announcements</h4>
        <p class="text-muted small">Post system-wide messages (e.g., service alerts).</p>
        <textarea class="form-control" rows="3" placeholder="Enter maintenance alerts or system messages..."></textarea>
        <button class="btn btn-warning mt-3"><i class="fas fa-bullhorn me-2"></i> Post Announcement</button>
    </div>
    
</div>

<?php 
include __DIR__ . '/../includes/footer.php'; 
?>