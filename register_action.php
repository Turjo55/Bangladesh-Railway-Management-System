<?php
// register_action.php
session_start();
require_once 'includes/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($name) || empty($email) || empty($password)) {
        die("Please fill all required fields.");
    }

    $db = getMongoDB();
    $usersCollection = $db->users;

    // Check if user exists
    $existingUser = $usersCollection->findOne(['email' => $email]);
    if ($existingUser) {
        echo "<script>alert('Email already registered!'); window.location.href='login.php';</script>";
        exit;
    }

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert new user
    $result = $usersCollection->insertOne([
        'name' => $name,
        'email' => $email,
        'phone' => $phone,
        'password' => $hashedPassword,
        'role' => 'Passenger',
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ]);

    if ($result->getInsertedCount() === 1) {
        // Auto login or redirect to login
        $_SESSION['user_id'] = (string)$result->getInsertedId();
        $_SESSION['user_role'] = 'Passenger';
        $_SESSION['user_name'] = $name;
        
        echo "<script>alert('Registration Successful!'); window.location.href='passenger_dashboard.php';</script>";
    } else {
        echo "<script>alert('Registration Failed. Please try again.'); window.location.href='login.php';</script>";
    }
}
?>
