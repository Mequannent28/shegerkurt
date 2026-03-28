<?php
require_once 'c:/xampp/htdocs/foodie-master/db.php';

try {
    // Check if table_id exists in reservations
    $stmt = $pdo->query("DESCRIBE reservations");
    $cols = $stmt->fetchAll(PDO::FETCH_COLUMN);
    if (!in_array('table_id', $cols)) {
        $pdo->exec("ALTER TABLE reservations ADD COLUMN table_id INT NULL AFTER id");
        echo "Column table_id added to reservations table.\n";
    }
    else {
        echo "Column table_id already exists.\n";
    }
}
catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
