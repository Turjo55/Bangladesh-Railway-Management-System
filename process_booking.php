<?php
// process_booking.php
session_start();
require_once 'includes/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $routeId = $_POST['route_id'] ?? null;
    $journeyDate = $_POST['date'] ?? null;
    $seats = $_POST['seats'] ?? null;
    $amount = $_POST['amount'] ?? 0;
    $gateway = $_POST['gateway'] ?? 'Cash';
    $userId = $_SESSION['user_id'] ?? null;
    
    if (!$routeId || !$journeyDate || !$seats) {
        die("Invalid booking data.");
    }
    
    if (!$userId) {
        // Could redirect to login or handle guest, but for now blocking
       // die("Please login to book tickets.");
       // Allow "Guest" logic or use session user
       $userId = 'guest_' . uniqid();
    }

    $db = getMongoDB();
    
    // 1. Fetch Route Info for Snapshot
    $routeDoc = $db->routes->findOne(['_id' => new MongoDB\BSON\ObjectId($routeId)]);
    if (!$routeDoc) { die("Route invalid"); }
    
    $train = $db->trains->findOne(['code' => $routeDoc['train_code']]);

    // 2. Generate PNR
    $pnr = 'BR' . strtoupper(substr(md5(uniqid()), 0, 8));

    // 3. Create Booking Document
    $booking = [
        'pnr' => $pnr,
        'user_id' => $userId, // Can be ObjectId or string
        'route_id' => new MongoDB\BSON\ObjectId($routeId),
        'train_name' => $train['name'], // Snapshot data
        'train_code' => $train['code'],
        'from_station' => $routeDoc['from_station'],
        'to_station' => $routeDoc['to_station'],
        'journey_date' => $journeyDate,
        'seats' => $seats, // "A-1,A-2"
        'total_amount' => (float)$amount,
        'payment_gateway' => $gateway,
        'payment_status' => 'Paid',
        'status' => 'Confirmed',
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ];

    $insertResult = $db->bookings->insertOne($booking);

    if ($insertResult->getInsertedCount() === 1) {
        // Redirect to confirmation
        header("Location: ticket_confirmation.php?pnr=" . $pnr);
        exit;
    } else {
        die("Booking failed. Please contact support.");
    }

} else {
    // Direct access not allowed
    header("Location: index.php");
    exit;
}
?>
