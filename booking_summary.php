<?php 
// booking_summary.php
// # Booking Summary and Payment Gateway Interface
$pageTitle = "Booking Summary & Payment";
include 'includes/config.php';
include 'includes/header.php'; 

// # Fetch URL parameters
$routeId = $_GET['route_id'] ?? null;
$journeyDate = $_GET['date'] ?? date('Y-m-d');
$seatsSelected = $_GET['seats'] ?? ''; 
$totalFare = (float)($_GET['fare'] ?? 0); 

if (!$routeId) {
    die("Invalid Route Information");
}

$db = getMongoDB();
try {
   $routeDoc = $db->routes->findOne(['_id' => new MongoDB\BSON\ObjectId($routeId)]);
} catch(Exception $e) { $routeDoc = null; }

if (!$routeDoc) {
    die("Route details not found.");
}

$train = $db->trains->findOne(['code' => $routeDoc['train_code']]);

// # Sample data fetch (Replace with actual DB lookup based on trainId)
$trainName = $train['name'] . " (" . $train['code'] . ")";
$route = $routeDoc['from_station'] . " to " . $routeDoc['to_station'];
$passengerName = $_SESSION['user_name'] ?? "Guest User"; // Fetch from session

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
// # JAVASCRIPT FOR PAYMENT SIMULATION & BOOKING SUBMISSION
function simulatePayment(gateway) {
    const statusDiv = document.getElementById('payment-status');
    statusDiv.innerHTML = `<div class="alert alert-info"><i class="fas fa-spinner fa-spin me-2"></i> Processing Payment via ${gateway}...</div>`;
    
    // Simulate API call delay then Submit to Backend
    setTimeout(() => {
        statusDiv.innerHTML = `<div class="alert alert-success"><i class="fas fa-check-circle me-2"></i> Payment Successful! Generating Ticket...</div>`;
        
        // POST to process_booking.php
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = 'process_booking.php';

        const fields = {
            'route_id': '<?php echo $routeId; ?>',
            'date': '<?php echo $journeyDate; ?>',
            'seats': '<?php echo $seatsSelected; ?>',
            'amount': '<?php echo $totalFare; ?>',
            'gateway': gateway // Pass the gateway name
        };

        for (const key in fields) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = key;
            input.value = fields[key];
            form.appendChild(input);
        }
        document.body.appendChild(form);
        form.submit();
        
    }, 2000); 
}
</script>

<?php 
include 'includes/footer.php'; 
?>