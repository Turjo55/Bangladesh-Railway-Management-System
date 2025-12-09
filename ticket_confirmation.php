<?php 
// ticket_confirmation.php
// # Final E-Ticket Display and Confirmation
$pageTitle = "Ticket Confirmed";
include 'includes/config.php';
include 'includes/header.php'; 

$pnr = $_GET['pnr'] ?? '';

if (empty($pnr)) {
    die("Invalid Ticket Request.");
}

$db = getMongoDB();
$booking = $db->bookings->findOne(['pnr' => $pnr]);

if (!$booking) {
    die("Ticket not found.");
}

$totalFare = $booking['total_amount'];
$seats = $booking['seats'];
$date = $booking['journey_date'];
$trainName = $booking['train_name'] . ' (' . $booking['train_code'] . ')';
$route = $booking['from_station'] . ' to ' . $booking['to_station'];
?>

<div class="row justify-content-center my-5">
    <div class="col-lg-8">
        <div class="card p-5 shadow-lg border-success">
            <div class="text-center">
                <i class="fas fa-check-circle fa-5x text-success mb-3"></i>
                <h1 class="text-success mb-2">Booking Confirmed!</h1>
                <p class="lead text-muted">Your e-ticket has been generated successfully.</p>
            </div>
            
            <hr class="my-4">
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <p class="fw-bold">PNR Number:</p>
                    <h3 class="text-primary"><?php echo htmlspecialchars($pnr); ?></h3>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="fw-bold">Total Paid:</p>
                    <h3 class="text-success">BDT <?php echo number_format($totalFare, 2); ?></h3>
                </div>
            </div>
            
            <div class="alert alert-info">
                <i class="fas fa-envelope me-2"></i> E-ticket details sent to your registered email/phone.
            </div>

            <h5 class="mt-4">Journey Details</h5>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr><th>Train Name:</th><td><?php echo htmlspecialchars($trainName); ?></td></tr>
                    <tr><th>Route:</th><td><?php echo htmlspecialchars($route); ?></td></tr>
                    <tr><th>Journey Date:</th><td><?php echo htmlspecialchars($date); ?></td></tr>
                    <tr><th>Booked Seats:</th><td><span class="fw-bold text-primary"><?php echo htmlspecialchars($seats); ?></span></td></tr>
                </tbody>
            </table>
            
            <div class="text-center mt-4">
                <button class="btn btn-primary btn-lg me-3" onclick="window.print()"><i class="fas fa-print me-2"></i> Print Ticket</button>
                <a href="passenger_dashboard.php" class="btn btn-secondary btn-lg"><i class="fas fa-list-alt me-2"></i> View My Bookings</a>
            </div>
            
        </div>
    </div>
</div>

<?php 
include 'includes/footer.php'; 
?>