<?php
require_once 'c:/xampp/htdocs/foodie-master/db.php';

try {
    // Create the tables table
    $pdo->exec("CREATE TABLE IF NOT EXISTS restaurant_tables (
        id INT AUTO_INCREMENT PRIMARY KEY,
        table_name VARCHAR(100),
        description TEXT,
        image_url VARCHAR(255),
        capacity INT DEFAULT 4,
        status ENUM('Available', 'Reserved', 'Occupied') DEFAULT 'Available',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    // Add table_id to reservations
    $check = $pdo->query("SHOW COLUMNS FROM reservations LIKE 'table_id'");
    if (!$check->fetch()) {
        $pdo->exec("ALTER TABLE reservations ADD COLUMN table_id INT AFTER guests");
    }

    // Seed the tables
    $pdo->exec("TRUNCATE TABLE restaurant_tables");
    $tables = [
        ['Table 1 - Traditional', 'Two set ground, traditional Mesob seating for an authentic experience. Perfect for traditional kurt lovers.', './assets/images/table_1_mesob.png', 4],
        ['Table 2 - Skyline', 'Night view of the breathtaking city skyline, perfect for romantic dinners and evening drinks.', './assets/images/banner-1.jpg', 2],
        ['Table 3 - Friendship', 'Spacious social table for friendship, family gatherings, and celebrations. Large and comfortable.', './assets/images/banner-2.jpg', 8],
        ['Table 4 - Lounge', 'Premium lounge seating with comfortable sofas and soft lighting. Great for relaxing with friends.', './assets/images/banner-3.jpg', 6],
        ['Table 5 - Private', 'Quiet and cozy private corner for confidential talks or peaceful dining. Tucked away in a quiet spot.', './assets/images/banner-4.jpg', 2]
    ];

    $stmt = $pdo->prepare("INSERT INTO restaurant_tables (table_name, description, image_url, capacity) VALUES (?, ?, ?, ?)");
    foreach ($tables as $t) {
        $stmt->execute($t);
    }

    echo "Restaurant tables table setup and seeded successfully!";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
