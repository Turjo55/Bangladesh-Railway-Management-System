

<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// index.php
// # Passenger Portal - Home Page with Hero Banner and Search Form
$pageTitle = "Home";
include 'includes/config.php'; // # Connect DB and Start Session
include 'includes/header.php'; 

// Fetch stations for the search form
$stations = [];
$result = $conn->query("SELECT name FROM stations ORDER BY name ASC");
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $stations[] = htmlspecialchars($row['name']);
    }
}
?>

<section class="p-5 text-center bg-image shadow-1-strong"
    style="background-image: url('https://mdbcdn.b-cdn.net/img/new/slides/041.webp'); height: 400px; margin-top: -60px;">
    <div class="mask" style="background-color: rgba(0, 0, 0, 0.6);">
        <div class="d-flex justify-content-center align-items-center h-100">
            <div class="text-white">
                <h1 class="mb-3 display-4 fw-bold">Book Train Tickets Easily</h1>
                <h4 class="mb-4">Bangladesh Railway Online Ticketing System</h4>
            </div>
        </div>
    </div>
</section>
<div class="row justify-content-center" style="margin-top: -100px;">
    <div class="col-lg-10 col-xl-8">
        <div class="card search-form-container">
            <h4 class="card-title text-center mb-4 text-uppercase text-primary fw-bold">Find Your Train</h4>
            <form action="search_results.php" method="GET">
                <div class="row g-3">
                    <div class="col-md-3">
                        <div class="form-outline">
                            <select class="form-select" id="fromStation" name="fromStation" required>
                                <option value="" disabled selected>Select Origin</option>
                                <?php foreach ($stations as $station): ?>
                                    <option value="<?php echo $station; ?>"><?php echo $station; ?></option>
                                <?php endforeach; ?>
                            </select>
                            <label class="form-label" for="fromStation">From (Station)</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-outline">
                            <select class="form-select" id="toStation" name="toStation" required>
                                <option value="" disabled selected>Select Destination</option>
                                <?php foreach ($stations as $station): ?>
                                    <option value="<?php echo $station; ?>"><?php echo $station; ?></option>
                                <?php endforeach; ?>
                            </select>
                            <label class="form-label" for="toStation">To (Station)</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-outline">
                            <input type="date" id="journeyDate" name="journeyDate" class="form-control" min="<?php echo date('Y-m-d'); ?>" required />
                            <label class="form-label" for="journeyDate">Journey Date</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-outline">
                            <select class="form-select" id="travelClass" name="travelClass" required>
                                <option value="All" selected>All Classes</option>
                                <option value="AC_B">AC Berth</option>
                                <option value="Snigdha">Snigdha (AC Chair)</option>
                                <option value="Shovan">Shovan (Non-AC Chair)</option>
                                <option value="Non_AC">Non-AC</option>
                            </select>
                            <label class="form-label" for="travelClass">Class</label>
                        </div>
                    </div>
                </div>
                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-primary btn-lg px-5">
                        <i class="fas fa-search me-2"></i> Find Trains
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<hr class="my-5">

<section class="mb-5">
    <h2 class="text-center mb-4 text-primary">Featured Information</h2>
    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card p-4 h-100 text-center">
                <i class="fas fa-route fa-3x text-secondary mb-3"></i>
                <h5 class="card-title">Popular Routes</h5>
                <p class="card-text text-muted">Dhaka to Chattogram, Dhaka to Sylhet, and more of our busiest routes with frequent services.</p>
                <a href="search_results.php?fromStation=Dhaka (Kamalapur)&toStation=Chattogram&journeyDate=<?php echo date('Y-m-d'); ?>" class="btn btn-link">View Details</a>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card p-4 h-100 text-center">
                <i class="fas fa-clock fa-3x text-secondary mb-3"></i>
                <h5 class="card-title">Train Schedules</h5>
                <p class="card-text text-muted">Access the complete and up-to-date schedule for all Intercity and Mail Express trains.</p>
                <a href="search_results.php" class="btn btn-link">View Schedules</a>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card p-4 h-100 text-center">
                <i class="fas fa-ticket-alt fa-3x text-secondary mb-3"></i>
                <h5 class="card-title">Ticketing Guidelines</h5>
                <p class="card-text text-muted">Understand the rules for booking, cancellation, and refund eligibility clearly.</p>
                <a href="#" class="btn btn-link">Read Guide</a>
            </div>
        </div>
    </div>
</section>

<?php 
include 'includes/footer.php'; 
?>