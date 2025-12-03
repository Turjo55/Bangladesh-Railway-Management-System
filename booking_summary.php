<?php 
// booking_summary.php
// # Booking Summary and Payment Gateway Interface
$pageTitle = "Booking Summary & Payment";
include 'includes/config.php';
include 'includes/header.php'; 

// # Fetch URL parameters
$trainId = $_GET['train_id'] ?? 1;
$journeyDate = $_GET['date'] ?? date('Y-m-d');
$seatsSelected = $_GET['seats'] ?? 'A-1,B-2'; 
$totalFare = (float)($_GET['fare'] ?? 2100.00); 

// # Sample data fetch (Replace with actual DB lookup based on trainId)
$trainName = "Suborno Express (701)";
$route = "Dhaka to Chattogram";
$passengerName = "Rahim Ahmed"; // Placeholder from session/DB

// Calculate charges
$seatFare = $totalFare / 1.05; // Reverse calculate base fare (assuming 5% charge)
$serviceCharge = $totalFare - $seatFare;

?>

<h2 class="text-primary mb-4">Finalize Your Booking</h2>

<div class="row">
    <div class="col-lg-6">
        <div class="card p-4 mb-4">
            <h4 class="card-title text-secondary">Journey & Passenger Details</h4>
            <hr>
            <dl class="row">
                <dt class="col-sm-4 text-muted">Train/Route:</dt>
                <dd class="col-sm-8 fw-bold text-primary"><?php echo htmlspecialchars($trainName); ?> - <?php echo htmlspecialchars($route); ?></dd>
                
                <dt class="col-sm-4 text-muted">Journey Date:</dt>
                <dd class="col-sm-8"><?php echo htmlspecialchars($journeyDate); ?></dd>
                
                <dt class="col-sm-4 text-muted">Passenger:</dt>
                <dd class="col-sm-8"><?php echo htmlspecialchars($passengerName); ?></dd>
                
                <dt class="col-sm-4 text-muted">Seats Booked:</dt>
                <dd class="col-sm-8 text-success fw-bold"><?php echo htmlspecialchars($seatsSelected); ?> (<?php echo count(explode(',', $seatsSelected)); ?> seats)</dd>
            </dl>
            
            <h4 class="card-title text-secondary mt-4">Fare Breakdown</h4>
            <hr>
            <dl class="row">
                <dt class="col-sm-6">Total Seat Fare:</dt>
                <dd class="col-sm-6 text-end">BDT <?php echo number_format($seatFare, 2); ?></dd>
                
                <dt class="col-sm-6">Service Charge (5%):</dt>
                <dd class="col-sm-6 text-end">BDT <?php echo number_format($serviceCharge, 2); ?></dd>
                
                <dt class="col-sm-6 fs-5 fw-bold text-primary">Total Payable:</dt>
                <dd class="col-sm-6 text-end fs-5 fw-bold text-primary">BDT <?php echo number_format($totalFare, 2); ?></dd>
            </dl>
        </div>
    </div>
    
    <div class="col-lg-6">
        <div class="card p-4 mb-4">
            <h4 class="card-title text-secondary">Select Payment Method</h4>
            <p class="text-muted">Total amount to pay: <strong class="text-primary">BDT <?php echo number_format($totalFare, 2); ?></strong></p>
            
            <ul class="nav nav-pills nav-fill mb-3" id="paymentTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="mobile-tab" data-mdb-toggle="tab" data-mdb-target="#mobile" type="button" role="tab" aria-controls="mobile" aria-selected="true"><i class="fas fa-mobile-alt me-2"></i> Mobile Wallet</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="card-tab" data-mdb-toggle="tab" data-mdb-target="#card" type="button" role="tab" aria-controls="card" aria-selected="false"><i class="fas fa-credit-card me-2"></i> Card / Bank</button>
                </li>
            </ul>

            <div class="tab-content" id="paymentTabContent">
                <div class="tab-pane fade show active" id="mobile" role="tabpanel" aria-labelledby="mobile-tab">
                    <p class="text-muted">Select your preferred Mobile Financial Service (MFS):</p>
                    <div class="d-grid gap-2">
                        <button class="btn btn-outline-danger btn-lg" onclick="simulatePayment('bKash')"><i class="fas fa-wallet me-2"></i> Pay with bKash</button>
                        <button class="btn btn-outline-warning btn-lg" onclick="simulatePayment('Nagad')"><i class="fas fa-wallet me-2"></i> Pay with Nagad</button>
                        <button class="btn btn-outline-info btn-lg" onclick="simulatePayment('Rocket')"><i class="fas fa-wallet me-2"></i> Pay with Rocket</button>
                    </div>
                </div>
                <div class="tab-pane fade" id="card" role="tabpanel" aria-labelledby="card-tab">
                    <p class="text-muted">This option will redirect you to the bank gateway for secure card processing.</p>
                    <div class="d-grid gap-2">
                        <button class="btn btn-outline-primary btn-lg" onclick="simulatePayment('Card')"><i class="fas fa-credit-card me-2"></i> Pay with Debit/Credit Card</button>
                        <button class="btn btn-outline-primary btn-lg" onclick="simulatePayment('Bank')"><i class="fas fa-university me-2"></i> Online Banking</button>
                    </div>
                </div>
            </div>
            
            <div id="payment-status" class="mt-4 text-center"></div>
        </div>
    </div>
</div>

<script>
// # JAVASCRIPT FOR PAYMENT SIMULATION
function simulatePayment(gateway) {
    const statusDiv = document.getElementById('payment-status');
    statusDiv.innerHTML = `<div class="alert alert-info"><i class="fas fa-spinner fa-spin me-2"></i> Redirecting to ${gateway} gateway...</div>`;
    
    // Simulate API call delay
    setTimeout(() => {
        // Assume successful payment for demonstration
        statusDiv.innerHTML = `<div class="alert alert-success"><i class="fas fa-check-circle me-2"></i> Payment via ${gateway} Successful! Redirecting to ticket page...</div>`;
        
        // Redirect to a placeholder ticket page
        setTimeout(() => {
            window.location.href = `ticket_confirmation.php?pnr=BRTICKET${Math.floor(Math.random() * 90000 + 10000)}`;
        }, 2000);
        
    }, 3000); // 3 second delay
}
</script>

<?php 
include 'includes/footer.php'; 
?>