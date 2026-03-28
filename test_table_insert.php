<?php
// Direct test: simulate what the handler does when add_table is submitted
require_once 'db.php';
echo "<pre>";
try {
    // Try a direct insert
    $name = "Test Table Debug";
    $desc = "Debug description";
    $capacity = 4;
    $image_url = './assets/images/table_1_mesob.png';
    
    $stmt = $pdo->prepare("INSERT INTO restaurant_tables (table_name, description, image_url, capacity) VALUES (?, ?, ?, ?)");
    $result = $stmt->execute([$name, $desc, $image_url, $capacity]);
    
    echo "Insert result: " . ($result ? "SUCCESS" : "FAILED") . "\n";
    echo "Last insert ID: " . $pdo->lastInsertId() . "\n";
    echo "Error info: "; print_r($stmt->errorInfo());
    
    // Clean up test row
    if ($pdo->lastInsertId()) {
        $pdo->prepare("DELETE FROM restaurant_tables WHERE id=?")->execute([$pdo->lastInsertId()]);
        echo "\nTest row cleaned up.\n";
    }
} catch (Exception $e) {
    echo "EXCEPTION: " . $e->getMessage() . "\n";
    echo "Trace:\n" . $e->getTraceAsString();
}
echo "</pre>";
