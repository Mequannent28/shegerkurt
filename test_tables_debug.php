<?php
require_once 'db.php';
echo "<pre>";
try {
    $r = $pdo->query("DESCRIBE restaurant_tables");
    echo "Columns:\n";
    while ($row = $r->fetch(PDO::FETCH_ASSOC)) {
        echo "  " . $row['Field'] . " (" . $row['Type'] . ")\n";
    }
    echo "\nRows:\n";
    $rows = $pdo->query("SELECT * FROM restaurant_tables")->fetchAll(PDO::FETCH_ASSOC);
    print_r($rows);
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage();
}
echo "</pre>";
