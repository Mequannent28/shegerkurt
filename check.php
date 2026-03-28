<?php
require_once 'db.php';
echo "DB: " . $db . "\n";
$tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
echo "Tables:\n";
print_r($tables);
?>
