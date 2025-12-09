<?php
require_once __DIR__ . '/../vendor/autoload.php'; // Updated path for vendor autoload

use MongoDB\Client;
use Cloudinary\Configuration\Configuration;

// --- MongoDB Configuration ---
// REPLACE WITH YOUR ACTUAL CONNECTION STRING
$mongoConnectionString = getenv('MONGO_URI') ?: 'mongodb+srv://BdRail:tanvir123@bdrail.jlvfhwx.mongodb.net/?appName=BdRail';
$mongoDbName = getenv('MONGO_DB_NAME') ?: 'railway_db_bd';

try {
    $mongoClient = new Client($mongoConnectionString);
    $db = $mongoClient->selectDatabase($mongoDbName);
} catch (Exception $e) {
    die("Error connecting to MongoDB: " . $e->getMessage());
}

// --- Cloudinary Configuration ---
// REPLACE WITH YOUR ACTUAL CREDENTIALS
$cloudinaryCloudName = getenv('CLOUDINARY_CLOUD_NAME') ?: 'your_cloud_name';
$cloudinaryApiKey = getenv('CLOUDINARY_API_KEY') ?: 'your_api_key';
$cloudinaryApiSecret = getenv('CLOUDINARY_API_SECRET') ?: 'your_api_secret';

Configuration::instance([
    'cloud' => [
        'cloud_name' => $cloudinaryCloudName,
        'api_key' => $cloudinaryApiKey,
        'api_secret' => $cloudinaryApiSecret],
    'url' => [
        'secure' => true]]);
?>
