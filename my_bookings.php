<?php 
// my_bookings.php
$pageTitle = "My Bookings";
include 'includes/config.php'; // Includes DB connection and session start
include 'includes/header.php'; // Includes navigation bar

// --- Start of Unique Page Content ---
?>

<div class="container my-5">
    <h1 class="text-center mb-4 text-primary"><i class="fas fa-ticket-alt me-2"></i> My Ticket Reservations</h1>
    
    <div class="card p-4">
        <?php 
        // 1. Check if the user is logged in
        if (!isset($_SESSION['user_id'])) {
            echo '<div class="alert alert-warning text-center">Please <a href="login.php" class="alert-link">log in</a> to view your bookings.</div>';
        } else {
            $user_id = $_SESSION['user_id'];
            $db = getMongoDB();
            $bookingsCursor = $db->bookings->find(
                ['user_id' => $user_id],
                ['sort' => ['created_at' => -1]]
            );
            
            $bookings = iterator_to_array($bookingsCursor);

            if (count($bookings) > 0) {
                // Display bookings in a table or card format
                echo "<h3>Your Recent Bookings:</h3>";
                echo '<div class="table-responsive">';
                echo '<table class="table table-striped">';
                echo '<thead><tr><th>PNR</th><th>Route</th><th>Date</th><th>Status</th><th>Action</th></tr></thead>';
                echo '<tbody>';
                foreach ($bookings as $booking) {
                    // Display booking details here
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($booking['pnr']) . '</td>';
                    echo '<td>' . htmlspecialchars($booking['from_station']) . ' to ' . htmlspecialchars($booking['to_station']) . '</td>';
                    echo '<td>' . htmlspecialchars($booking['journey_date']) . '</td>';
                    echo '<td>' . htmlspecialchars($booking['status']) . '</td>';
                    echo '<td><a href="ticket_confirmation.php?pnr=' . $booking['pnr'] . '" class="btn btn-sm btn-info">View</a></td>';
                    echo '</tr>';
                }
                echo '</tbody></table>';
                echo '</div>';
            } else {
                echo '<div class="alert alert-info text-center">You have no current or past bookings. Start your journey <a href="index.php" class="alert-link">here</a>!</div>';
            }
        }
        ?>
    </div>
</div>

<?php 
// --- End of Unique Page Content ---
include 'includes/footer.php'; 
?>