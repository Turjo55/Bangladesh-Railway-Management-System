<?php
// ...existing code...
// Logout: destroy session and redirect to homepage
if (session_status() === PHP_SESSION_NONE) session_start();

// Unset all session variables
$_SESSION = [];

// Destroy session cookie if present
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Destroy the session
session_destroy();

// Redirect to home (relative)
header('Location: index.php');
exit;
// ...existing code...