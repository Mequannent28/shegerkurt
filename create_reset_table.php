<?php
require 'db.php';
try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS password_resets (
        id INT AUTO_INCREMENT PRIMARY KEY,
        email VARCHAR(100),
        status ENUM('Pending', 'Completed') DEFAULT 'Pending',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
    echo "Password resets table created!";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
