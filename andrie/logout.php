<?php
/**
 * Administrator Logout (logout.php)
 * Portfolio for Sean Andrie Cubio
 */

require_once __DIR__ . '/db.php';

// Unset all session variables
$_SESSION = [];

// Delete the session cookie if present
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Destroy session
session_destroy();

// Redirect back to portfolio
header("Location: index.php?logged_out=1");
exit;
