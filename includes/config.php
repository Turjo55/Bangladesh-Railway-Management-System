<?php
// Start the session at the very beginning of execution
session_start();

// Database Connection Constants
// Database Connection Constants
// define('DB_SERVER', 'localhost'); // usually 'localhost'
// define('DB_USERNAME', 'root');
// define('DB_PASSWORD', ''); 
// define('DB_NAME', 'railway_db_bd');

// Attempt to connect to MySQL database
// $conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// if ($conn === false) {
//     die("ERROR: Could not connect. " . mysqli_connect_error());
// }

// Include MongoDB & Cloudinary Config
require_once 'db_mongo.php';

// Helper function to get MongoDB Database instance
function getMongoDB() {
    global $db;
    return $db;
}

// --- Global Functions and Variables ---

// Define the root URL for asset loading (optional, but good practice)
define('BASE_URL', 'http://localhost/railway-management-system/');

// Function to safely fetch data (using prepared statements later is better, but this works for basic fetch)
function fetch_data($sql) {
    global $conn;
    $result = mysqli_query($conn, $sql);
    $data = [];
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
    }
    return $data;
}

?>