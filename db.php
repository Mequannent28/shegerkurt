<?php
// Database Config with Environment Variable Support (for Render deployment)
$host = getenv('DB_HOST') ?: 'localhost';
$db   = getenv('DB_NAME') ?: 'sheger_kurt_db';
$user = getenv('DB_USER') ?: 'root';
$pass = getenv('DB_PASS') ?: '';
$charset = 'utf8mb4';

// For Render's direct MySQL URL (if provided as a single string)
$db_url = getenv('DATABASE_URL');
if ($db_url) {
    $parts = parse_url($db_url);
    if ($parts) {
        $host = $parts['host'] ?? $host;
        $db   = ltrim($parts['path'] ?? '', '/') ?: $db;
        $user = $parts['user'] ?? $user;
        $pass = $parts['pass'] ?? $pass;
    }
}

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
     // For MySQL, we need to handle both with and without DB selection
     $pdo = new PDO("mysql:host=$host;charset=$charset", $user, $pass, $options);
     $pdo->exec("CREATE DATABASE IF NOT EXISTS `$db` CHARACTER SET $charset COLLATE utf8mb4_unicode_ci");
     $pdo->exec("USE `$db`");
} catch (\PDOException $e) {
     // Fail quietly or log if needed
}
?>
