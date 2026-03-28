<?php
require 'c:/xampp/htdocs/foodie-master/db.php';
try {
    $pdo->exec("ALTER TABLE company_info ADD COLUMN IF NOT EXISTS dev_whatsapp VARCHAR(255) AFTER dev_photo");
    $pdo->exec("ALTER TABLE company_info ADD COLUMN IF NOT EXISTS dev_telegram VARCHAR(255) AFTER dev_whatsapp");
    $pdo->exec("ALTER TABLE company_info ADD COLUMN IF NOT EXISTS dev_linkedin VARCHAR(255) AFTER dev_telegram");
    echo "Developer columns added successfully!";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
