<?php
// setup_seeder.php
require_once 'includes/config.php';

$db = getMongoDB();

echo "Seeding MongoDB Atlas...<br>";

// 1. Seed Stations
$stationsCollection = $db->stations;
$stationsCollection->deleteMany([]); // Clear existing
$stations = [
    ['name' => 'Dhaka (Kamalapur)', 'code' => 'DA'],
    ['name' => 'Chattogram', 'code' => 'CTG'],
    ['name' => 'Sylhet', 'code' => 'SYL'],
    ['name' => 'Rajshahi', 'code' => 'RAJ'],
    ['name' => 'Khulna', 'code' => 'KHL'],
    ['name' => 'Mymensingh', 'code' => 'MYM']
];
$stationsCollection->insertMany($stations);
echo "Stations seeded.<br>";

// 2. Seed Trains
$trainsCollection = $db->trains;
$trainsCollection->deleteMany([]);
$trains = [
    ['name' => 'Suborno Express', 'code' => '701', 'type' => 'Intercity'],
    ['name' => 'Turna Express', 'code' => '741', 'type' => 'Intercity'],
    ['name' => 'Parabat Express', 'code' => '709', 'type' => 'Intercity'],
    ['name' => 'Sonar Bangla', 'code' => '787', 'type' => 'Intercity']
];
$trainsCollection->insertMany($trains);
echo "Trains seeded.<br>";

// 3. Seed Routes
$routesCollection = $db->routes;
$routesCollection->deleteMany([]);
$routes = [
    [
        'train_code' => '701', // Suborno
        'from_station' => 'Dhaka (Kamalapur)',
        'to_station' => 'Chattogram',
        'departure_time' => '07:00',
        'arrival_time' => '12:30',
        'fare_ac_b' => 1200,
        'fare_snigdha' => 800,
        'fare_shovan' => 450,
        'off_days' => ['Friday']
    ],
    [
        'train_code' => '709', // Parabat
        'from_station' => 'Dhaka (Kamalapur)',
        'to_station' => 'Sylhet',
        'departure_time' => '06:20',
        'arrival_time' => '13:00',
        'fare_ac_b' => 1100,
        'fare_snigdha' => 700,
        'fare_shovan' => 380,
        'off_days' => ['Tuesday']
    ]
];
$routesCollection->insertMany($routes);
echo "Routes seeded.<br>";

// 4. Create Admin User (if not exists)
$usersCollection = $db->users;
if (!$usersCollection->findOne(['email' => 'admin@br.gov.bd'])) {
    $usersCollection->insertOne([
        'name' => 'System Admin',
        'email' => 'admin@br.gov.bd',
        'password' => password_hash('admin123', PASSWORD_DEFAULT),
        'role' => 'Super Admin'
    ]);
    echo "Admin user created.<br>";
}

echo "Seeding Complete!";
?>
