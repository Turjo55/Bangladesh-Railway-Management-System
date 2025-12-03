<?php
// Start the session at the very beginning of execution
session_start();

// Database Connection Constants
define('DB_SERVER', 'localhost'); // Usually 'localhost' for XAMPP
define('DB_USERNAME', 'root');    // Default XAMPP username is 'root'
define('DB_PASSWORD', '');        // Default XAMPP password is empty (no password)
define('DB_NAME', 'railway_db_bd'); // Ensure this EXACTLY matches your database name

// Attempt to connect to MySQL database
$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check connection
if ($conn === false) {
    // If the connection fails, stop the script and print an error message
    die("ERROR: Could not connect. " . mysqli_connect_error());
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