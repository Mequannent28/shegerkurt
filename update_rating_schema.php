<?php
require 'db.php';
try {
    $pdo->exec("ALTER TABLE company_info ADD COLUMN IF NOT EXISTS google_maps_url TEXT AFTER address");
    $pdo->exec("ALTER TABLE company_info ADD COLUMN IF NOT EXISTS google_rating DECIMAL(3,1) DEFAULT 4.5 AFTER google_maps_url");
    $pdo->exec("ALTER TABLE company_info ADD COLUMN IF NOT EXISTS google_rating_count INT DEFAULT 100 AFTER google_rating");
    echo "Columns added successfully!";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
