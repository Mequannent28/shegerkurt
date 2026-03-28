<?php
require 'db.php';
try {
    $cols = [
        "tiktok" => "TEXT",
        "linkedin" => "TEXT",
        "telegram" => "TEXT",
        "whatsapp" => "TEXT",
        "ceo_name" => "VARCHAR(100)",
        "ceo_title" => "VARCHAR(100)",
        "ceo_message" => "TEXT",
        "ceo_image" => "VARCHAR(255)"
    ];
    
    foreach ($cols as $name => $type) {
        $pdo->exec("ALTER TABLE company_info ADD COLUMN IF NOT EXISTS `$name` $type");
    }
    
    echo "company_info table expanded successfully!";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
