<?php
require_once 'c:/xampp/htdocs/foodie-master/db.php';
try {
    // Check if uom column exists
    $stmt = $pdo->query("DESCRIBE menu_items");
    $cols = $stmt->fetchAll(PDO::FETCH_COLUMN);
    if (!in_array('uom', $cols)) {
        $pdo->exec("ALTER TABLE menu_items ADD COLUMN uom VARCHAR(20) DEFAULT 'pcs' AFTER image_url");
        echo "Column uom added.\n";
    } else {
        echo "Column uom already exists.\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
