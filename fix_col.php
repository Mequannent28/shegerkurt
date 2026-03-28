<?php
require_once 'c:/xampp/htdocs/foodie-master/db.php';
try {
    // Check if table_number exists
    $cols = $pdo->query("SHOW COLUMNS FROM restaurant_tables")->fetchAll(PDO::FETCH_COLUMN);
    if (in_array('table_number', $cols)) {
        $pdo->exec("ALTER TABLE restaurant_tables CHANGE table_number table_name VARCHAR(100)");
        echo "Column renamed to table_name.";
    } else {
        echo "Column table_number not found.";
    }
} catch (Exception $e) { echo $e->getMessage(); }
?>
