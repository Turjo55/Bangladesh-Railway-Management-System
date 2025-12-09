<?php 
// passenger_dashboard.php
// # User Dashboard: My Bookings, Profile, Refunds
$pageTitle = "My Dashboard";
include 'includes/config.php';
include 'includes/header.php'; 

// # Simple authorization check
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$currentUserId = $_SESSION['user_id']; // For actual fetching
$passengerName = $_SESSION['user_name'] ?? 'Passenger'; 

// Fetch user's bookings
$db = getMongoDB();
$bookingsCursor = $db->bookings->find(
    ['user_id' => $currentUserId],
    ['sort' => ['created_at' => -1]]
);

$bookings = [];
foreach ($bookingsCursor as $b) {
    $bookings[] = [
        'pnr' => $b['pnr'],
        'train' => $b['train_name'],
        'route' => $b['from_station'] . ' to ' . $b['to_station'],
        'date' => $b['journey_date'],
        'status' => $b['status'] ?? 'Upcoming', // 'Upcoming', 'Completed', 'Cancelled'
        'fare' => $b['total_amount']
    ];
}

?>

<h2 class="text-primary mb-4">Welcome Back, <?php echo htmlspecialchars($passengerName); ?></h2>

<div class="row">
    <div class="col-lg-3">
        <div class="list-group shadow-sm" id="dashboard-nav">
            <a href="#bookings" class="list-group-item list-group-item-action active" data-mdb-toggle="list" role="tab"><i class="fas fa-ticket-alt me-2"></i> My Bookings</a>
            <a href="#profile" class="list-group-item list-group-item-action" data-mdb-toggle="list" role="tab"><i class="fas fa-user-circle me-2"></i> Profile Settings</a>
            <a href="#refunds" class="list-group-item list-group-item-action" data-mdb-toggle="list" role="tab"><i class="fas fa-undo-alt me-2"></i> Refund Requests</a>
            <a href="logout.php" class="list-group-item list-group-item-action text-danger"><i class="fas fa-sign-out-alt me-2"></i> Logout</a>
        </div>
    </div>

    <div class="col-lg-9">
        <div class="tab-content">
            
            <div class="tab-pane fade show active" id="bookings" role="tabpanel">
                <div class="card p-4">
                    <h4 class="card-title text-secondary">My Bookings & Tickets</h4>
                    <ul class="nav nav-pills nav-fill mb-3" role="tablist">
                        <li class="nav-item"><a class="nav-link active" data-mdb-toggle="tab" href="#upcoming">Upcoming</a></li>
                        <li class="nav-item"><a class="nav-link" data-mdb-toggle="tab" href="#completed">Completed</a></li>
                        <li class="nav-item"><a class="nav-link" data-mdb-toggle="tab" href="#cancelled">Cancelled</a></li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="upcoming" role="tabpanel">
                            <?php foreach (array_filter($bookings, fn($b) => $b['status'] == 'Upcoming') as $booking): ?>
                                <div class="card mb-3 p-3 bg-light">
                                    <div class="row align-items-center">
                                        <div class="col-md-7">
                                            <h6 class="fw-bold text-primary"><?php echo $booking['train']; ?> (<small><?php echo $booking['route']; ?></small>)</h6>
                                            <p class="mb-0 text-muted"><i class="fas fa-calendar-alt me-2"></i> **<?php echo $booking['date']; ?>** | PNR: <?php echo $booking['pnr']; ?></p>
                                        </div>
                                        <div class="col-md-5 text-md-end">
                                            <a href="ticket_confirmation.php?pnr=<?php echo $booking['pnr']; ?>" class="btn btn-sm btn-info me-2"><i class="fas fa-download me-1"></i> E-Ticket</a>
                                            <button class="btn btn-sm btn-danger"><i class="fas fa-times me-1"></i> Cancel</button>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="tab-pane fade" id="completed" role="tabpanel">
                            <div class="alert alert-success">Showing <?php echo count(array_filter($bookings, fn($b) => $b['status'] == 'Completed')); ?> completed bookings.</div>
                            </div>
                        <div class="tab-pane fade" id="cancelled" role="tabpanel">
                            <div class="alert alert-warning">Showing <?php echo count(array_filter($bookings, fn($b) => $b['status'] == 'Cancelled')); ?> cancelled tickets.</div>
                            </div>
                    </div>
                </div>
            </div>
            
            <div class="tab-pane fade" id="profile" role="tabpanel">
                <div class="card p-4">
                    <h4 class="card-title text-secondary">Update Profile Information</h4>
                    <form>
                        <div class="form-outline mb-4"><input type="text" id="pName" class="form-control" value="<?php echo htmlspecialchars($passengerName); ?>" /><label class="form-label" for="pName">Name</label></div>
                        <div class="form-outline mb-4"><input type="email" id="pEmail" class="form-control" value="rahim@example.com" /><label class="form-label" for="pEmail">Email</label></div>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                        <hr>
                        <h5 class="mt-4">Change Password</h5>
                        <div class="form-outline mb-4"><input type="password" id="oldPass" class="form-control" /><label class="form-label" for="oldPass">Current Password</label></div>
                        <div class="form-outline mb-4"><input type="password" id="newPass" class="form-control" /><label class="form-label" for="newPass">New Password</label></div>
                        <button type="button" class="btn btn-warning">Change Password</button>
                    </form>
                </div>
            </div>
            
            <div class="tab-pane fade" id="refunds" role="tabpanel">
                <div class="card p-4">
                    <h4 class="card-title text-secondary">Submit Refund Request</h4>
                    <p class="text-muted">Refunds are subject to Bangladesh Railway rules and cancellation timeframes.</p>
                    <form>
                        <div class="form-outline mb-4">
                            <select class="form-select" id="refundPnr" required>
                                <option value="" disabled selected>Select PNR Number to Refund</option>
                                <option>BRTICKET12345 (Upcoming)</option>
                            </select>
                            <label class="form-label" for="refundPnr">PNR Number</label>
                        </div>
                        <div class="form-outline mb-4">
                            <textarea class="form-control" id="reason" rows="3"></textarea>
                            <label class="form-label" for="reason">Reason for Refund</label>
                        </div>
                        <button type="submit" class="btn btn-danger"><i class="fas fa-file-invoice-dollar me-2"></i> Submit Refund Request</button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
<?php 
include 'includes/footer.php'; 
?>