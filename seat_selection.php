<?php 
// seat_selection.php
// # Train Detail, Seat Selection, and Fare Calculation Page
$pageTitle = "Seat Selection";
include 'includes/config.php';
include 'includes/header.php'; 

// # Get Train ID from URL
// # Get parameters
$routeId = $_GET['route_id'] ?? null;
$journeyDate = $_GET['date'] ?? date('Y-m-d');

if (!$routeId) {
    die("Invalid Route ID");
}

$db = getMongoDB();

// # Fetch Route Details
try {
    $route = $db->routes->findOne(['_id' => new MongoDB\BSON\ObjectId($routeId)]);
} catch (Exception $e) {
    $route = null;
}

if (!$route) {
    die("Route not found");
}

// # Fetch Train Details
$train = $db->trains->findOne(['code' => $route['train_code']]);

$trainDetails = [];
if ($train) {
    $trainDetails['name'] = $train['name'];
    $trainDetails['code'] = $train['code'];
    $trainDetails['route'] = $route['from_station'] . ' to ' . $route['to_station'];
    $trainDetails['departure_time'] = $route['departure_time'];
    $trainDetails['arrival_time'] = $route['arrival_time'];
    
    // Duration
    $dep = strtotime($route['departure_time']);
    $arr = strtotime($route['arrival_time']);
    $diff = abs($arr - $dep);
    $trainDetails['duration'] = floor($diff / 3600) . 'h ' . floor(($diff % 3600) / 60) . 'm';
} else {
   die("Train details missing");
}

// # Mock Coaches and Fares (since we don't have a coaches collection yet, or it was embedded)
// In a real app, you might query $db->coaches->find(['train_code' => ...])
$trainDetails['coaches'] = [
    'KA' => ['type' => 'AC_B', 'seats' => 20],
    'KHA' => ['type' => 'Snigdha', 'seats' => 40],
    'GA' => ['type' => 'Snigdha', 'seats' => 40],
    'GHA' => ['type' => 'Shovan', 'seats' => 60],
    'UMO' => ['type' => 'Shovan', 'seats' => 60]
];

$trainDetails['fare'] = [
    'AC_B' => $route['fare_ac_b'] ?? 1000,
    'Snigdha' => $route['fare_snigdha'] ?? 600,
    'Shovan' => $route['fare_shovan'] ?? 300
];


// # Simple function to generate a seat grid (for visualization)
function generateSeatGrid($coach, $totalSeats, $classType, $fare) {
    $rows = ceil($totalSeats / 4);
    $html = '<div class="seat-map-grid">';
    $seatCounter = 1;
    for ($r = 1; $r <= $rows; $r++) {
        $html .= '<div class="seat-row">';
        for ($s = 1; $s <= 4; $s++) {
            if ($seatCounter > $totalSeats) break;
            
            // # Logic for visual seat status (e.g., booked seats)
            $isBooked = in_array($seatCounter, [5, 6, 15, 16]) && $coach === 'A'; // Sample booked seats
            
            $statusClass = 'available';
            $seatLabel = $coach . '-' . $seatCounter;
            $dataFare = $fare;

            if ($isBooked) {
                $statusClass = 'booked';
            }
            
            $html .= "<div class='seat-box {$statusClass}' data-seat='{$seatLabel}' data-coach='{$coach}' data-fare='{$dataFare}' data-status='{$statusClass}' title='Seat: {$seatLabel}'>";
            $html .= ($isBooked) ? '<i class="fas fa-times"></i>' : $seatCounter;
            $html .= '</div>';
            $seatCounter++;
        }
        $html .= '</div>';
    }
    $html .= '</div>';
    return $html;
}

?>

<style>
/* # Specific styles for Seat Selection Grid */
.seat-map-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    padding: 15px;
    border: 1px solid #ccc;
    border-radius: 5px;
    background-color: #f8f9fa;
}
.seat-row {
    display: flex;
    gap: 5px;
    width: 100%;
    margin-bottom: 5px;
}
.seat-box {
    width: 30px;
    height: 30px;
    line-height: 30px;
    text-align: center;
    border-radius: 4px;
    cursor: pointer;
    font-size: 12px;
    font-weight: bold;
    transition: all 0.2s;
    color: #fff;
    flex-grow: 1; 
}
.seat-box.available {
    background-color: #28a745; 
    box-shadow: 0 2px 5px rgba(40, 167, 69, 0.4);
}
.seat-box.available:hover {
    background-color: #218838;
    transform: scale(1.05);
}
.seat-box.selected {
    background-color: #007bff !important; 
    border: 2px solid #ffc107;
    box-shadow: 0 0 10px rgba(0, 123, 255, 0.8);
}
.seat-box.booked {
    background-color: #dc3545; 
    cursor: not-allowed;
    opacity: 0.7;
}
.legend-box {
    display: inline-block;
    width: 15px;
    height: 15px;
    border-radius: 3px;
    margin-right: 5px;
}
</style>

<div class="row">
    <div class="col-lg-4">
        <div class="card p-4 mb-4">
            <h4 class="card-title text-primary fw-bold"><?php echo htmlspecialchars($trainDetails['name']); ?> <span class="badge bg-primary ms-2"><?php echo htmlspecialchars($trainDetails['code']); ?></span></h4>
            <h6 class="text-muted mb-3"><?php echo htmlspecialchars($trainDetails['route']); ?></h6>
            
            <ul class="list-unstyled">
                <li class="mb-2"><i class="far fa-clock me-2"></i> **Departure:** <?php echo htmlspecialchars($trainDetails['departure_time']); ?></li>
                <li class="mb-2"><i class="fas fa-clock me-2"></i> **Arrival:** <?php echo htmlspecialchars($trainDetails['arrival_time']); ?></li>
                <li class="mb-2"><i class="fas fa-hourglass-half me-2"></i> **Duration:** <?php echo htmlspecialchars($trainDetails['duration']); ?></li>
                <li class="mb-2"><i class="fas fa-calendar-alt me-2"></i> **Journey Date:** <?php echo htmlspecialchars($journeyDate); ?></li>
            </ul>

            <h6 class="mt-3 text-secondary">Seat Legend</h6>
            <div>
                <span class="legend-box available" style="background-color: #28a745;"></span> Available
                <span class="legend-box selected" style="background-color: #007bff;"></span> Selected
                <span class="legend-box booked" style="background-color: #dc3545;"></span> Booked
            </div>
        </div>
    </div>
    
    <div class="col-lg-8">
        <ul class="nav nav-tabs mb-3" id="coachTabs" role="tablist">
            <?php $first = true; foreach ($trainDetails['coaches'] as $coachId => $coach): ?>
                <li class="nav-item" role="presentation">
                    <button class="nav-link <?php echo $first ? 'active' : ''; ?>" id="coach-<?php echo $coachId; ?>-tab" data-mdb-toggle="tab" data-mdb-target="#coach-<?php echo $coachId; ?>" type="button" role="tab" aria-controls="coach-<?php echo $coachId; ?>" aria-selected="<?php echo $first ? 'true' : 'false'; ?>">
                        Coach **<?php echo $coachId; ?>** (<small><?php echo htmlspecialchars($coach['type']); ?> - BDT <?php echo number_format($trainDetails['fare'][$coach['type']] ?? 0); ?></small>)
                    </button>
                </li>
            <?php $first = false; endforeach; ?>
        </ul>
        
        <div class="tab-content" id="coachTabsContent">
            <?php $first = true; foreach ($trainDetails['coaches'] as $coachId => $coach): ?>
                <div class="tab-pane fade <?php echo $first ? 'show active' : ''; ?>" id="coach-<?php echo $coachId; ?>" role="tabpanel" aria-labelledby="coach-<?php echo $coachId; ?>-tab">
                    <div class="card p-4 mb-4">
                        <h5 class="card-title">Coach **<?php echo $coachId; ?>** - **<?php echo htmlspecialchars($coach['type']); ?>**</h5>
                        <div class="d-flex justify-content-center">
                            <div class="p-3 border rounded" style="width: fit-content; background-color: #e9ecef;">
                                <div class="text-center mb-3">**DRIVER'S CABIN**</div>
                                <?php echo generateSeatGrid($coachId, $coach['seats'], $coach['type'], $trainDetails['fare'][$coach['type']] ?? 0); ?>
                                <div class="text-center mt-3">**DOOR**</div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php $first = false; endforeach; ?>
        </div>

        <div class="card p-4 mt-4">
            <h4 class="text-secondary">Booking Summary</h4>
            <div class="row mt-3">
                <div class="col-6">
                    <p class="mb-1">Selected Seats: <strong id="selectedSeatsCount">0</strong></p>
                    <p class="mb-1">Total Seat Fare:</p>
                    <p class="mb-1">Service Charge (5%):</p>
                    <p class="mb-1 fw-bold fs-5">Total Payable Fare:</p>
                </div>
                <div class="col-6 text-end">
                    <p class="mb-1" id="seatsList">No seats selected</p>
                    <p class="mb-1 fw-bold text-success">BDT <span id="seatFareAmount">0</span></p>
                    <p class="mb-1 text-muted">BDT <span id="serviceChargeAmount">0</span></p>
                    <p class="mb-1 fw-bold fs-5 text-primary">BDT <span id="totalFareAmount">0</span></p>
                </div>
            </div>
            
            <div class="text-center mt-4">
                <a href="booking_summary.php?route_id=<?php echo $routeId; ?>&date=<?php echo $journeyDate; ?>" class="btn btn-lg btn-success disabled" id="proceedToBookingBtn">
                    <i class="fas fa-arrow-right me-2"></i> Proceed to Passenger Details
                </a>
            </div>
        </div>
    </div>
</div>

<script>
// # JAVASCRIPT FOR SEAT SELECTION LOGIC AND FARE CALCULATION
const selectedSeats = {}; // Format: { 'A-5': 1200, 'B-10': 850 }

document.addEventListener('DOMContentLoaded', () => {
    // # 1. SEAT SELECTION LOGIC
    document.querySelectorAll('.seat-box.available').forEach(seat => {
        seat.addEventListener('click', function() {
            const seatLabel = this.getAttribute('data-seat');
            const fare = parseFloat(this.getAttribute('data-fare'));

            if (this.classList.contains('selected')) {
                // Deselect
                this.classList.remove('selected');
                delete selectedSeats[seatLabel];
            } else {
                // Select
                this.classList.add('selected');
                selectedSeats[seatLabel] = fare;
            }
            updateSummary();
        });
    });

    // # 2. SUMMARY UPDATE LOGIC
    function updateSummary() {
        const seatCount = Object.keys(selectedSeats).length;
        let totalSeatFare = 0;
        let seatListText = '';
        
        for (const seat in selectedSeats) {
            totalSeatFare += selectedSeats[seat];
            seatListText += seat + ' ';
        }

        const serviceChargeRate = 0.05; // 5% Service Charge
        const serviceCharge = totalSeatFare * serviceChargeRate;
        const totalPayableFare = totalSeatFare + serviceCharge;

        // Update DOM elements, using toFixed(0) for whole numbers as BDT
        document.getElementById('selectedSeatsCount').textContent = seatCount;
        document.getElementById('seatsList').textContent = seatListText || 'No seats selected';
        document.getElementById('seatFareAmount').textContent = totalSeatFare.toFixed(0);
        document.getElementById('serviceChargeAmount').textContent = serviceCharge.toFixed(0);
        document.getElementById('totalFareAmount').textContent = totalPayableFare.toFixed(0);

        // Toggle button state
        const proceedBtn = document.getElementById('proceedToBookingBtn');
        if (seatCount > 0) {
            proceedBtn.classList.remove('disabled');
            // # Update URL parameter to pass selected seats for the next step
            const seatQuery = Object.keys(selectedSeats).join(',');
            proceedBtn.href = `booking_summary.php?route_id=<?php echo $routeId; ?>&date=<?php echo $journeyDate; ?>&seats=${seatQuery}&fare=${totalPayableFare.toFixed(2)}`;
        } else {
            proceedBtn.classList.add('disabled');
            proceedBtn.href = '#';
        }
    }
});
</script>

<?php 
include 'includes/footer.php'; 
?>