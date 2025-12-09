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

// # Query to fetch trains (Using MongoDB)
// Fetch routes matching origin/dest
$db = getMongoDB();
$routesCursor = $db->routes->find([
    'from_station' => $from,
    'to_station' => $to
]);

$availableTrains = [];

foreach ($routesCursor as $route) {
    // Fetch train details for this route
    $trainCode = $route['train_code'];
    $train = $db->trains->findOne(['code' => $trainCode]);

    if ($train) {
        $trainData = [
            'train_id' => (string)$train['_id'], // Use MongoID as ID
            'route_id' => (string)$route['_id'], // Add Route ID
            'train_code' => $train['code'],
            'name' => $train['name'],
            'type' => $train['type'],
            'running_days' => 'Daily', // Placeholder as it wasn't in seeder clearly
            'departure_time' => $route['departure_time'],
            'arrival_time' => $route['arrival_time'],
            'duration' => '', // Calculate below
            'classes' => [],
            'availability' => []
        ];

        // Map fares from route to classes array
        if (isset($route['fare_ac_b'])) $trainData['classes']['AC_B'] = $route['fare_ac_b'];
        if (isset($route['fare_snigdha'])) $trainData['classes']['Snigdha'] = $route['fare_snigdha'];
        if (isset($route['fare_shovan'])) $trainData['classes']['Shovan'] = $route['fare_shovan'];

        // Availability Placeholder
        foreach ($trainData['classes'] as $cls => $price) {
            $trainData['availability'][$cls] = rand(5, 50);
        }

        // Calculate Duration
        $dep = strtotime($route['departure_time']);
        $arr = strtotime($route['arrival_time']);
        $diff = abs($arr - $dep);
        $trainData['duration'] = floor($diff / 3600) . 'h ' . floor(($diff % 3600) / 60) . 'm';

        $availableTrains[] = $trainData;
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
                                <a href="seat_selection.php?route_id=<?php echo $train['route_id']; ?>&date=<?php echo htmlspecialchars($date); ?>" class="btn btn-secondary btn-sm">Select Seat</a>
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