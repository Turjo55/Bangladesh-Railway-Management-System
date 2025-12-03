<?php 
// search_results.php
// # Train Search Results Page with Filters and Sorting
$pageTitle = "Search Results";
include 'includes/config.php';
include 'includes/header.php'; 

// # Get Search Parameters from URL (for display only)
$from = $_GET['fromStation'] ?? 'Dhaka (Kamalapur)';
$to = $_GET['toStation'] ?? 'Chattogram';
$date = $_GET['journeyDate'] ?? date('Y-m-d');
$class = $_GET['travelClass'] ?? 'All';

// # Query to fetch trains (Highly simplified, needs complex joins for full functionality)
$sql = "
SELECT 
    t.train_id, t.train_code, t.name, t.type, t.running_days, 
    t.departure_time, t.arrival_time,
    GROUP_CONCAT(c.class_type, ':', c.base_fare SEPARATOR ';') AS fares_data
FROM trains t
LEFT JOIN coaches c ON t.train_id = c.train_id
GROUP BY t.train_id
ORDER BY t.departure_time ASC
";
$result = $conn->query($sql);
$availableTrains = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $train = $row;
        $train['classes'] = [];
        // Parse fares data string (e.g., "AC_B:1200.00;Snigdha:850.00")
        foreach (explode(';', $row['fares_data']) as $farePair) {
            list($classCode, $price) = explode(':', $farePair);
            $train['classes'][$classCode] = (int)$price;
            // # Availability Placeholder: Needs DB lookup in real system
            $train['availability'][$classCode] = rand(0, 80); 
        }
        // # Calculate duration (Simple subtraction is inaccurate across midnight, but okay for mock)
        $dep = strtotime($row['departure_time']);
        $arr = strtotime($row['arrival_time']);
        $diff = abs($arr - $dep);
        $train['duration'] = floor($diff / 3600) . 'h ' . floor(($diff % 3600) / 60) . 'm';
        
        $availableTrains[] = $train;
    }
}

// # Function to convert class code to full name
function getClassFullName($code) {
    $map = ['AC_B' => 'AC Berth', 'AC_C' => 'AC Chair', 'Snigdha' => 'Snigdha', 'Shovan' => 'Shovan', 'Non_AC' => 'Non-AC'];
    return $map[$code] ?? $code;
}

?>

<h2 class="text-primary mb-4">Trains from **<?php echo htmlspecialchars($from); ?>** to **<?php echo htmlspecialchars($to); ?>** on **<?php echo htmlspecialchars($date); ?>**</h2>

<div class="row g-4">
    <div class="col-lg-3">
        <div class="card p-3 mb-4">
            <h5 class="text-primary mb-3">Filters & Sort</h5>
            <form>
                <div class="mb-3">
                    <label class="form-label fw-bold">Class Type</label>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="AC" id="filterAC" checked />
                        <label class="form-check-label" for="filterAC"> AC </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="Non-AC" id="filterNonAC" />
                        <label class="form-check-label" for="filterNonAC"> Non-AC </label>
                    </div>
                </div>
                <hr>
                <div class="mb-3">
                    <label class="form-label fw-bold">Sort By</label>
                    <select class="form-select" name="sortBy">
                        <option value="price_asc">Price (Lowest First)</option>
                        <option value="time_asc">Departure Time</option>
                        <option value="fastest">Fastest Route</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-sm btn-outline-primary w-100">Apply Filters</button>
            </form>
        </div>
    </div>

    <div class="col-lg-9">
        <?php if (count($availableTrains) > 0): ?>
            <?php foreach ($availableTrains as $train): ?>
                <div class="card mb-3 shadow-sm train-card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <h5 class="fw-bold text-primary"><?php echo htmlspecialchars($train['name']); ?> <span class="badge bg-secondary ms-2"><?php echo htmlspecialchars($train['train_code']); ?></span></h5>
                                <p class="mb-1 text-muted"><i class="fas fa-train me-1"></i> **<?php echo htmlspecialchars($train['type']); ?>**</p>
                                <p class="text-muted"><i class="far fa-calendar-alt me-1"></i> Running: <?php echo htmlspecialchars($train['running_days']); ?></p>
                            </div>

                            <div class="col-md-3 text-center">
                                <h6 class="mb-0"><?php echo date('H:i', strtotime($train['departure_time'])); ?> <i class="fas fa-long-arrow-alt-right mx-2 text-secondary"></i> <?php echo date('H:i', strtotime($train['arrival_time'])); ?></h6>
                                <small class="text-muted">Total Journey: <?php echo htmlspecialchars($train['duration']); ?></small>
                            </div>

                            <div class="col-md-3">
                                <ul class="list-unstyled mb-0">
                                    <?php 
                                    foreach ($train['classes'] as $classCode => $price): 
                                        $availableSeats = $train['availability'][$classCode] ?? 0;
                                        $availabilityColor = $availableSeats > 20 ? 'text-success' : ($availableSeats > 0 ? 'text-warning' : 'text-danger');
                                        $statusText = $availableSeats > 0 ? $availableSeats . ' Seats Avl.' : 'Sold Out';
                                    ?>
                                    <li class="mb-1">
                                        <span class="badge rounded-pill bg-light text-dark me-2"><?php echo getClassFullName($classCode); ?></span>
                                        <strong class="text-success">BDT <?php echo number_format($price); ?></strong>
                                        <small class="ms-2 <?php echo $availabilityColor; ?>"><?php echo $statusText; ?></small>
                                    </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>

                            <div class="col-md-2 text-end">
                                <a href="seat_selection.php?train_id=<?php echo $train['train_id']; ?>&date=<?php echo htmlspecialchars($date); ?>" class="btn btn-secondary btn-sm">Select Seat</a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
        <?php else: ?>
            <div class="alert alert-warning text-center" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i> No trains found for the selected route and date. Please try another search.
            </div>
        <?php endif; ?>
    </div>
</div>


<?php 
include 'includes/footer.php'; 
?>