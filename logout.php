<?php
session_start();

// Log the logout activity if possible
if (isset($_SESSION['admin_name'])) {
    try {
        require_once 'db.php';
        $admin = $_SESSION['admin_name'];
        $stmt = $pdo->prepare("INSERT INTO activity_logs (action, admin_name) VALUES (?, ?)");
        $stmt->execute(["Logged out", $admin]);
    }
    catch (Exception $e) {
    // Silently fail — logging is not critical
    }
}
// Destroy session completely
$_SESSION = [];
// Delete session cookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}
session_destroy();
// Redirect to login page
header("Location: login.php");
exit;
?>
