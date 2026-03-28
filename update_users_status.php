<?php
require 'db.php';
try {
    $pdo->exec("ALTER TABLE users ADD COLUMN IF NOT EXISTS status ENUM('Pending', 'Active', 'Disabled') DEFAULT 'Pending'");
    // Make first user (admin) active automatically if not already
    $pdo->exec("UPDATE users SET status = 'Active' WHERE id = 1");
    echo "Users table updated!";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
